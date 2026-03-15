<?php

namespace App\Http\Controllers;

use App\Models\DespesaRecorrente;
use App\Models\DespesaCategoria;
use Illuminate\Http\Request;

class DespesasRecorrenteController extends Controller
{
    /**
     * Listar todas as despesas recorrentes.
     */
    public function index()
    {
        $categorias = DespesaCategoria::all();
        $query = DespesaRecorrente::query()->with('categoria', 'usuario', 'empresa');

        if ($descricao = request('descricao')) {
            $query->where('descricao', 'like', "%{$descricao}%");
        }

        if ($categoria = request('categoria_id')) {
            $query->where('categoria_id', $categoria);
        }

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        if ($frequencia = request('frequencia')) {
            $query->where('frequencia', $frequencia);
        }

        if ($data_inicio = request('data_inicio')) {
            $query->whereDate('data_inicio', '>=', $data_inicio);
        }

        if ($data_fim = request('data_fim')) {
            $query->whereDate('data_fim', '<=', $data_fim);
        }

        $despesas = $query->get();

        // Total filtrado
        $totalDespesas = $despesas->sum('valor');

        return view('admin.financeiro.despesas_recorrentes.index', compact('despesas', 'totalDespesas', 'categorias'));
    }

    /**
     * Mostrar formulário de criação.
     */
    public function create()
    {
        $categorias = DespesaCategoria::all();
        return view('admin.financeiro.despesas_recorrentes.create', compact('categorias'));
    }

    /**
     * Salvar nova despesa recorrente.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'categoria_id' => 'required|exists:despesas_categorias,id',
            'frequencia' => 'required|in:DIARIA,SEMANAL,MENSAL,ANUAL',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
        ]);

        DespesaRecorrente::create([
            'descricao' => $request->descricao,
            'valor' => $request->valor,
            'categoria_id' => $request->categoria_id,
            'status' => 'ACTIVE',
            'frequencia' => $request->frequencia,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
            'empresa_id' => auth()->user()->empresa->id ?? null,
            'usuario_id' => auth()->id(),
        ]);

        return redirect()->route('financeiro.despesas_recorrentes.index')
                         ->with('success', 'Despesa recorrente cadastrada com sucesso!');
    }

    /**
     * Mostrar formulário de edição.
     */
    public function edit(DespesaRecorrente $despesaRecorrente)
    {
        $categorias = DespesaCategoria::all();
        return view('admin.financeiro.despesas_recorrentes.edit', compact('despesaRecorrente', 'categorias'));
    }

    /**
     * Atualizar despesa recorrente.
     */
    public function update(Request $request, DespesaRecorrente $despesaRecorrente)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'categoria_id' => 'required|exists:despesas_categorias,id',
            'frequencia' => 'required|in:DIARIA,SEMANAL,MENSAL,ANUAL',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ]);

        $despesaRecorrente->update($request->only([
            'descricao',
            'valor',
            'categoria_id',
            'frequencia',
            'data_inicio',
            'data_fim',
            'status'
        ]));

        return redirect()->route('financeiro.despesas_recorrentes.index')
                         ->with('success', 'Despesa recorrente atualizada com sucesso!');
    }

    /**
     * Deletar despesa recorrente.
     */
    public function destroy(DespesaRecorrente $despesaRecorrente)
    {
        $despesaRecorrente->delete();

        return redirect()->route('financeiro.despesas_recorrentes.index')
                         ->with('success', 'Despesa recorrente removida com sucesso!');
    }
}
