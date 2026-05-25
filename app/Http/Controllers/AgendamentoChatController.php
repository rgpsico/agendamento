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
        return view('admin.agendamentos.chat', [
            'pageTitle' => 'Chat IA · Agendamentos',
        ]);
    }

    // ── Endpoint principal do chat ─────────────────────────────────────────
    public function query(Request $request)
    {
        $request->validate([
            'mensagem'        => 'required|string|max:2000',
            'conversation_id' => 'nullable|integer',
        ]);

        $user       = Auth::user();
        $empresaId  = $this->resolverEmpresaId($user);
        $mensagem   = $request->input('mensagem');

        // ── Resolve / cria bot da empresa ──────────────────────────────────
        $bot = Bot::where('empresa_id', $empresaId)->where('status', true)->first();

        if (!$bot) {
            // Se não tem bot ativo, responde só com insights (sem IA)
            return response()->json([
                'reply'    => 'Nenhum bot ativo encontrado para sua empresa. Cadastre um bot em **Admin → Bot** para habilitar o assistente de IA.',
                'insights' => $this->buildInsights($empresaId, $user),
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
            'from'    => 'user',
            'to'      => 'bot',
            'user_id' => $user->id,
            'body'    => $mensagem,
            'tipo'    => 'user',
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
        if ($user->professor) return $user->professor->empresa_id;
        if ($user->empresa)   return $user->empresa->id;
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
