<?php

namespace App\Http\Controllers;

use App\Mail\PaymentConfirmation;
use App\Models\Agendamento;
use App\Models\AlunoProfessor;
use App\Models\Alunos;
use App\Models\Professor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Charge;

class PagamentoController extends Controller
{
    protected $aluno_professor;

    public function __construct(AlunoProfessor $aluno_professor)
    {
        $this->aluno_professor  =   $aluno_professor;
    }


    public function pagamentoStripe(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $token = $request->stripeToken;

        $aluno_id = $request->aluno_id;
        $professor_id = $request->professor_id;

        $valor_aula = $request->valor_aula;

        $modalidade_id = $request->modalidade_id;

        $originalDate = $request->data_aula;
        $hora_aula = $request->hora_aula;




        $data_agendamento_formato_eua = PagamentoController::convertToUSFormat($originalDate) . ' ' . $hora_aula;

        $aluno = Alunos::find($aluno_id);
        $professor = Professor::find($professor_id);

        $aluno->professores()->attach($professor);

        try {
            $charge = \Stripe\Charge::create([
                'amount' => 20000, // Alterado para 20000 centavos para corresponder ao valor em centavos
                'currency' => 'usd',
                'source' => $token,
                'description' => $request->titulo,
            ]);


            if ($charge->paid == true) {
                $conta_professor = "acct_1P23pLPi8YEsgyY7";

                $amount_brl = PagamentoController::convertToBRL($valor_aula, 'USD');

                $transfer = \Stripe\Transfer::create([
                    'amount' => $amount_brl, // Valor convertido para BRL
                    'currency' => 'brl', // Definindo a moeda como BRL
                    'destination' => $conta_professor,
                    'transfer_group' => 'pagamento_aula_' . $aluno_id,
                    'source_transaction' => $charge->id, // Adicione o ID da transação de origem
                ]);

                Agendamento::create([
                    'aluno_id' => $aluno_id,
                    'modalidade_id' => $modalidade_id, // Defina essa variável conforme necessário
                    'professor_id' => $professor_id, // Defina essa variável conforme necessário
                    'data_da_aula' => $data_agendamento_formato_eua, // Defina essa variável conforme necessário
                    'valor_aula' => $valor_aula,
                    'horario' => $hora_aula
                ]);

                Mail::to(['email' => Auth::user()->email])->send(new PaymentConfirmation(Auth::user()));
                // Aqui você pode redirecionar o usuário para uma página de agradecimento, por exemplo
                return redirect()->route('home.checkoutsucesso', ['id' =>  $professor_id]);
            } else {
                // Aqui você pode redirecionar o usuário para uma página de erro, por exemplo
                return redirect()->route('erroPagamento');
            }
        } catch (\Exception $ex) {

            return redirect()->back()->with('error', 'Ocorreu um erro ao processar o seu pagamento. Por favor, tente novamente.' . $ex);
        }
    }

    public static function convertToBRL($amount, $from_currency)
    {
        // Defina as taxas de câmbio aqui ou use uma API de terceiros para obter as taxas de câmbio em tempo real
        $exchange_rates = [
            'USD' => 50, // Exemplo de taxa de câmbio USD -> BRL
            // Adicione mais taxas de câmbio conforme necessário para outras moedas
        ];

        // Verifique se a moeda de origem está presente nas taxas de câmbio
        if (isset($exchange_rates[$from_currency])) {
            // Converta o valor para BRL usando a taxa de câmbio
            $brl_amount = $amount * $exchange_rates[$from_currency];
            return $brl_amount;
        } else {
            throw new \Exception("Taxa de câmbio para '$from_currency' não encontrada.");
        }
    }


    public static function convertToUSD($amount, $from_currency)
    {
        // Defina as taxas de câmbio aqui ou use uma API de terceiros para obter as taxas de câmbio em tempo real
        $exchange_rates = [
            'BRL' => 0.18, // Exemplo de taxa de câmbio BRL -> USD
            // Adicione mais taxas de câmbio conforme necessário para outras moedas
        ];

        // Verifique se a moeda de origem está presente nas taxas de câmbio
        if (isset($exchange_rates[$from_currency])) {
            // Converta o valor para USD usando a taxa de câmbio
            $usd_amount = $amount * $exchange_rates[$from_currency];
            return $usd_amount;
        } else {
            throw new \Exception("Taxa de câmbio para '$from_currency' não encontrada.");
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
}
