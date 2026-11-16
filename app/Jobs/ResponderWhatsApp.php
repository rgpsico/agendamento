<?php

namespace App\Jobs;

use App\Models\Conversation;
use App\Models\Message;
use App\Services\DeepSeekService;
use App\Services\WhatsApp\TwilioWhatsApp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Gera a resposta da IA para a mensagem que chegou no WhatsApp e envia pelo Twilio.
 * Roda na fila: a IA pode levar vários segundos (ferramentas), e o webhook do Twilio precisa responder rápido.
 */
class ResponderWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;       // não repetir: reenviaria a resposta ao cliente
    public int $timeout = 120;

    public function __construct(public int $conversationId, public int $messageId)
    {
    }

    public function handle(DeepSeekService $ia, TwilioWhatsApp $whatsapp): void
    {
        $conversa = Conversation::with('whatsappCanal')->find($this->conversationId);
        $mensagem = Message::find($this->messageId);
        $canal = $conversa?->whatsappCanal;

        if (! $conversa || ! $mensagem || ! $canal || ! $canal->ativo || ! $canal->ia_ativa || $conversa->human_controlled) {
            return; // atendimento humano, IA desligada ou canal removido: não responde
        }

        // Cliente mandou várias mensagens seguidas: responde só à última (com todas no histórico).
        $ultimaDoCliente = $conversa->messages()->where('role', 'user')->max('id');
        if ((int) $ultimaDoCliente !== (int) $mensagem->id) {
            return;
        }

        $bot = $canal->botQueAtende();
        if (! $bot) {
            Log::warning('WhatsApp: empresa sem bot ativo', ['canal' => $canal->id, 'conversa' => $conversa->id]);
            return;
        }

        // Uma resposta por vez por conversa.
        $trava = Cache::lock('whatsapp:conversa:' . $conversa->id, 120);
        if (! $trava->get()) {
            return;
        }

        try {
            $contexto = "O cliente está falando pelo WhatsApp, do telefone {$conversa->telefone}. "
                . "Use esse telefone em `buscar_aluno_por_telefone` sem pedir o número de novo. "
                . "Escreva mensagens curtas, como numa conversa de WhatsApp: sem tabelas e sem títulos com #; "
                . "use no máximo *negrito* e listas simples com hífen.";

            $resultado = $ia->getDeepSeekResponseWithPrompt($bot, (string) $mensagem->body, $conversa, (int) $conversa->empresa_id, 10, $contexto);
            $resposta = trim((string) ($resultado['reply'] ?? ''));

            if ($resposta === '') {
                return;
            }

            // Enquanto a IA pensava, alguém da equipe pode ter assumido a conversa.
            if ($conversa->fresh()->human_controlled) {
                return;
            }

            $sids = $whatsapp->enviar($canal, (string) $conversa->telefone, $resposta);

            $conversa->messages()->create([
                'from'       => 'bot',
                'to'         => 'user',
                'role'       => 'assistant',
                'body'       => $resposta,
                'twilio_sid' => $sids[0] ?? null,
            ]);
            $conversa->touch();
        } catch (\Throwable $e) {
            Log::error('WhatsApp: falha ao responder', ['conversa' => $conversa->id, 'erro' => $e->getMessage()]);
        } finally {
            $trava->release();
        }
    }
}
