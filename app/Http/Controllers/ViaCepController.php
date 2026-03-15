<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ViaCepController extends Controller
{
    public function getCep($cep)
    {
        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
        return $response->json();
    }
}