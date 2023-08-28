<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use Carbon\Carbon;
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

        $modalidade_id = $request->modalidade_id;

        $originalDate = $request->data_aula;
        $hora_aula = $request->hora_aula;

        $data_agendamento_formato_eua = PagamentoController::convertToUSFormat($originalDate) . ' ' . $hora_aula;



        try {
            $charge = \Stripe\Charge::create([
                'amount' => 200.00, // Este é o valor da cobrança. Neste exemplo,    $10.00
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
                    'data_da_aula' => $data_agendamento_formato_eua, // Você precisa definir essa variável
                    'valor_aula' => $valor_aula,
                ]);

                // Aqui você pode redirecionar o usuário para uma página de agradecimento, por exemplo
                return redirect()->route('home.checkoutsucesso', ['id' =>  $professor_id]);
            } else {
                // Aqui você pode redirecionar o usuário para uma página de erro, por exemplo
                return redirect()->route('erroPagamento');
            }
        } catch (\Exception $ex) {

            // Aqui você pode capturar e lidar com possíveis erros
            // Você pode querer logar o erro e mostrar uma mensagem amigável ao usuário
            return redirect()->back()->with('error', 'Ocorreu um erro ao processar o seu pagamento. Por favor, tente novamente.' . $ex);
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
