<?php

namespace App\Http\Controllers;

use App\Http\Requests\TreinoStripeRequest;
use App\Models\Agendamento;
use App\Models\Usuario;
use Carbon\Carbon;
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

        dd($request->all());
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

        $user_id = $user->id;
        $data_agendamento = $request->data_aula;
        $hora_agendamento = $request->hora_aula;
        $professor_id = $request->professor_id;
        $aula_id = $request->aula_id ??  1;

        $data_agendamento_formato_eua = Carbon::createFromFormat('j M. Y', $data_agendamento)->format('Y-m-d');

        $agendamento = Agendamento::create([
            'aluno_id' => 1,
            'aula_id' => $aula_id,
            'professor_id' => $professor_id,
            'data_agendamento' => $data_agendamento_formato_eua . ' ' . $hora_agendamento,
        ]);

        $res = $stripe->tokens->create([
            'card' => [
                'number' => $request->numero_cartao,
                'exp_month' => $request->mes_vencimento,
                'exp_year' => $request->ano_vencimento,
                'cvc' => $request->cvv,
                'name' => $request->nome_cartao
            ]
        ]);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $response = $stripe->charges->create([
            'amount' => $request->total, // Valor em centavos, substitua pelo valor correto
            'currency' => 'usd', // Substitua pela moeda correta
            'source' => $res->id,
            'description' => $request->description
        ]);

        if ($response->status === 'succeeded') {


            return response(['content' => $response]);
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
