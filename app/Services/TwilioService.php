<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $sid;
    protected $token;
    protected $from;

    public function __construct()
    {
        $this->sid = env('TWILIO_SID');
        $this->token = env('TWILIO_AUTH_TOKEN');
        $this->from = env('TWILIO_WHATSAPP_FROM');
    }

    public function sendWhatsAppMessage($to, $message)
    {
        $client = new Client($this->sid, $this->token);

        $client->messages->create(
            "whatsapp:+5521990271287", // Número de destinatário
            [
                'from' => 'whatsapp:+14155238886', // Número de WhatsApp Sandbox
                'body' => 'Mensagem de teste via Twilio 10'
            ]
        );
    }
}
