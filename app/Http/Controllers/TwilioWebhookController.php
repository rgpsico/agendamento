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
        // Validação da assinatura (recomendada). Twilio passa X-Twilio-Signature. :contentReference[oaicite:3]{index=3}
        $validator = new RequestValidator(env('TWILIO_AUTH_TOKEN'));
        $signature = $request->header('X-Twilio-Signature');
        $url = $request->fullUrl(); // importante: use a URL exata cadastrada no Twilio
        $params = $request->all();

        if (! $validator->validate($signature, $url, $params)) {
            \Log::warning('Invalid Twilio signature', ['url'=>$url,'sig'=>$signature,'params'=>$params]);
            return response('Forbidden', 403);
        }

        $from = $request->input('From');   // ex: whatsapp:+55...
        $to   = $request->input('To');
        $body = trim($request->input('Body') ?? '');

        // Salva a mensagem no DB
        $msg = Message::create([
            'from' => $from,
            'to' => $to,
            'role' => 'user',
            'body' => $body,
            'twilio_sid' => $request->input('MessageSid')
        ]);

        // Enfileira Job assíncrono para processar e responder (não bloquear webhook)
        ProcessIncomingMessage::dispatch($msg->id);

        return response('OK', 200);
    }
}
