<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotLog;
use App\Models\BotService;
use App\Models\Conversation;
use App\Models\Servicos;
use App\Models\TokenUsage;
use App\Services\DeepSeekService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BotController extends Controller
{

    protected $deepSeekService;

    public function __construct(DeepSeekService $deepSeekService)
    {
        $this->deepSeekService = $deepSeekService;
    }
    
   public function dashboard()
    {
        // Bots ativos
        $botsAtivos = Bot::where('status', true)->count();

        // Tokens consumidos
        $totalTokens = TokenUsage::sum('tokens_usados');

        // Custo estimado (exemplo: R$ 0,002 por 1k tokens)
        $custoEstimado = ($totalTokens / 1000) * 0.002;

            // Conversas de hoje
        $conversasHoje = Conversation::whereDate('created_at', today())->count();

        // Serviços cadastrados
        $services = BotService::all();

        // Consumo nos últimos 7 dias
        $labels = [];
        $dataTokens = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $labels[] = now()->subDays($i)->format('d/m');
            $dataTokens[] = TokenUsage::whereDate('created_at', $day)->sum('tokens_usados');
        }

        return view('admin.bot.dashboard', compact(
            'botsAtivos',
            'totalTokens',
            'custoEstimado',
            'conversasHoje',
            'services',
            'labels',
            'dataTokens'
        ));
    }


    public function index() {
        $bots = Bot::all();
        return view('admin.bot.index', compact('bots'));
    }

    public function create() {
        $services = Servicos::all(); // exemplo: professores, horários
        return view('admin.bot.create', compact('services'));
    }

    public function store(Request $request) {
        $bot = Bot::create($request->all());
        return redirect()->route('admin.bot.index')->with('success', 'Bot criado com sucesso!');
    }

        public function tokens() {
            $usage = TokenUsage::with('bot')->get();
            return view('admin.bot.tokens', compact('usage'));
        }

        public function logs() {
            $logs = BotLog::with('bot')->get();
            return view('admin.bot.logs', compact('logs'));
        }

    // Mostrar formulário de edição
    public function edit($id)
    {
        $bot = Bot::with('services')->findOrFail($id);
        $services = Servicos::all();
        return view('admin.bot.edit', compact('bot', 'services'));
    }

    // Atualizar bot
    public function update(Request $request, $id)
    {
        $bot = Bot::findOrFail($id);
        $bot->update($request->only(['nome','segmento','tom','token_deepseek','status']));

        // Atualizar serviços
        if ($request->has('services')) {
            $bot->services()->sync($request->services); // many-to-many se tiver pivot, ou ajustar se for hasMany
        }

        return redirect()->route('admin.bot.index')->with('success', 'Bot atualizado com sucesso!');
    }

    // Excluir bot
    public function destroy(Request $request)
    {
        $bot = Bot::findOrFail($request->id);
        $bot->delete();
        return redirect()->route('admin.bot.index')->with('success', 'Bot excluído com sucesso!');
    }


    private function getDeepSeekResponse(string $question): string
    {
        // Força o modelo a responder em português
        $prompt = "Responda em português: " . $question;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('DEEP_SEEK_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/v1/chat/completions', [
            'model' => 'deepseek-chat',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 10
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'] ?? 'Sem resposta';
        } else {
            return 'Erro: ' . $response->body();
        }
    }


    public function interact(Request $request, $botId)
    {
        $bot = Bot::findOrFail($botId);
        $question = $request->input('question');
        $empresa_id = $request->empresa_id;
    
        if (!$bot->status) {
            return response()->json(['error' => 'Bot está inativo'], 403);
        }

        $response = $this->deepSeekService->getDeepSeekResponse($bot, $question, $empresa_id);

        return response()->json(['response' => $response]);
    }

}

