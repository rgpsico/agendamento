<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;

class EmailController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function send(Request $request)
    {
        $to = $request->input("to"); // Ex: 5521990271287
        $message = $request->input("message");

        $response = $this->twilio->sendWhatsApp($to, $message);

        return response()->json([
            "status" => "enviado",
            "sid" => $response->sid
        ]);
    }

    public function receive(Request $request)
    {
        $from = $request->input("From");
        $body = $request->input("Body");

        // Exemplo: salvar no log
        \Log::info("Mensagem recebida de $from: $body");

        return response("Mensagem recebida", 200)
                ->header("Content-Type", "text/plain");
    }
}
