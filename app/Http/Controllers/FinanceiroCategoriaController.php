<?php

namespace App\Http\Controllers;

use App\Models\DespesaCategoria;
use App\Models\FinanceiroCategoria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Categorias do financeiro.
 * - Receita: tabela financeiro_categorias (tipo = receita). Cada serviço cria a sua automaticamente.
 * - Despesa: tabela despesas_categorias (é para ela que apontam despesas e despesas recorrentes).
 * As duas são filtradas pela empresa logada (DaEmpresaLogada); categorias padrão (empresa_id nulo)
 * aparecem para todos, mas não podem ser editadas nem excluídas.
 */
class FinanceiroCategoriaController extends Controller
{
    public function index()
    {
        $categoriasReceita = FinanceiroCategoria::where('tipo', 'receita')->orderBy('nome')->get();
        $categoriasDespesa = DespesaCategoria::orderByRaw('empresa_id IS NULL')->orderBy('nome')->get();

        return view('admin.financeiro.categorias.index', compact('categoriasReceita', 'categoriasDespesa'));
    }

    public function create(Request $request)
    {
        $tipo = $request->get('tipo') === 'DESPESA' ? 'DESPESA' : 'RECEITA';

        return view('admin.financeiro.categorias.create', compact('tipo'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome'      => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'tipo'      => 'required|in:DESPESA,RECEITA',
        ]);

        if ($dados['tipo'] === 'DESPESA') {
            DespesaCategoria::create(['nome' => $dados['nome'], 'descricao' => $dados['descricao'] ?? null]);
        } else {
            FinanceiroCategoria::create(['nome' => $dados['nome'], 'descricao' => $dados['descricao'] ?? null, 'tipo' => 'receita']);
        }

        return redirect()->route('financeiro.categorias.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function show(Request $request, $categoria)
    {
        return redirect()->route('financeiro.categorias.edit', ['categoria' => $categoria, 'tipo' => $request->get('tipo')]);
    }

    public function edit(Request $request, $categoria)
    {
        $tipo = $this->tipo($request);
        $categoria = $this->daEmpresa($tipo, $categoria);

        return view('admin.financeiro.categorias.edit', compact('categoria', 'tipo'));
    }

    public function update(Request $request, $categoria)
    {
        $tipo = $this->tipo($request);
        $categoria = $this->daEmpresa($tipo, $categoria);

        $dados = $request->validate([
            'nome'      => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
        ]);

        $categoria->update($dados);

        return redirect()->route('financeiro.categorias.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Request $request, $categoria)
    {
        $tipo = $this->tipo($request);
        $categoria = $this->daEmpresa($tipo, $categoria);

        $emUso = $tipo === 'DESPESA'
            ? $categoria->despesas()->exists() || \App\Models\DespesaRecorrente::where('categoria_id', $categoria->id)->exists()
            : \App\Models\Receita::where('categoria_id', $categoria->id)->exists()
                || \App\Models\ReceitaRecorrente::where('categoria_id', $categoria->id)->exists()
                || \App\Models\Servicos::where('categoria_id', $categoria->id)->exists();

        if ($emUso) {
            return redirect()->route('financeiro.categorias.index')
                ->with('error', 'Esta categoria está em uso por lançamentos ou serviços e não pode ser excluída. Você pode renomeá-la.');
        }

        $categoria->delete();

        return redirect()->route('financeiro.categorias.index')
            ->with('success', 'Categoria removida com sucesso!');
    }

    private function tipo(Request $request): string
    {
        return strtoupper((string) $request->get('tipo')) === 'DESPESA' ? 'DESPESA' : 'RECEITA';
    }

    /** Categoria da própria empresa; de outra empresa vira 404 e as padrão (sem empresa) não podem ser alteradas. */
    private function daEmpresa(string $tipo, $id): Model
    {
        $categoria = $tipo === 'DESPESA'
            ? DespesaCategoria::findOrFail($id)
            : FinanceiroCategoria::findOrFail($id);

        abort_if($categoria->empresa_id === null, 403, 'Categorias padrão do sistema não podem ser alteradas. Crie uma categoria própria.');

        return $categoria;
    }
}
