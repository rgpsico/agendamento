<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class PagamentoController extends Controller
{
    public function pagamentoStripe(Request $request)
    {


        Stripe::setApiKey(env('STRIPE_SECRET'));

        $token = $request->stripeToken;

        $aluno_id = $request->aluno_id;
        $professor_id = $request->professor_id;
        $valor_aula = $request->valor_aula;
        $data_da_aula = $request->data_aula;
        $modalidade_id = $request->modalidade_id;

        try {
            $charge = \Stripe\Charge::create([
                'amount' => $valor_aula, // Este é o valor da cobrança. Neste exemplo, $10.00
                'currency' => 'usd', // Esta é a moeda.
                'source' => $token, // Este é o token do cartão de crédito.
                'description' => $request->titulo,
            ]);
            // de pagamento para o usuário, etc.

            // Você pode verificar se a cobrança foi bem-sucedida assim:
            if ($charge->paid == true) {

                Agendamento::create([
                    'aluno_id' => $aluno_id,
                    'modalidade_id' => $modalidade_id, // Você precisa definir essa variável
                    'professor_id' => $professor_id, // Você precisa definir essa variável
                    'data_da_aula' => $data_da_aula, // Você precisa definir essa variável
                    'valor_aula' => $valor_aula,
                ]);

                // Aqui você pode redirecionar o usuário para uma página de agradecimento, por exemplo
                return redirect()->route('home.checkoutsucesso', ['id' =>  $aluno_id]);
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
