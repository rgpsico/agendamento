<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Message;
use Twilio\Rest\Client as TwilioClient;
use GuzzleHttp\Client as Guzzle;

class ProcessIncomingMessage implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $messageId;

    public function __construct($messageId) {
        $this->messageId = $messageId;
    }

    public function handle() {
        $msg = Message::find($this->messageId);
        if (!$msg) return;

        // 1) Recupera histórico recente
        $history = Message::where('from', $msg->from)
                    ->orderBy('created_at','desc')
                    ->limit(8)
                    ->get()
                    ->reverse();

        $messages = [];
        // system prompt (persona)
        $messages[] = [
            'role' => 'system',
            'content' => "Você é 'Lucas'... (cole o SYSTEM PROMPT descrito anteriormente aqui)"
        ];

        foreach ($history as $h) {
            $messages[] = [
                'role' => $h->role === 'user' ? 'user' : 'assistant',
                'content' => $h->body
            ];
        }

        // 2) Chamada ao modelo (OpenAI Chat Completions)
        $guzzle = new Guzzle();
        $resp = $guzzle->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer '.env('OPENAI_API_KEY'),
                'Content-Type'  => 'application/json'
            ],
            'json' => [
                'model' => env('OPENAI_MODEL', 'gpt-4o'), // substitua pelo modelo que tiver
                'messages' => $messages,
                'temperature' => 0.6,
                'max_tokens' => 512
            ]
        ]);

        $body = json_decode($resp->getBody(), true);
        $reply = $body['choices'][0]['message']['content'] ?? "Desculpa, não entendi. Quer que eu transfira pro suporte?";

        // 3) "Human feel" — opcional: esperar um pouco antes de enviar (simular pensamento)
        sleep(rand(1,3)); // melhor ainda: adicionar delay configurável em fila para não bloquear

        // 4) Envia via Twilio
        $tw = new TwilioClient(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        $send = $tw->messages->create(
            $msg->from,
            [
                'from' => 'whatsapp:'.env('TWILIO_WHATSAPP_FROM'),
                'body' => $reply
            ]
        );

        // 5) Salva resposta
        Message::create([
            'from' => $msg->to,        // invertido (sua conta -> usuário)
            'to' => $msg->from,
            'role' => 'assistant',
            'body' => $reply,
            'twilio_sid' => $send->sid ?? null
        ]);
    }
}
