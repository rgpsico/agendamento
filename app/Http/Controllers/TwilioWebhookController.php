<?php

namespace App\Http\Controllers;

use App\Jobs\ResponderWhatsApp;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\WhatsappCanal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Mensagens de WhatsApp que chegam pelo Twilio (POST /api/webhook/twilio, assinatura conferida
 * pelo middleware twilio.assinatura).
 *
 * O número que recebeu (To) diz de qual empresa é a conversa (whatsapp_canais). A mensagem é gravada
 * na conversa do cliente com essa empresa e a resposta da IA é gerada na fila (ResponderWhatsApp).
 * Se a equipe assumiu a conversa no painel (controle humano), a IA não responde.
 */
class TwilioWebhookController extends Controller
{
    public function inbound(Request $request)
    {
        $de   = (string) $request->input('From');
        $para = (string) $request->input('To');
        $sid  = (string) $request->input('MessageSid');

        if (! str_starts_with(strtolower($de), 'whatsapp:')) {
            return $this->vazio(); // SMS etc.: não tratamos aqui
        }

        $canal = WhatsappCanal::where('numero', WhatsappCanal::normalizarNumero($para))->where('ativo', true)->first();
        if (! $canal) {
            Log::warning('WhatsApp: mensagem para número não cadastrado', ['to' => $para]);
            return $this->vazio();
        }

        // O Twilio reenvia se não receber resposta a tempo: não grave nem responda duas vezes.
        if ($sid !== '' && Message::where('twilio_sid', $sid)->exists()) {
            return $this->vazio();
        }

        $telefone = WhatsappCanal::normalizarNumero($de);
        $texto = trim((string) $request->input('Body'));
        $midias = (int) $request->input('NumMedia', 0);
        if ($midias > 0) {
            $texto = trim($texto . "\n[O cliente enviou " . ($midias === 1 ? 'um arquivo (foto, áudio ou documento)' : "{$midias} arquivos")
                . '. Diga que por aqui você só consegue ler mensagens de texto e peça para escrever.]');
        }
        if ($texto === '') {
            return $this->vazio();
        }

        $conversa = Conversation::where('empresa_id', $canal->empresa_id)
            ->where('canal', 'whatsapp')
            ->where('telefone', $telefone)
            ->latest('id')
            ->first();

        if (! $conversa) {
            $bot = $canal->botQueAtende();
            $conversa = Conversation::create([
                'empresa_id'        => $canal->empresa_id,
                'bot_id'            => $bot?->id,
                'mensagem'          => 'WhatsApp' . ($request->filled('ProfileName') ? ' — ' . mb_substr((string) $request->input('ProfileName'), 0, 80) : ''),
                'telefone'          => $telefone,
                'canal'             => 'whatsapp',
                'whatsapp_canal_id' => $canal->id,
                'human_controlled'  => false,
            ]);
        } elseif ((int) $conversa->whatsapp_canal_id !== (int) $canal->id) {
            $conversa->update(['whatsapp_canal_id' => $canal->id]);
        }

        $mensagem = $conversa->messages()->create([
            'from'       => 'user',
            'to'         => 'bot',
            'role'       => 'user',
            'body'       => $texto,
            'twilio_sid' => $sid ?: null,
        ]);
        $conversa->forceFill(['last_message_is_opened' => false])->save();

        if ($canal->ia_ativa && ! $conversa->human_controlled) {
            // pequeno atraso: se o cliente mandar várias mensagens seguidas, responde uma vez só
            ResponderWhatsApp::dispatch($conversa->id, $mensagem->id)->delay(now()->addSeconds(4));
        }

        return $this->vazio();
    }

    /** Resposta vazia em TwiML: a resposta ao cliente sai pela API (fila), não por aqui. */
    private function vazio()
    {
        return response('<?xml version="1.0" encoding="UTF-8"?><Response></Response>', 200)
            ->header('Content-Type', 'text/xml');
    }
}
