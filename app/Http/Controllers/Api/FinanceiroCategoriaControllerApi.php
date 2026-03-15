<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinanceiroCategoria;

class FinanceiroCategoriaControllerApi extends Controller
{
    public function index()
    {
        $categorias = FinanceiroCategoria::all();
        return response()->json($categorias);
    }
}
