<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotService;
use App\Models\Conversation;
use App\Models\Servicos;
use App\Models\TokenUsage;
use Illuminate\Http\Request;
class BotController extends Controller
{
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
}

