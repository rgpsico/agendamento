<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Jobs\ProcessIncomingMessage;
use Twilio\Security\RequestValidator;

class TwilioWebhookController extends Controller
{
    public function inbound(Request $request)
    {
        // Loga todos os dados recebidos do Twilio para inspeção
        $allParams = $request->all();
        \Log::info('Webhook recebido do Twilio', [
            'url' => $request->fullUrl(),
            'headers' => $request->header(),
            'params' => $allParams,
        ]);

        // Validação da assinatura (recomendada). Twilio passa X-Twilio-Signature.
        // $validator = new RequestValidator(env('TWILIO_TOKEN'));
        $signature = $request->header('X-Twilio-Signature');

        $url = $request->fullUrl(); // importante: use a URL exata cadastrada no Twilio
        $params = $request->all();

        // if (!$validator->validate($signature, $url, $params)) {
        //     \Log::warning('Invalid Twilio signature', ['url' => $url, 'sig' => $signature, 'params' => $params]);
        //     return response('Forbidden', 403);
        // }

        // Extrai os dados específicos da mensagem
        $from = $request->input('From');   // ex: whatsapp:+55...
        $to   = $request->input('To');
        $body = trim($request->input('Body') ?? '');

        // Loga os dados específicos da mensagem
        \Log::info('Dados da mensagem recebida', [
            'from' => $from,
            'to' => $to,
            'body' => $body,
            'message_sid' => $request->input('MessageSid'),
        ]);

    
       
        // Salva a mensagem no DB
        Message::create([
            'from' => $request->input('From'),
            'to' => $request->input('To'),
            'role' => 'user', // Defina um valor padrão, pois o Twilio não envia isso
            'body' => $request->input('Body'),
            'twilio_sid' => $request->input('MessageSid'),
        ]);
        // Enfileira Job assíncrono para processar e responder
       // ProcessIncomingMessage::dispatch($msg->id);
   
        return response('OK', 200);
    }
}