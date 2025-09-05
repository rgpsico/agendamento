<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Twilio\Rest\Client;

class TwilioWebhookController extends Controller
{
    public function inbound(Request $request)
    {
        $from = $request->input('From');   // número do usuário
        $to   = $request->input('To');     // número do bot
        $body = trim($request->input('Body') ?? ''); // mensagem do usuário

        // Chama DeepSeek para gerar resposta
        $reply = $this->getDeepSeekResponse($body);

        // Envia resposta de volta via Twilio WhatsApp
        $twilioSid   = env('TWILIO_SID');
        $twilioToken = env('TWILIO_TOKEN');
        $twilioFrom  = $to;

        $client = new Client($twilioSid, $twilioToken);
        $client->messages->create(
            $from,
            [
                'from' => $twilioFrom,
                'body' => $reply
            ]
        );

        return response('OK', 200);
    }

    private function getDeepSeekResponse(string $question): string
{
    // Força o modelo a responder em português
    $prompt = "Responda em português: " . $question;

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('DEEP_SEEK_API_KEY'),
        'Content-Type' => 'application/json',
    ])->post('https://api.deepseek.com/v1/chat/completions', [
        'model' => 'deepseek-chat',
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.7,
        'max_tokens' => 150
    ]);

    if ($response->successful()) {
        return $response->json()['choices'][0]['message']['content'] ?? 'Sem resposta';
    } else {
        return 'Erro: ' . $response->body();
    }
}

}
