<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfiguracoesController extends Controller
{
    public function permissoes()
    {
        return view('configuracoes.permissoes');
    }

    public function pagamentos()
    {
        return view('configuracoes.pagamentos');
    }

    public function empresa()
    {
        return view('configuracoes.empresa');
    }

    public function usuarios()
    {
        return view('configuracoes.usuarios');
    }

    public function sistema()
    {
        return view('configuracoes.sistema');
    }
}
