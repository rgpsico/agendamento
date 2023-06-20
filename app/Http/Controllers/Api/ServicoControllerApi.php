<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use App\Models\Servicos;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ServicoControllerApi extends Controller
{
    // Mostrar todos os usuários

    protected $model;

    public function __construct(Servicos $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $model = $this->model::all();
        return response()->json($model);
    }

    // Criar um novo usuário
    public function store(Request $request)
    {
        $model = $this->model::create($request->all());


        return response()->json($model, 201);
    }

    // Mostrar um usuário específico
    public function show($id)
    {
        $model = $this->model::find($id);
        if ($model) {
            return response()->json($model);
        } else {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
    }

    // Atualizar um usuário específico
    public function update(Request $request, $id)
    {
        $model = $this->model::find($id);

        if ($model) {
            $model->update($request->all());
            return response()->json($model);
        } else {
            return response()->json(['error' => 'Serviço não encontrado'], 404);
        }
    }

    // Deletar um usuário específico
    public function destroy($id)
    {
        $user = $this->model::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => 'Serviço deletado com sucesso'], 200);
        } else {
            return response()->json(['error' => 'Serviço não encontrado'], 404);
        }
    }
}
