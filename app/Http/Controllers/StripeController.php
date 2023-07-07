<?php

namespace App\Http\Controllers;

use App\Http\Requests\TreinoStripeRequest;
use App\Models\Agendamento;
use App\Models\Alunos;
use App\Models\Professor;
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

        $aluno = new Alunos();
        $aluno->usuario_id = $user->id;

        $aluno->save();

        $aluno_id = $aluno->id;
        $data_agendamento = $request->data_aula;
        $hora_agendamento = $request->hora_aula;
        $professor_id = $request->professor_id;
        $modalidade_id = $request->aula_id ??  1;

        $professor = Professor::with('usuario')->where('usuario_id', $professor_id)->first();

        if (!$professor) {
            // Você pode retornar um erro ou redirecionar de volta com uma mensagem de erro
            return redirect()->back()->with('erro', 'Professor não encontrado');
        }

        $data_agendamento_formato_eua = Carbon::createFromFormat('j M. Y', $data_agendamento)->format('Y-m-d');

        $agendamento = Agendamento::create([
            'aluno_id' => $aluno_id,
            'modalidade_id' => $modalidade_id,
            'professor_id' => $professor->id, // Aqui você deve usar o id do professor, não o usuario_id
            'data_da_aula' => $data_agendamento_formato_eua . ' ' . $hora_agendamento,
            'valor_aula' => $request->total
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
            'amount' => $request->total * 100, // Convertendo o valor para centavos
            'currency' => 'brl', // Usando a moeda correta
            'source' => $res->id,
            'description' => $request->description
        ]);

        $nome_do_professor = $professor->usuario->nome;

        if ($response->status === 'succeeded') {

            return view('public.home.checkoutsucesso', ['nome_professor' => $nome_do_professor]);
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
