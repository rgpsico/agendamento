<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDespesaRequest;
use App\Http\Requests\UpdateDespesaRequest;
use App\Models\Despesas;
use App\Models\FinanceiroCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Para usuário logado

class DespesaController extends Controller
{
    // Listar todas as despesas (filtrado por usuário/empresa logado, com paginação)
  public function index()
{
    $request = request();
    $categorias = FinanceiroCategoria::where('tipo', 'despesa')->get();
    $user = Auth::user();

    // Query base para despesas
    $query = Despesas::where('usuario_id', $user->id)
        ->when($user->empresa_id, fn($q) => $q->where('empresa_id', $user->empresa_id))
        // Filtros
        ->when($request->filled('categoria_id'), fn($q) => 
            $q->where('categoria_id', $request->categoria_id)
        )
        ->when($request->filled('status'), fn($q) => 
            $q->where('status', $request->status)
        )
        ->when($request->filled('data_inicio') && $request->filled('data_fim'), fn($q) => 
            $q->whereBetween('data_vencimento', [$request->data_inicio, $request->data_fim])
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
   
    $query = Despesas::where('usuario_id', $user->id)
        ->when($user->empresa_id, fn($q) => $q->where('empresa_id', $user->empresa_id))
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

    $query = Despesas::where('usuario_id', $user->id)
        ->when($user->empresa_id, fn($q) => $q->where('empresa_id', $user->empresa_id));

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
           $categorias = FinanceiroCategoria::where('tipo', 'despesa')->get();
        $user = Auth::user();
        $defaultData = [
            'empresa_id' => $user->empresa_id ?? null,  // Assuma que Usuario tem empresa_id
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
        $data['empresa_id'] ??= $user->empresa_id ?? null;  // Define se não passado
        $data['usuario_id'] ??= $user->id;  // Sempre define o usuário logado
        
        Despesas::create($data);

        return redirect()->route('financeiro.despesas.index')
            ->with('success', 'Despesa cadastrada com sucesso!');
    }

    // Mostrar formulário de edição
    public function edit($id)
    {
       $categorias = FinanceiroCategoria::where('tipo', 'despesa')->get();
        $despesa = Despesas::findOrFail($id);
    
        // $this->authorize('update', $despesa);  // Policy verifica se é do usuário
        return view('admin.financeiro.despesas.edit', compact('despesa','categorias'));
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