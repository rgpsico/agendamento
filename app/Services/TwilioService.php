<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $this->client = new Client(
            env('TWILIO_SID'),
            env('TWILIO_TOKEN')
        );

        $this->from = env("TWILIO_WHATSAPP");
    }

    public function sendWhatsApp($to, $message)
    {
        return $this->client->messages->create(
            "whatsapp:$to", // destino
            [
                "from" => $this->from,
                "body" => $message
            ]
        );
    }

    public function enviarConfirmacaoAgendamento($agendamento, $pagamento)
    {

        $aluno = $agendamento->aluno->usuario; // assumindo relacionamento
        $professor = $agendamento->professor->usuario;
        $modalidade = $agendamento->modalidade;

        $mensagem = "Olá! Novo agendamento confirmado:\n\n";
        $mensagem .= "Aluno: {$aluno->nome}\n";
        $mensagem .= "Professor: {$professor->nome}\n";
        $mensagem .= "Modalidade: {$modalidade->nome}\n";
        $mensagem .= "Data da Aula: " . \Carbon\Carbon::parse($agendamento->data_da_aula)->format('d/m/Y') . "\n";
        $mensagem .= "Horário: {$agendamento->horario}\n";
        $mensagem .= "Valor: R$ {$agendamento->valor_aula}\n";
        $mensagem .= "Status do Pagamento: {$pagamento->status}";

        try {
            
            $telefone = $this->formatPhone($professor->empresa->telefone);
             $this->sendWhatsApp($telefone, $mensagem);

        } catch (\Exception $e) {
            \Log::error('Erro ao enviar WhatsApp: ' . $e->getMessage());
            // Não interrompe o fluxo do controller
        }
    }

    private function formatPhone($phone)
    {
        // remove tudo que não for número
        $digits = preg_replace('/\D+/', '', $phone);

        // se já começar com 55, mantemos
        if (substr($digits, 0, 2) !== '55') {
            $digits = '55' . $digits;
        }

        return $digits;
    }


}
