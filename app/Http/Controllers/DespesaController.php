<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDespesaRequest;
use App\Http\Requests\UpdateDespesaRequest;
use App\Models\Despesas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Para usuário logado

class DespesaController extends Controller
{
    // Listar todas as despesas (filtrado por usuário/empresa logado, com paginação)
    public function index()
    {
        $user = Auth::user();
        $despesas = Despesas::where('usuario_id', $user->id)  // Filtra pelo usuário logado
                          ->when($user->empresa_id, fn($q) => $q->where('empresa_id', $user->empresa_id))  // Se usuário tem empresa
                          ->orderBy('data_vencimento', 'desc')
                          ->paginate(15);

        return view('admin.financeiro.despesas.index', compact('despesas'));
    }

    // Mostrar formulário de criação (pré-preenche empresa_id e usuario_id)
    public function create()
    {
        $user = Auth::user();
        $defaultData = [
            'empresa_id' => $user->empresa_id ?? null,  // Assuma que Usuario tem empresa_id
            'usuario_id' => $user->id,
            'status' => 'PENDING',
        ];

        return view('admin.financeiro.despesas.create', compact('defaultData'));
    }

    // Salvar nova despesa
    public function store(StoreDespesaRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $data['empresa_id'] ??= $user->empresa_id ?? null;  // Define se não passado
        $data['usuario_id'] ??= $user->id;  // Sempre define o usuário logado

        Despesas::create($data);

        return redirect()->route('financeiro.despesas.index')
            ->with('success', 'Despesa cadastrada com sucesso!');
    }

    // Mostrar formulário de edição
    public function edit($id)
    {
    
        $despesa = Despesas::findOrFail($id);
    
        // $this->authorize('update', $despesa);  // Policy verifica se é do usuário
        return view('admin.financeiro.despesas.edit', compact('despesa'));
    }

    // Atualizar despesa existente
    public function update(UpdateDespesaRequest $request, $id)
    {
        $despesa = Despesas::findOrFail($id);
        // $this->authorize('update', $despesa);
        $despesa->update($request->validated());  // Não altera usuario_id/empresa_id aqui

        return redirect()->route('financeiro.despesas.index')
            ->with('success', 'Despesa atualizada com sucesso!');
    }

    // Deletar despesa
    public function destroy($id)
    {
        $despesa = Despesas::findOrFail($id);
        // $this->authorize('delete', $despesa);
        $despesa->delete();

        return redirect()->route('financeiro.despesas.index')
            ->with('success', 'Despesa excluída com sucesso!');
    }

    // Mostrar detalhes da despesa
    public function show($id)
    {
        $despesa = Despesas::findOrFail($id);
        $this->authorize('view', $despesa);
        return view('admin.financeiro.despesas.show', compact('despesa'));
    }
}