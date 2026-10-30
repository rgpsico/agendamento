<?php

namespace App\Http\Controllers;

use App\Models\BotService;
use App\Models\Bot;
use App\Models\Servicos;
use Illuminate\Http\Request;

class BotServiceController extends Controller
{
    private function empresaId(): int
    {
        $id = auth()->user()?->empresa?->id;
        abort_unless($id, 403, 'Empresa não identificada.');

        return (int) $id;
    }

    /** Vínculo bot↔serviço que pertence à empresa logada; de outra empresa vira 404. */
    private function meuVinculo($id): BotService
    {
        return BotService::with(['bot', 'servico'])
            ->whereHas('bot', fn ($q) => $q->daEmpresa($this->empresaId()))
            ->findOrFail($id);
    }

    /** Valida o formulário garantindo que bot e serviço são da empresa logada. */
    private function validar(Request $request): array
    {
        $empresaId = $this->empresaId();

        return $request->validate([
            'bot_id' => ['required', \Illuminate\Validation\Rule::exists('bots', 'id')->where('empresa_id', $empresaId)],
            'servico_id' => ['required', \Illuminate\Validation\Rule::exists('servicos', 'id')->where('empresa_id', $empresaId)],
            'nome_servico' => 'required|string|max:255',
            'professor' => 'nullable|string|max:255',
            'horario' => 'nullable|string|max:100',
            'valor' => 'required|numeric|min:0',
        ]);
    }

    // Lista todos os serviços dos bots
   public function index()
{
    $request = request();
    
    $query = BotService::with(['bot', 'servico'])
        ->whereHas('bot', fn ($q) => $q->daEmpresa($this->empresaId()));

    // Filtro por bot
    if ($request->filled('bot_id')) {
        $query->where('bot_id', $request->bot_id);
    }

    // Filtro por serviço
    if ($request->filled('servico_id')) {
        $query->where('servico_id', $request->servico_id);
    }

    $botServices = $query->get();

    $bots = Bot::daEmpresa($this->empresaId())->where('status', true)->get();
    $servicos = Servicos::where('empresa_id', $this->empresaId())->get();

    return view('admin.botservice.index', compact('botServices', 'bots', 'servicos'));
}


    // Mostra o formulário para criar um novo serviço para o bot
    public function create()
    {
        $bots = Bot::daEmpresa($this->empresaId())->where('status', true)->get(); // Apenas bots ativos da empresa
        $servicos = Servicos::where('empresa_id', $this->empresaId())->get();
        return view('admin.botservice.create', compact('bots', 'servicos'));
    }

    // Armazena o novo serviço do bot
    public function store(Request $request)
    {
        $dados = $this->validar($request);

        BotService::create($dados);

        return redirect()->route('admin.botservice.index')
            ->with('success', 'Serviço do bot cadastrado com sucesso!');
    }

    // Mostra o formulário para editar um serviço do bot
    public function edit($id)
    {
        $botService = $this->meuVinculo($id);
        $bots = Bot::daEmpresa($this->empresaId())->where('status', true)->get();
        $servicos = Servicos::where('empresa_id', $this->empresaId())->get();

        return view('admin.botservice.edit', compact('botService', 'bots', 'servicos'));
    }

    // Atualiza o serviço do bot
    public function update(Request $request, $id)
    {
        $botService = $this->meuVinculo($id);
        $dados = $this->validar($request);

        $botService->update($dados);

        return redirect()->route('admin.botservice.index')
            ->with('success', 'Serviço do bot atualizado com sucesso!');
    }

    // Remove o serviço do bot
    public function destroy($id)
    {
        $botService = $this->meuVinculo($id);
        $botService->delete();

        return redirect()->route('admin.botservice.index')
            ->with('success', 'Serviço do bot removido com sucesso!');
    }
}
