<?php

namespace App\Http\Controllers;

use App\Http\Requests\TreinoStripeRequest;
use App\Mail\PaymentConfirmation;
use App\Models\Agendamento;
use App\Models\AlunoProfessor;
use App\Models\Alunos;
use App\Models\DisponibilidadeServico;
use App\Models\Professor;
use App\Models\Usuario;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

        // Buscar ou criar usuário
        $existingUser = Usuario::where('email', $request->email)->first();

        if (!$existingUser) {
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
            $user = $existingUser;
            $aluno = Alunos::where('usuario_id', $user->id)->first();
        }

        if ($aluno == null) {
            return response()->json(['error' => 'Erro aluno não encontrado'], 500);
        }

        $aluno_id = $aluno->id;
        $data_agendamento = $request->data_aula;
        $hora_agendamento = $request->hora_aula;
        $professor_id = $request->professor_id;

        $professor = Professor::select('id')->where('usuario_id', $professor_id)->first();

        $existingRelation = AlunoProfessor::where('aluno_id', $aluno->id)
            ->where('professor_id', $professor->id)
            ->first();

        if (!$existingRelation) {
            $alunoProfessor = new AlunoProfessor();
            $alunoProfessor->aluno_id = $aluno->id;
            $alunoProfessor->professor_id = $professor->id;
            $alunoProfessor->save();
        }

        $professor = Professor::with('usuario')->where('usuario_id', $professor_id)->first();
        $data_agendamento_formato_eua = PagamentoController::convertToUSFormat($data_agendamento) . ' ' . $hora_agendamento;

        if (!$professor) {
            return redirect()->back()->with('erro', 'Professor não encontrado');
        }

        // 🚀 **Lógica para Serviços**
        $servicos = $request->servicos;

        if (!is_array($servicos) || count($servicos) === 0) {
            return response()->json(['error' => 'Nenhum serviço selecionado'], 400);
        }

        foreach ($servicos as $servico) {
            $servico_id = $servico['id'];
            $tipo_agendamento = $servico['tipo_agendamento'];
            $modalidade_id = $request->aula_id ??  1;
            $agendamento = Agendamento::create([
                'aluno_id' => $aluno_id,
                'modalidade_id' => $modalidade_id,
                'professor_id' => $professor->id, // Aqui você deve usar o id do professor, não o usuario_id
                'data_da_aula' => $data_agendamento_formato_eua,
                'valor_aula' => $request->total,
                'horario' => $hora_agendamento
            ]);

            // 🚀 **Se for um serviço do tipo DIA, reduz uma vaga**
            if ($tipo_agendamento === 'DIA') {
                $disponibilidade = DisponibilidadeServico::where('servico_id', $servico_id)
                    ->where('data', $data_agendamento)
                    ->first();

                if ($disponibilidade && $disponibilidade->vagas_totais > $disponibilidade->vagas_reservadas) {
                    $disponibilidade->increment('vagas_reservadas');
                } else {
                    return response()->json(['error' => 'Nenhuma vaga disponível para essa data'], 400);
                }
            }
        }

        // Criando token para o pagamento
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
            'amount' => $request->total * 100,
            'currency' => 'brl',
            'source' => $res->id,
            'description' => "Pagamento de serviços"
        ]);

        if ($response->status === 'succeeded') {
            Auth::login($user);
            Mail::to($user->email)->send(new PaymentConfirmation($user));

            return response()->json(['content' => $professor]);
            //   return response()->json(['success' => true, 'message' => 'Pagamento realizado com sucesso']);
        } else {
            return response()->json(['error' => 'Falha no pagamento'], 500);
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
