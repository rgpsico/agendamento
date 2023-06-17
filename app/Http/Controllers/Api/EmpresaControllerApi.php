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
