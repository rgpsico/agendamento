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
}
