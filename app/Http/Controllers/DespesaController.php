<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDespesaRequest;
use App\Http\Requests\UpdateDespesaRequest;
use App\Models\Despesas;
use App\Models\DespesaCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Para usuário logado

class DespesaController extends Controller
{
    // Listar todas as despesas (filtrado por usuário/empresa logado, com paginação)
  public function index()
{
    $request = request();
    $categorias = DespesaCategoria::orderByRaw('empresa_id IS NULL')->orderBy('nome')->get();
    $user = Auth::user();

    // Query base para despesas
    $query = Despesas::query() // a empresa logada é aplicada pelo model (DaEmpresaLogada)
        // Filtros
        ->when($request->filled('categoria_id'), fn($q) => 
            $q->where('categoria_id', $request->categoria_id)
        )
        ->when($request->filled('status'), fn($q) => 
            $q->where('status', $request->status)
        )
        ->when($request->filled('data_inicio') && $request->filled('data_fim'), fn($q) => 
            $q->noPeriodo($request->data_inicio, $request->data_fim) // vencimento; sem vencimento, lançamento
        );

    // Total das despesas filtradas (soma no banco)
    $totalDespesas = $query->sum('valor');

    // Paginação
    $despesas = $query->orderBy('data_vencimento', 'desc')
                      ->paginate(15)
                      ->withQueryString(); // mantém filtros na paginação

    return view('admin.financeiro.despesas.index', compact('despesas', 'totalDespesas', 'categorias'));
}




    public function apiIndex(Request $request)
{
    $user = Auth::user();
   
    $query = Despesas::query() // a empresa logada é aplicada pelo model (DaEmpresaLogada)
        ->with(['categoria']);

    // filtros
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('categoria')) {
        $query->where('categoria_id', $request->categoria);
    }
    if ($request->filled('data_inicio')) {
        $query->whereDate('data_vencimento', '>=', $request->data_inicio);
    }
    if ($request->filled('data_fim')) {
        $query->whereDate('data_vencimento', '<=', $request->data_fim);
    }

    $despesas = $query->orderBy('data_vencimento', 'desc')->get();

    return response()->json([
        'data' => $despesas
     
    ]);
}

public function apiResumo(Request $request)
{
    $user = Auth::user();

    $query = Despesas::query(); // a empresa logada é aplicada pelo model (DaEmpresaLogada)

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('categoria')) {
        $query->where('categoria_id', $request->categoria);
    }
    if ($request->filled('data_inicio')) {
        $query->whereDate('data_vencimento', '>=', $request->data_inicio);
    }
    if ($request->filled('data_fim')) {
        $query->whereDate('data_vencimento', '<=', $request->data_fim);
    }

    return response()->json([
        'total_filtrado' => $query->sum('valor')
    ]);
}


    // Mostrar formulário de criação (pré-preenche empresa_id e usuario_id)
    public function create()
    {
           $categorias = DespesaCategoria::orderByRaw('empresa_id IS NULL')->orderBy('nome')->get();
        $user = Auth::user();
        $defaultData = [
            'empresa_id' => $user->empresa?->id,
            'usuario_id' => $user->id,
            'status' => 'PENDING',
        ];

        return view('admin.financeiro.despesas.create', compact('defaultData','categorias'));
    }

    // Salvar nova despesa
    public function store(StoreDespesaRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        // empresa_id: definido pelo model (DaEmpresaLogada) com a empresa do usuário logado
        $data['usuario_id'] ??= $user->id;  // Sempre define o usuário logado
        
        abort_unless(DespesaCategoria::whereKey($data['categoria_id'])->exists(), 422, 'Categoria inválida.');
        $data['data_pagamento'] = $this->dataPagamento($data['status'], $data['data_pagamento'] ?? null);

        Despesas::create($data);

        return redirect()->route('financeiro.despesas.index')
            ->with('success', 'Despesa cadastrada com sucesso!');
    }

    // Mostrar formulário de edição
    public function edit($id)
    {
       $categorias = DespesaCategoria::orderByRaw('empresa_id IS NULL')->orderBy('nome')->get();
        $despesa = Despesas::findOrFail($id);
    
        // $this->authorize('update', $despesa);  // Policy verifica se é do usuário
        return view('admin.financeiro.despesas.edit', compact('despesa','categorias'));
    }

    // Atualizar despesa existente
    public function update(UpdateDespesaRequest $request, $id)
    {
        $despesa = Despesas::findOrFail($id);
        // $this->authorize('update', $despesa);
        $dados = $request->validated();  // Não altera usuario_id/empresa_id aqui
        if (isset($dados['categoria_id'])) {
            abort_unless(DespesaCategoria::whereKey($dados['categoria_id'])->exists(), 422, 'Categoria inválida.');
        }
        if (isset($dados['status'])) {
            $dados['data_pagamento'] = $this->dataPagamento($dados['status'], $dados['data_pagamento'] ?? $despesa->data_pagamento);
        }
        $despesa->update($dados);

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
        // a empresa logada já é garantida pelo model (outra empresa = 404)
        return view('admin.financeiro.despesas.show', compact('despesa'));
    }

    /** Paga: a data informada ou hoje; pendente: sem data de pagamento. */
    private function dataPagamento(?string $status, $informada)
    {
        if (! in_array(strtoupper((string) $status), Despesas::PAGA, true)) {
            return null;
        }

        return $informada ?: now()->toDateString();
    }
}
