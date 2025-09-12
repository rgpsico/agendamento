<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\Agendamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceitaController extends Controller
{
    /**
     * Listar todas as receitas (pagamentos).
     */
    public function index()
    {
        $receitas = Pagamento::with(['agendamento', 'aluno'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.financeiro.receitas.index', compact('receitas'));
    }

    /**
     * Mostrar formulário para lançar uma nova receita manualmente.
     */
   public function create()
   {
      // Pega o professor logado
      $professor = Auth::user()->professor;

      if (!$professor) {
         auth()->logout();
         return redirect()->back()->with('error', 'O Professor com esse e-mail não existe.');
      }

      // Pega todos os alunos do professor
      $alunos = $professor->alunos()->with('usuario')->get();

      // Pega os agendamentos dos alunos
      $agendamentos = Agendamento::with(['aluno.usuario', 'modalidade'])
         ->whereIn('aluno_id', $alunos->pluck('id'))
         ->get();

      return view('admin.financeiro.receitas.create', compact('alunos', 'agendamentos'));
   }


    /**
     * Salvar uma nova receita no banco.
     */
    public function store(Request $request)
    {
        $request->validate([
            'agendamento_id' => 'required|exists:agendamentos,id',
            'aluno_id' => 'required|exists:alunos,id',
            'valor' => 'required|numeric|min:0',
            'status' => 'required|in:PENDING,RECEIVED',
            'metodo_pagamento' => 'required|string',
        ]);

        $agendamento = Agendamento::findOrFail($request->agendamento_id);

        Pagamento::create([
            'agendamento_id' => $agendamento->id,
            'aluno_id' => $request->aluno_id,
            'valor' => $request->valor,
            'status' => $request->status,
            'metodo_pagamento' => $request->metodo_pagamento,
            'data_vencimento' => $request->data_vencimento ?? now(),
        ]);

        return redirect()->route('financeiro.receitas.index')
            ->with('success', 'Receita lançada com sucesso!');
    }

    /**
     * Editar receita existente.
     */
    public function edit($id)
    {
        $receita = Pagamento::findOrFail($id);
        $agendamentos = Agendamento::with('aluno')->get();

        return view('admin.financeiro.receitas.edit', compact('receita', 'agendamentos'));
    }

    /**
     * Atualizar receita.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0',
            'status' => 'required|in:PENDING,RECEIVED',
            'metodo_pagamento' => 'required|string',
        ]);

        $receita = Pagamento::findOrFail($id);
        $receita->update($request->only(['valor', 'status', 'metodo_pagamento', 'data_vencimento']));

        return redirect()->route('financeiro.receitas.index')
            ->with('success', 'Receita atualizada com sucesso!');
    }

    /**
     * Excluir receita.
     */
    public function destroy($id)
    {
        $receita = Pagamento::findOrFail($id);
        $receita->delete();

        return redirect()->route('financeiro.receitas.index')
            ->with('success', 'Receita excluída com sucesso!');
    }
}
