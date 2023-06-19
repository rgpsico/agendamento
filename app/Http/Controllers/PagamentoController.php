<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class PagamentoController extends Controller
{
    public function pagamentoStripe(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $token = $request->stripeToken;
        $amount = $request->amount; // obter a quantia da sessão, banco de dados, etc.

        try {
            $charge = \Stripe\Charge::create([
                'amount' => 1000, // Este é o valor da cobrança. Neste exemplo, $10.00
                'currency' => 'usd', // Esta é a moeda.
                'source' => $token, // Este é o token do cartão de crédito.
                'description' => 'Descrição da cobrança aqui',
            ]);
            // de pagamento para o usuário, etc.

            // Você pode verificar se a cobrança foi bem-sucedida assim:
            if ($charge->paid == true) {
                // Aqui você pode redirecionar o usuário para uma página de agradecimento, por exemplo
                return redirect()->route('home.checkoutsucesso', ['id' => 1]);
            } else {
                // Aqui você pode redirecionar o usuário para uma página de erro, por exemplo
                return redirect()->route('erroPagamento');
            }
        } catch (\Exception $ex) {

            // Aqui você pode capturar e lidar com possíveis erros
            // Você pode querer logar o erro e mostrar uma mensagem amigável ao usuário
            return redirect()->back()->with('error', 'Ocorreu um erro ao processar o seu pagamento. Por favor, tente novamente.');
        }
    }
}
