<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
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

        $query = Empresa::with('endereco', 'galeria', 'avaliacao');

        if ($tipos) {
            $tipos = explode(',', $tipos); // divide a string em um array
            $query = $query->whereIn('tipo', $tipos); // filtra por todos os tipos
        }

        if ($nome) {
            $query = $query->where('nome', 'like', "%{$nome}%"); // pesquisa por empresas cujo nome contém a string fornecida
        }

        return $query->get();
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

    public function update(Request $request, Empresa $empresa)
    {
        $empresa->update($request->all());
        return response()->json($empresa, 200);
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return response()->json(null, 204);
    }
}
