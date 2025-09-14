<?php

namespace App\Http\Controllers;

use App\Models\BotService;
use App\Models\Bot;
use App\Models\Servicos;
use Illuminate\Http\Request;

class BotServiceController extends Controller
{
    // Lista todos os serviços dos bots
   public function index()
{
    $request = request();
    
    $query = BotService::with(['bot', 'servico']);

    // Filtro por bot
    if ($request->filled('bot_id')) {
        $query->where('bot_id', $request->bot_id);
    }

    // Filtro por serviço
    if ($request->filled('servico_id')) {
        $query->where('servico_id', $request->servico_id);
    }

    $botServices = $query->get();

    $bots = Bot::where('status', true)->get();
    $servicos = Servicos::all();

    return view('admin.botservice.index', compact('botServices', 'bots', 'servicos'));
}


    // Mostra o formulário para criar um novo serviço para o bot
    public function create()
    {
        $bots = Bot::where('status', true)->get(); // Apenas bots ativos
        $servicos = Servicos::all(); // Todos os serviços disponíveis
        return view('admin.botservice.create', compact('bots', 'servicos'));
    }

    // Armazena o novo serviço do bot
    public function store(Request $request)
    {
        $request->validate([
            'bot_id' => 'required|exists:bots,id',
            'servico_id' => 'required|exists:servicos,id',
            'nome_servico' => 'required|string|max:255',
            'professor' => 'nullable|string|max:255',
            'horario' => 'nullable|string|max:100',
            'valor' => 'required|numeric|min:0',
        ]);

        BotService::create($request->all());

        return redirect()->route('admin.botservice.index')
            ->with('success', 'Serviço do bot cadastrado com sucesso!');
    }

    // Mostra o formulário para editar um serviço do bot
    public function edit($id)
    {
      $botService = BotService::with(['bot', 'servico'])->findOrFail($id);
        $bots = Bot::where('status', true)->get();
        $servicos = Servicos::all();

        return view('admin.botservice.edit', compact('botService', 'bots', 'servicos'));
    }

    // Atualiza o serviço do bot
    public function update(Request $request, $id)
    {
        $botService = BotService::findOrFail($id);

        $request->validate([
            'bot_id' => 'required|exists:bots,id',
            'servico_id' => 'required|exists:servicos,id',
            'nome_servico' => 'required|string|max:255',
            'professor' => 'nullable|string|max:255',
            'horario' => 'nullable|string|max:100',
            'valor' => 'required|numeric|min:0',
        ]);

        $botService->update($request->all());

        return redirect()->route('admin.botservice.index')
            ->with('success', 'Serviço do bot atualizado com sucesso!');
    }

    // Remove o serviço do bot
    public function destroy($id)
    {
        $botService = BotService::findOrFail($id);
        $botService->delete();

        return redirect()->route('admin.botservice.index')
            ->with('success', 'Serviço do bot removido com sucesso!');
    }
}
