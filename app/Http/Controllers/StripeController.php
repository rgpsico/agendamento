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
use Illuminate\Support\Facades\Auth;
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


        $existingUser = Usuario::where('email', $request->email)->first();

        if (!$existingUser) {
            // Se o usuário não existir, crie um novo
            $user = new Usuario();
            $user->nome = $request->nome;
            $user->email = $request->email;
            $user->tipo_usuario = 'Aluno';
            $user->password = Hash::make('senha');
            $user->save();

            $aluno = new Alunos();
            $aluno->usuario_id = $user->id;
            $aluno->save();
        } else {
            // Se o usuário já existir, use as informações existentes
            $user = $existingUser;
            $aluno = Alunos::where('usuario_id', $user->id)->first();
        }

        Auth::login($user);

        $aluno_id = $aluno->id;
        $data_agendamento = $request->data_aula;
        $hora_agendamento = $request->hora_aula;
        $professor_id = $request->professor_id;
        $modalidade_id = $request->aula_id ??  1;

        $professor = Professor::with('usuario')->where('usuario_id', $professor_id)->first();
        $data_agendamento_formato_eua = PagamentoController::convertToUSFormat($data_agendamento) . ' ' . $hora_agendamento;

        if (!$professor) {
            return redirect()->back()->with('erro', 'Professor não encontrado');
        }


        $agendamento = Agendamento::create([
            'aluno_id' => $aluno_id,
            'modalidade_id' => $modalidade_id,
            'professor_id' => $professor->id, // Aqui você deve usar o id do professor, não o usuario_id
            'data_da_aula' => $data_agendamento_formato_eua,
            'valor_aula' => $request->total,
            'horario' => $hora_agendamento
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
            return response()->json(['content' => $professor]);
        } else {
            // Lógica para tratamento de erro, caso o pagamento não tenha sido bem-sucedido
        }
    }

    public static function convertToUSFormat($originalDate)
    {
        // Remova pontos após os meses
        $originalDate = str_replace(
            [' jan. ', ' fev. ', ' mar. ', ' abr. ', ' mai. ', ' jun. ', ' jul. ', ' ago. ', ' set. ', ' out. ', ' nov. ', ' dez. '],
            [' jan ', ' fev ', ' mar ', ' abr ', ' mai ', ' jun ', ' jul ', ' ago ', ' set ', ' out ', ' nov ', ' dez '],
            $originalDate
        );

        // Mapeie meses em português para seus números correspondentes
        $months = [
            'jan' => '01',
            'fev' => '02',
            'mar' => '03',
            'abr' => '04',
            'mai' => '05',
            'jun' => '06',
            'jul' => '07',
            'ago' => '08',
            'set' => '09',
            'out' => '10',
            'nov' => '11',
            'dez' => '12',
        ];


        // Separe a string por espaços
        $parts = explode(' ', $originalDate);

        // Converta o mês textual para o número correspondente
        $monthNumber = $months[strtolower($parts[1])];

        // Retorne a data no formato "Y-m-d"
        return "{$parts[2]}-{$monthNumber}-{$parts[0]}";
    }


    public function all()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $charges = \Stripe\Charge::all();
        $data = $charges->data;

        return response()->json(['content' => $data]);
    }
}
