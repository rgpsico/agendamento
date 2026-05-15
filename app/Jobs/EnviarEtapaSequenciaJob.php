<?php

namespace App\Jobs;

use App\Models\AutomacaoEnvio;
use App\Models\AutomacaoEtapa;
use App\Models\Lead;
use App\Services\TwilioService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarEtapaSequenciaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60; // segundos entre tentativas

    public function __construct(
        public readonly int $envioId,
    ) {}

    public function handle(TwilioService $twilio): void
    {
        $envio = AutomacaoEnvio::with(['etapa', 'lead', 'sequencia'])->find($this->envioId);

        if (! $envio) {
            Log::warning("EnviarEtapaSequenciaJob: envio #{$this->envioId} não encontrado.");
            return;
        }

        // Se foi cancelado enquanto estava na fila, não envia
        if ($envio->status === 'cancelado') {
            return;
        }

        $etapa = $envio->etapa;
        $lead  = $envio->lead;

        try {
            // Gera a mensagem
            $mensagem = $this->gerarMensagem($etapa, $lead);

            // Envia pelo canal correto
            if ($envio->canal === 'whatsapp') {
                $this->enviarWhatsApp($twilio, $lead, $mensagem);
            } elseif ($envio->canal === 'email') {
                $this->enviarEmail($lead, $mensagem, $etapa->assunto_email);
            }

            // Marca como enviado
            $envio->update([
                'status'           => 'enviado',
                'mensagem_enviada' => $mensagem,
                'enviado_em'       => now(),
                'erro'             => null,
            ]);

            Log::info("AutomacaoIA: envio #{$envio->id} enviado com sucesso", [
                'lead'  => $lead->nome,
                'canal' => $envio->canal,
            ]);

        } catch (\Throwable $e) {
            $envio->update([
                'status' => 'falhou',
                'erro'   => $e->getMessage(),
            ]);

            Log::error("AutomacaoIA: falha no envio #{$envio->id}", [
                'erro'  => $e->getMessage(),
                'lead'  => $lead->id,
                'etapa' => $etapa->id,
            ]);

            // Re-lança para o Laravel retentar
            throw $e;
        }
    }

    // ─── Geração de mensagem ─────────────────────────────────────────────────

    private function gerarMensagem(AutomacaoEtapa $etapa, Lead $lead): string
    {
        if ($etapa->tipo_mensagem === 'template') {
            return $etapa->preencherTemplate($lead);
        }

        // Gera com IA (DeepSeek)
        return $this->gerarComIA($etapa, $lead);
    }

    private function gerarComIA(AutomacaoEtapa $etapa, Lead $lead): string
    {
        $primeiroNome = trim(explode(' ', trim($lead->nome))[0] ?? $lead->nome);

        $systemPrompt = "Você é um assistente de vendas especialista em pilates e academia. "
            . "Escreva mensagens curtas, naturais e personalizadas para WhatsApp ou e-mail. "
            . "Tom: amigável, profissional, sem emojis em excesso. "
            . "Responda APENAS com o texto da mensagem, sem explicações, sem aspas, sem prefixos como 'Mensagem:'.";

        $contextoLead = "Lead: {$lead->nome} (primeiro nome: {$primeiroNome})";
        if ($lead->interesse) {
            $contextoLead .= ", interesse: {$lead->interesse}";
        }
        if ($lead->bairro) {
            $contextoLead .= ", bairro: {$lead->bairro}";
        }
        if ($lead->empresa) {
            $contextoLead .= ", empresa: {$lead->empresa}";
        }
        $contextoLead .= ", temperatura: {$lead->temperatura}";
        $contextoLead .= ", pipeline: " . (Lead::$pipelineStatus[$lead->pipeline_status] ?? $lead->pipeline_status);

        $instrucao = $etapa->instrucao_ia
            ?? "Ofereça os serviços do estúdio de pilates e convide para uma aula experimental gratuita.";

        $userPrompt = "{$contextoLead}.\n\nInstrução: {$instrucao}\n\n"
            . "Escreva a mensagem agora (apenas o texto, pronto para enviar):";

        $response = Http::timeout(30)->withHeaders([
            'Authorization' => 'Bearer ' . env('DEEP_SEEK_API_KEY'),
            'Content-Type'  => 'application/json',
        ])->post('https://api.deepseek.com/v1/chat/completions', [
            'model'       => 'deepseek-chat',
            'messages'    => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user',   'content' => $userPrompt],
            ],
            'temperature' => 0.8,
            'max_tokens'  => 400,
        ]);

        if (! $response->successful()) {
            throw new \Exception('DeepSeek API erro: ' . $response->body());
        }

        $texto = $response->json('choices.0.message.content') ?? '';
        // Remove emojis fora do plano básico Unicode que quebram o banco
        return trim(preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $texto));
    }

    // ─── Envios ──────────────────────────────────────────────────────────────

    private function enviarWhatsApp(TwilioService $twilio, Lead $lead, string $mensagem): void
    {
        if (empty($lead->telefone)) {
            throw new \Exception("Lead #{$lead->id} não tem telefone cadastrado.");
        }

        $numero = preg_replace('/\D/', '', $lead->telefone);
        if (! str_starts_with($numero, '55')) {
            $numero = '55' . $numero;
        }

        $twilio->sendWhatsApp($numero, $mensagem);
    }

    private function enviarEmail(Lead $lead, string $mensagem, ?string $assunto): void
    {
        if (empty($lead->email)) {
            throw new \Exception("Lead #{$lead->id} não tem e-mail cadastrado.");
        }

        $assunto = $assunto ?: 'Uma mensagem especial para você';

        Mail::html(
            nl2br(e($mensagem)),
            function ($mail) use ($lead, $assunto) {
                $mail->to($lead->email, $lead->nome)
                     ->subject($assunto);
            }
        );
    }
}
