<?php

namespace App\Http\Controllers;

use App\Models\PlanoAluno;
use Illuminate\Http\Request;

class PlanoAlunoController extends Controller
{
    // Listar todos os planos
    public function index()
    {
        $planos = PlanoAluno::all();
        return response()->json($planos);
    }

    // Criar novo plano
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'valor' => 'required|numeric',
            'duracao_dias' => 'required|integer',
        ]);

        $plano = PlanoAluno::create($validated);

        // Redireciona para a listagem de planos com mensagem de sucesso
        return redirect()->route('alunos.planos.index')
            ->with('success', 'Plano criado com sucesso!');
    }


    // Mostrar um plano específico
    public function show($id)
    {
        $plano = PlanoAluno::findOrFail($id);
        return response()->json($plano);
    }

    // Atualizar um plano
    public function update(Request $request, $id)
    {
        $plano = PlanoAluno::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'descricao' => 'nullable|string',
            'valor' => 'sometimes|required|numeric',
            'duracao_dias' => 'sometimes|required|integer',
        ]);

        $plano->update($validated);

        // Redireciona para a listagem de planos com mensagem de sucesso
        return redirect()->route('alunos.planos.index')
            ->with('success', 'Plano atualizado com sucesso!');
    }


    // Deletar um plano
    public function destroy($id)
    {
        $plano = PlanoAluno::findOrFail($id);
        $plano->delete();

        return redirect()->route('alunos.planos.index')
            ->with('success', 'Plano Excluido com sucesso!');
    }


    // Tela de listagem
    public function indexView()
    {
        $planos = PlanoAluno::all();
        return view('admin.aluno.planos.index', compact('planos'));
    }

    // Tela de criação
    public function create()
    {
        return view('admin.aluno.planos.create');
    }

    // Tela de edição
    public function edit(PlanoAluno $plano)
    {
        return view('admin.aluno.planos.edit', compact('plano'));
    }

    // Funções store, update e destroy continuam iguais

}
