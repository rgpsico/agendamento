<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Bot;
use App\Models\Conversation;
use App\Services\DeepSeekService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgendamentoChatController extends Controller
{
    protected DeepSeekService $deepSeek;

    public function __construct(DeepSeekService $deepSeek)
    {
        $this->deepSeek = $deepSeek;
    }

    public function index()
    {
        $user      = Auth::user();
        $empresaId = $this->resolverEmpresaId($user);
        $empresa   = $empresaId ? \App\Models\Empresa::find($empresaId) : null;

        // Auto-cria bot padrão se a empresa não tiver nenhum
        if ($empresaId && Bot::where('empresa_id', $empresaId)->doesntExist()) {
            Bot::create([
                'empresa_id'     => $empresaId,
                'nome'           => 'Assistente IA',
                'segmento'       => 'agendamentos',
                'tom'            => 'profissional',
                'status'         => true,
                'token_deepseek' => 2000,
                'prompt'         => 'Você é um assistente inteligente para gerenciamento de agendamentos da empresa ' . ($empresa->nome ?? 'da empresa') . '. Ajude com agendamentos, alunos, serviços e consultas ao sistema.',
            ]);
        }

        $bots = $empresaId
            ? Bot::where('empresa_id', $empresaId)->orderBy('nome')->get(['id','nome','status'])
            : collect();

        return view('admin.agendamentos.chat', [
            'pageTitle'  => 'Chat IA · Agendamentos',
            'usuario'    => $user,
            'empresa'    => $empresa,
            'bots'       => $bots,
            'empresaId'  => $empresaId,
        ]);
    }

    // ── Endpoint principal do chat ─────────────────────────────────────────
    public function query(Request $request)
    {
        $request->validate([
            'mensagem'        => 'required|string|max:2000',
            'conversation_id' => 'nullable|integer',
            'bot_id'          => 'nullable|integer',
        ]);

        $user      = Auth::user();
        $empresaId = $this->resolverEmpresaId($user);
        $mensagem  = $request->input('mensagem');

        if (!$empresaId) {
            return response()->json([
                'reply'    => '⚠️ Sua conta não está vinculada a nenhuma empresa. Verifique seu perfil ou entre em contato com o administrador.',
                'insights' => [],
            ]);
        }

        // ── Resolve bot: pelo ID escolhido, primeiro ativo, qualquer bot, ou cria default ──
        $bot = $request->filled('bot_id')
            ? Bot::where('id', $request->bot_id)->where('empresa_id', $empresaId)->first()
            : Bot::where('empresa_id', $empresaId)->where('status', true)->first()
              ?? Bot::where('empresa_id', $empresaId)->first();

        // Se ainda não há bot, cria um padrão automaticamente
        if (!$bot) {
            $empresa = \App\Models\Empresa::find($empresaId);
            $bot = Bot::create([
                'empresa_id'     => $empresaId,
                'nome'           => 'Assistente IA',
                'segmento'       => 'agendamentos',
                'tom'            => 'profissional',
                'status'         => true,
                'token_deepseek' => 2000,
                'prompt'         => 'Você é um assistente inteligente para gerenciamento de agendamentos da empresa ' . ($empresa->nome ?? 'da empresa') . '. Ajude com agendamentos, alunos, serviços e consultas ao sistema.',
            ]);
        }

        // ── Resolve / cria conversa ────────────────────────────────────────
        $conversation = $request->filled('conversation_id')
            ? Conversation::find($request->input('conversation_id'))
            : null;

        if (!$conversation) {
            $conversation = Conversation::createWithBot($bot, null, $user->id, $empresaId);
        }

        // ── Salva mensagem do usuário ──────────────────────────────────────
        $conversation->messages()->create([
            'from' => 'user',
            'to'   => 'bot',
            'role' => 'user',
            'body' => $mensagem,
        ]);

        // ── Chama DeepSeek com function calling completo ───────────────────
        try {
            $result = $this->deepSeek->getDeepSeekResponseWithPrompt(
                $bot,
                $mensagem,
                $conversation,
                $empresaId
            );

            $reply = $result['reply'];
            $debug = $result['debug'] ?? [];

            // Salva resposta do bot
            $conversation->messages()->create([
                'from' => 'bot',
                'to'   => 'user',
                'role' => 'assistant',
                'body' => $reply,
            ]);

            return response()->json([
                'conversation_id' => $conversation->id,
                'reply'           => $reply,
                'debug'           => $debug,
                'insights'        => $this->buildInsights($empresaId, $user),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'reply'   => '⚠️ Erro ao processar: ' . $e->getMessage(),
                'insights'=> $this->buildInsights($empresaId, $user),
            ], 500);
        }
    }

    // ── Debug: mostra o que o sistema enxerga do usuário logado ───────────
    public function debug()
    {
        $user      = Auth::user();
        $empresaId = $this->resolverEmpresaId($user);
        $bots      = Bot::where('empresa_id', $empresaId)->get(['id','nome','status','empresa_id']);
        $botAtivo  = Bot::where('empresa_id', $empresaId)->where('status', true)->first();

        return response()->json([
            'usuario_id'   => $user->id,
            'usuario_nome' => $user->name ?? $user->nome,
            'empresa_id_resolvido' => $empresaId,
            'empresa_relation'  => $user->empresa ? ['id' => $user->empresa->id] : null,
            'professor_relation' => $user->professor ? ['id' => $user->professor->id, 'empresa_id' => $user->professor->empresa_id] : null,
            'todos_os_bots_da_empresa' => $bots,
            'bot_ativo_encontrado'     => $botAtivo ? ['id'=>$botAtivo->id,'nome'=>$botAtivo->nome] : null,
        ]);
    }

    // ── Endpoint separado só para carregar insights ────────────────────────
    public function insights()
    {
        $user      = Auth::user();
        $empresaId = $this->resolverEmpresaId($user);

        return response()->json([
            'insights' => $this->buildInsights($empresaId, $user),
        ]);
    }

    // ── Helpers ────────────────────────────────────────────────────────────
    private function resolverEmpresaId($user): ?int
    {
        // Dono/admin da empresa
        if ($user->empresa) return $user->empresa->id;

        // Professor vinculado a uma empresa
        if ($user->professor && $user->professor->empresa_id)
            return $user->professor->empresa_id;

        // Fallback: busca qualquer empresa onde este usuário é dono
        $empresa = \App\Models\Empresa::where('user_id', $user->id)->first();
        if ($empresa) return $empresa->id;

        return null;
    }

    private function buildInsights(?int $empresaId, $user): array
    {
        $base = Agendamento::query();

        if ($user->professor) {
            $base->where('professor_id', $user->professor->id);
        } elseif ($empresaId) {
            $base->whereHas('professor', fn($p) => $p->where('empresa_id', $empresaId));
        }

        $mesAtual = (clone $base)
            ->whereMonth('data_da_aula', now()->month)
            ->whereYear('data_da_aula', now()->year);

        $totalMes        = (clone $mesAtual)->count();
        $totalHoje       = (clone $base)->whereDate('data_da_aula', today())->count();
        $cancelamentos   = (clone $mesAtual)->where('status', 'cancelado')->count();
        $receitaPrevista = (clone $mesAtual)->sum('valor_aula');
        $clientesNovos   = (clone $mesAtual)->distinct('aluno_id')->count('aluno_id');

        $horarios = (clone $mesAtual)
            ->select('horario', DB::raw('count(*) as total'))
            ->groupBy('horario')
            ->orderByDesc('total')
            ->limit(3)
            ->pluck('total', 'horario')
            ->toArray();

        return [
            'agendamentos_mes'  => $totalMes,
            'agendamentos_hoje' => $totalHoje,
            'cancelamentos'     => $cancelamentos,
            'clientes_novos'    => $clientesNovos,
            'receita_prevista'  => 'R$ ' . number_format($receitaPrevista, 2, ',', '.'),
            'horarios_top'      => $horarios,
        ];
    }
}
