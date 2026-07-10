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
    public function connect()
    {
        $redirectUri = route('admin.social.callback');
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
        $redirectUri = route('admin.social.callback');

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

            // Pega a primeira página (ou deixa o usuário escolher no futuro)
            $page         = $pages[0];
            $pageId       = $page['id'];
            $pageName     = $page['name'];
            $pageToken    = $page['access_token'];
            $igAccountId  = $page['instagram_business_account']['id'] ?? null;
            $igUsername   = '';

            if ($igAccountId) {
                $igUsername = $this->meta->getInstagramUsername($igAccountId, $pageToken);
            }

            // Salva ou atualiza
            SocialConnection::updateOrCreate(
                ['empresa_id' => $empresaId],
                [
                    'facebook_page_id'    => $pageId,
                    'facebook_page_name'  => $pageName,
                    'facebook_page_token' => $pageToken,
                    'instagram_account_id' => $igAccountId,
                    'instagram_username'  => $igUsername,
                    'user_access_token'   => $userToken,
                    'token_expires_at'    => $expiresAt,
                ]
            );

            Log::info('SocialMeta: conexão salva', ['empresa_id' => $empresaId, 'page' => $pageName]);

            return redirect()->route('admin.social.index')
                ->with('success', "Conectado com sucesso! Página: {$pageName}" . ($igUsername ? " | Instagram: @{$igUsername}" : ''));

        } catch (\Exception $e) {
            Log::error('SocialMeta: erro no callback', ['erro' => $e->getMessage()]);
            return redirect()->route('admin.social.index')
                ->with('error', 'Erro ao conectar: ' . $e->getMessage());
        }
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
