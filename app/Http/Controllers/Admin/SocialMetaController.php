<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialConnection;
use App\Models\SiteArtigo;
use App\Services\MetaGraphService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocialMetaController extends Controller
{
    public function __construct(private MetaGraphService $meta) {}

    /* ─────────────────────────────────────────────────────────
     |  Tela de configuração — mostra status da conexão
     ──────────────────────────────────────────────────────── */
    public function index()
    {
        $empresaId  = $this->resolverEmpresaId();
        $connection = SocialConnection::where('empresa_id', $empresaId)->first();

        return view('admin.social.index', compact('connection'));
    }

    /* ─────────────────────────────────────────────────────────
     |  Redireciona para OAuth do Facebook
     ──────────────────────────────────────────────────────── */
    public function connect(Request $request)
    {
        $redirectUri = 'https://' . $request->getHost() . route('admin.social.callback', [], false);
        $url = $this->meta->getOAuthUrl($redirectUri);

        return redirect($url);
    }

    /* ─────────────────────────────────────────────────────────
     |  Callback OAuth — salva tokens e páginas
     ──────────────────────────────────────────────────────── */
    public function callback(Request $request)
    {
        if ($request->has('error') || $request->has('error_code')) {
            $desc = $request->error_description ?? $request->error_message ?? $request->error ?? 'erro desconhecido';
            Log::warning('SocialMeta: OAuth cancelado', $request->all());
            return redirect()->route('admin.social.index')
                ->with('error', 'Autorização cancelada: ' . $desc);
        }

        if (!$request->filled('code')) {
            Log::warning('SocialMeta: callback sem code', $request->all());
            return redirect()->route('admin.social.index')
                ->with('error', 'Facebook não retornou o código de autorização. Verifique as configurações do app.');
        }

        $empresaId   = $this->resolverEmpresaId();
        $redirectUri = 'https://' . $request->getHost() . route('admin.social.callback', [], false);

        try {
            // Troca code por token longo
            $tokenData = $this->meta->exchangeCodeForToken($request->code, $redirectUri);
            $userToken = $tokenData['access_token'];
            $expiresAt = now()->addSeconds($tokenData['expires_in']);

            // Busca páginas do usuário
            $pages = $this->meta->getUserPages($userToken);

            if (empty($pages)) {
                return redirect()->route('admin.social.index')
                    ->with('error', 'Nenhuma Página do Facebook encontrada. Você precisa administrar uma Página.');
            }

            // Se mais de uma página, deixa o usuário escolher
            if (count($pages) > 1) {
                session([
                    'meta_pages'      => $pages,
                    'meta_user_token' => $userToken,
                    'meta_expires_at' => $expiresAt->toDateTimeString(),
                    'meta_empresa_id' => $empresaId,
                ]);
                return redirect()->route('admin.social.select-page');
            }

            // Só uma página — conecta direto
            return $this->salvarPagina($pages[0], $userToken, $expiresAt, $empresaId);

        } catch (\Exception $e) {
            Log::error('SocialMeta: erro no callback', ['erro' => $e->getMessage()]);
            return redirect()->route('admin.social.index')
                ->with('error', 'Erro ao conectar: ' . $e->getMessage());
        }
    }

    /* ─────────────────────────────────────────────────────────
     |  Seleção de página — exibe lista
     ──────────────────────────────────────────────────────── */
    public function selectPage()
    {
        $pages = session('meta_pages');
        if (empty($pages)) {
            return redirect()->route('admin.social.index')
                ->with('error', 'Sessão expirada. Conecte novamente.');
        }
        return view('admin.social.select-page', compact('pages'));
    }

    /* ─────────────────────────────────────────────────────────
     |  Seleção de página — salva escolha
     ──────────────────────────────────────────────────────── */
    public function storePage(Request $request)
    {
        $request->validate(['page_id' => 'required|string']);

        $pages     = session('meta_pages', []);
        $userToken = session('meta_user_token');
        $expiresAt = session('meta_expires_at') ? \Carbon\Carbon::parse(session('meta_expires_at')) : now()->addDays(60);
        $empresaId = session('meta_empresa_id') ?? $this->resolverEmpresaId();

        $page = collect($pages)->firstWhere('id', $request->page_id);
        if (!$page) {
            return redirect()->route('admin.social.index')
                ->with('error', 'Página não encontrada. Conecte novamente.');
        }

        session()->forget(['meta_pages', 'meta_user_token', 'meta_expires_at', 'meta_empresa_id']);

        return $this->salvarPagina($page, $userToken, $expiresAt, $empresaId);
    }

    /* ─────────────────────────────────────────────────────────
     |  Salva conexão de uma página escolhida
     ──────────────────────────────────────────────────────── */
    private function salvarPagina(array $page, string $userToken, $expiresAt, int $empresaId)
    {
        $pageId      = $page['id'];
        $pageName    = $page['name'];
        $pageToken   = $page['access_token'];
        $igAccountId = $page['instagram_business_account']['id'] ?? null;
        $igUsername  = '';

        if ($igAccountId) {
            $igUsername = $this->meta->getInstagramUsername($igAccountId, $pageToken);
        }

        SocialConnection::updateOrCreate(
            ['empresa_id' => $empresaId],
            [
                'facebook_page_id'     => $pageId,
                'facebook_page_name'   => $pageName,
                'facebook_page_token'  => $pageToken,
                'instagram_account_id' => $igAccountId,
                'instagram_username'   => $igUsername,
                'user_access_token'    => $userToken,
                'token_expires_at'     => $expiresAt,
            ]
        );

        Log::info('SocialMeta: conexão salva', ['empresa_id' => $empresaId, 'page' => $pageName]);

        return redirect()->route('admin.social.index')
            ->with('success', "Conectado com sucesso! Página: {$pageName}" . ($igUsername ? " | Instagram: @{$igUsername}" : ''));
    }

    /* ─────────────────────────────────────────────────────────
     |  Desconectar
     ──────────────────────────────────────────────────────── */
    public function disconnect()
    {
        $empresaId = $this->resolverEmpresaId();
        SocialConnection::where('empresa_id', $empresaId)->delete();

        return redirect()->route('admin.social.index')
            ->with('success', 'Contas desconectadas.');
    }

    /* ─────────────────────────────────────────────────────────
     |  Postar artigo nas redes sociais
     ──────────────────────────────────────────────────────── */
    public function postArtigo(Request $request, SiteArtigo $artigo)
    {
        $request->validate([
            'redes' => 'required|array|min:1',
            'redes.*' => 'in:facebook,instagram',
        ]);

        $empresaId  = $this->resolverEmpresaId();
        $connection = SocialConnection::where('empresa_id', $empresaId)->first();

        if (!$connection) {
            return back()->with('error', 'Você precisa conectar suas redes sociais primeiro.');
        }

        // Monta URL pública do artigo
        $site = $artigo->site;
        $base = $site?->dominio_personalizado
            ? 'https://' . $site->dominio_personalizado
            : config('app.url') . '/site/' . ($site?->slug ?? '');

        $urlArtigo = $base . '/blog/' . $artigo->slug;
        $texto     = $this->meta->montarTextoArtigo($artigo->titulo, $artigo->resumo ?? '', $urlArtigo);

        $resultados = [];
        $erros      = [];

        foreach ($request->redes as $rede) {
            try {
                if ($rede === 'facebook') {
                    $res = $this->meta->postToFacebook($connection, $texto, $urlArtigo);
                    $resultados[] = "Facebook ✅ (post #{$res['post_id']})";
                }

                if ($rede === 'instagram') {
                    // Instagram precisa de imagem — usa imagem do artigo ou OG do site
                    $imageUrl = $artigo->imagem_url
                        ?? ($site?->og_image_url ? url('storage/' . $site->og_image_url) : null);

                    if (!$imageUrl) {
                        $erros[] = 'Instagram: artigo sem imagem. Adicione uma imagem ao artigo.';
                        continue;
                    }

                    $res = $this->meta->postToInstagram($connection, $texto, $imageUrl);
                    $resultados[] = "Instagram ✅ (post #{$res['post_id']})";
                }
            } catch (\Exception $e) {
                $erros[] = ucfirst($rede) . ': ' . $e->getMessage();
                Log::error("SocialMeta: erro ao postar em {$rede}", ['erro' => $e->getMessage(), 'artigo_id' => $artigo->id]);
            }
        }

        $msg = implode(' | ', $resultados);
        if ($erros) {
            return back()
                ->with('success', $msg ?: null)
                ->with('error', implode("\n", $erros));
        }

        return back()->with('success', 'Publicado: ' . $msg);
    }

    /* ─────────────────────────────────────────────────────────
     |  Resolve empresa do usuário logado
     ──────────────────────────────────────────────────────── */
    private function resolverEmpresaId(): int
    {
        $user = auth()->user();

        if ($user->empresa_id) return $user->empresa_id;
        if ($user->professor?->empresa_id) return $user->professor->empresa_id;

        $empresa = \App\Models\Empresa::where('user_id', $user->id)->first();
        if ($empresa) return $empresa->id;

        abort(403, 'Empresa não encontrada.');
    }
}
