<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDespesaRequest;
use App\Http\Requests\UpdateDespesaRequest;
use App\Models\DespesaCategoria;
use App\Models\Despesas;
use App\Models\FinanceiroCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Para usuário logado
use Illuminate\Support\Facades\DB;

class DespesaController extends Controller
{
    // Listar todas as despesas (filtrado por usuário/empresa logado, com paginação)
   public function index()
    {
        $request = request();
        $user = Auth::user();
        
        // Query base
        $query = Despesas::where('usuario_id', $user->id)
                         ->when($user->empresa_id, fn($q) => $q->where('empresa_id', $user->empresa_id))
                         ->with(['categoria', 'empresa', 'usuario']);

        // Aplicar filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('data_inicial')) {
            $query->whereDate('data_vencimento', '>=', $request->data_inicial);
        }

        if ($request->filled('data_final')) {
            $query->whereDate('data_vencimento', '<=', $request->data_final);
        }

        if ($request->filled('search')) {
            $query->where('descricao', 'like', '%' . $request->search . '%');
        }

        // Buscar despesas com paginação
        $despesas = $query->orderBy('data_vencimento', 'desc')->paginate(15);

        // Manter parâmetros de filtro na paginação
        $despesas->appends($request->query());

        // Calcular resumo (sem filtros para comparação)
        $resumoQuery = Despesas::where('usuario_id', $user->id)
                              ->when($user->empresa_id, fn($q) => $q->where('empresa_id', $user->empresa_id));

        $resumo = [
            'total_geral' => $resumoQuery->sum(DB::raw('CAST(valor AS DECIMAL(10,2))')),
            'total_pago' => $resumoQuery->where('status', 'PAID')->sum(DB::raw('CAST(valor AS DECIMAL(10,2))')),
            'total_pendente' => $resumoQuery->where('status', 'PENDING')->sum(DB::raw('CAST(valor AS DECIMAL(10,2))')),
            'count_pago' => $resumoQuery->where('status', 'PAID')->count(),
            'count_pendente' => $resumoQuery->where('status', 'PENDING')->count(),
        ];

        // Buscar categorias para o filtro
        
        $categorias = DespesaCategoria::orderBy('nome')->get();

        return view('admin.financeiro.despesas.index', compact('despesas', 'resumo', 'categorias'));
    }


     public function resumo(Request $request)
    {
        $user = Auth::user();
        
        $query = Despesas::where('usuario_id', $user->id)
                         ->when($user->empresa_id, fn($q) => $q->where('empresa_id', $user->empresa_id));

        // Aplicar os mesmos filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('data_inicial')) {
            $query->whereDate('data_vencimento', '>=', $request->data_inicial);
        }

        if ($request->filled('data_final')) {
            $query->whereDate('data_vencimento', '<=', $request->data_final);
        }

        if ($request->filled('search')) {
            $query->where('descricao', 'like', '%' . $request->search . '%');
        }

      $filteredQuery = clone $query;

        $resumoFiltrado = [
            'total_filtrado' => (clone $filteredQuery)->sum(DB::raw('CAST(valor AS DECIMAL(10,2))')),
            'total_pago_filtrado' => (clone $filteredQuery)->where('status', 'PAID')->sum(DB::raw('CAST(valor AS DECIMAL(10,2))')),
            'total_pendente_filtrado' => (clone $filteredQuery)->where('status', 'PENDING')->sum(DB::raw('CAST(valor AS DECIMAL(10,2))')),
            'count_filtrado' => $filteredQuery->count(),
        ];

        return response()->json($resumoFiltrado);
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