<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IntegrationsController extends Controller
{
    public function asaas()
    {
        // Lógica para carregar configurações do Asaas (ex.: chaves de API salvas)
        return view('admin.integracoes.asaas');
    }

   

    public function stripe()
    {
        // Lógica para carregar configurações do Stripe
        return view('admin.integracoes.stripe');
    }

    public function mercadopago()
    {
        // Lógica para carregar configurações do Mercado Pago
        return view('integracao.mercadopago');
    }

    public function configuracoes()
    {
        // Lógica para configurações gerais (ex.: regras de split)
        return view('integracao.configuracoes');
    }

    public function relatorios()
    {
        // Lógica para exibir relatórios de transações
        return view('integracao.relatorios');
    }
}
