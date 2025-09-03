<?php

namespace App\Services;

use App\Mail\AgendamentoMail;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function enviarAgendamento($agendamento, $pagamento, $canais = ['whatsapp', 'email'])
    {
        $dados = [
            'aluno'      => $agendamento->aluno->usuario->nome,
            'professor'  => $agendamento->professor->usuario->nome,
            'modalidade' => $agendamento->modalidade->nome,
            'data'       => \Carbon\Carbon::parse($agendamento->data_da_aula)->format('d/m/Y'),
            'horario'    => $agendamento->horario,
            'valor'      => $agendamento->valor_aula,
            'status'     => $pagamento->status,
        ];

        if (in_array('whatsapp', $canais)) {
            $telefone = $this->formatPhone($agendamento->professor->empresa->telefone);
            $mensagem = $this->montarMensagem($dados);
            $this->twilioService->sendWhatsApp($telefone, $mensagem);
        }

        if (in_array('email', $canais)) {
            $email = $agendamento->professor->usuario->email;
            Mail::to($email)->send(new AgendamentoMail($dados));
        }
    }

    private function montarMensagem($dados)
    {
        return "Olá! Novo agendamento confirmado:\n\n" .
            "Aluno: {$dados['aluno']}\n" .
            "Professor: {$dados['professor']}\n" .
            "Modalidade: {$dados['modalidade']}\n" .
            "Data da Aula: {$dados['data']}\n" .
            "Horário: {$dados['horario']}\n" .
            "Valor: R$ {$dados['valor']}\n" .
            "Status do Pagamento: {$dados['status']}";
    }

    private function formatPhone($phone)
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (substr($digits, 0, 2) !== '55') {
            $digits = '55' . $digits;
        }
        return $digits;
    }
}
