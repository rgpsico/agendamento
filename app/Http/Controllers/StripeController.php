<?php

namespace App\Http\Controllers;

use App\Http\Requests\TreinoStripeRequest;
use App\Models\Usuario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stripe\Charge;
use Stripe\StripeClient;

class StripeController extends Controller
{

    public function index()
    {
        return view('stripe.index');
    }



    public function pagamento(Request $request)
    {
        // Usamos json_decode porque os dados estão em uma string JSON
        $servicos = json_decode($request->servicos, true);

        // Aqui você pode fazer o que precisar com os serviços. 
        // Por exemplo, você pode querer somar o total para cobrar o usuário:
        $total = 0;
        foreach ($servicos as $servico) {
            $total += $servico['preco'];
        }

        // Converta o total para centavos para usar com o Stripe
        $valor = $total * 100;

        // Obtém o token do Stripe do formulário
        $token = $request->input('stripeToken');

        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            // Processa o pagamento
            $charge = Charge::create([
                'amount' => $valor,
                'currency' => 'usd', // Você provavelmente vai querer ajustar a moeda aqui
                'source' => $token,
                'description' => 'Pagamento de serviços',
            ]);

            // Você não precisa criar outra cobrança para um cliente existente a menos que 
            // queira cobrar duas vezes. Se você quer que isso seja uma opção, provavelmente 
            // deve fazer uma verificação aqui para ver se deve usar um cliente existente 
            // ou o token do formulário.

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // No caso de sucesso, retorna uma resposta
        return response()->json(['success' => true]);
    }


    public function treinoStripe(TreinoStripeRequest $request)
    {

        $stripe = new StripeClient(env('STRIPE_SECRET'));

        $user = new Usuario();
        $user->nome = $request->nome;
        $user->email = $request->email;
        $user->tipo_usuario = 'Aluno';
        $user->password = Hash::make('senha');
        $user->save();


        $res = $stripe->tokens->create([
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => '07',
                'exp_year' => '2025',
                'cvc' => '123',
                'name' => 'Fabi revendedora da Silva'

            ]
        ]);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        // Criar um pagamento com um cliente existente
        $response =  $stripe->charges->create([
            'amount' => 1000, // valor em centavos
            'currency' => 'usd',
            'source' => $res->id, // substitua pelo token de cartão válido
            'description' => 'Exemplo de pagamento com cartão de crédito',
        ]);

        if ($response->status === 'succeeded') {
            return  response(['content' =>  $response]);
        } else {
            // Lógica para tratamento de erro, caso o pagamento não tenha sido bem-sucedido
        }
    }

    public function all()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $charges = \Stripe\Charge::all();
        $data = $charges->data;

        return response()->json(['content' => $data]);
    }
}
