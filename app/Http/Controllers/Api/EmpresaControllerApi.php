<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Professor;
use Illuminate\Http\Request;

class EmpresaControllerApi extends Controller
{
    public function index()
    {

        return $empresas = Empresa::with('endereco', 'galeria', 'avaliacao')->get();
    }

    public function search(Request $request)
    {
        $tipos = $request->input('tipo');

        $nome = $request->input('nome_empresa');

        $query = Empresa::with('modalidade', 'endereco', 'galeria', 'avaliacao');

        if ($tipos) {
            $tipos = explode(',', $tipos); // divide a string em um array
            $query = $query->whereIn('modalidade_id', $tipos); // filtra por todos os tipos
        }

        if ($nome) {
            $query = $query->where('nome', 'like', "%{$nome}%"); // pesquisa por empresas cujo nome contém a string fornecida
        }

        return $query->get();
    }

    public function verificarStatus($empresaId)
    {
        $empresa = Empresa::find($empresaId);

        if (!$empresa) {
            return response()->json(['error' => 'Empresa não encontrada'], 404);
        }

        $status = $empresa->status === 'ativo' || $empresa->status === 1 ? 'ativo' : 'inativo';
        return response()->json(['status' => $status, 'empresa' => $empresa->nome], 200);
    }

    public function getAlunoByIdProfessor($id)
    {
        $professor = Professor::find($id);

        if (!$professor) {
            return response()->json(['error' => 'Professor não encontrado'], 404);
        }


        $alunosDoProfessor = $professor->alunos;

        // Monta um array com os dados de nome e email dos alunos
        $alunosArray = [];
        foreach ($alunosDoProfessor as $aluno) {
            $alunosArray[] = [
                'nome' => $aluno->usuario->nome,
                'email' => $aluno->usuario->email,
                // Outras informações do aluno (se houver)
            ];
        }

        return response()->json($alunosArray);
    }



    public function store(Request $request)
    {
        $empresa = Empresa::create($request->all());
        return response()->json($empresa, 201);
    }

    public function show(Empresa $empresa)
    {
        return $empresa;
    }

    public function update(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|integer|exists:empresas,id',
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
            'celular' => 'nullable|string',
            'endereco' => 'nullable|string',
            'cidade' => 'nullable|string',
            'estado' => 'nullable|string',
            'cep' => 'nullable|string',
            'pais' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $empresa = Empresa::findOrFail($request->empresa_id);

        $empresa->update([
            'nome' => $request->nome,
            'email' => $request->email,
            'celular' => $request->celular,
            'endereco' => $request->endereco,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'cep' => $request->cep,
            'pais' => $request->pais,
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('empresas/logos', 'public');
            $empresa->logo = $path;
            $empresa->save();
        }

        return response()->json(['success' => true, 'empresa' => $empresa]);
    }


    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return response()->json(null, 204);
    }
}
