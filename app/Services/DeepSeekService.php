<?php

namespace App\Services;

use App\Models\Bot;
use App\Models\Servicos;
use Illuminate\Support\Facades\Http;

class DeepSeekService
{
    /**
     * Get a response from the DeepSeek API using the bot's configuration, including service details.
     *
     * @param Bot $bot The bot instance with configuration details
     * @param string $question The user's question
     * @return string The response from DeepSeek or an error message
     */
    public function getDeepSeekResponse(Bot $bot, string $question, int $empresa_id): string
    {
        // Carrega os serviços associados ao bot com detalhes completos
        $services = Servicos::where('empresa_id',$empresa_id)->get(); // Ajuste 'servico' se o relacionamento for diferente
     
        // Constrói o system prompt com detalhes dos serviços
        $systemPrompt = "Você é um assistente especializado em serviços de praia, como aulas de surf e bodyboard. ";
        $systemPrompt .= "Seu tom é". $bot->tom ?? 'amigável e motivador'.", e o segmento é ".$bot->segmento ?? 'esportes aquáticos na praia';
        $systemPrompt .= "Sempre responda em português de forma clara e detalhada. ";

        if ($services->isNotEmpty()) {
            $systemPrompt .= "Você gerencia os seguintes serviços:\n";
            foreach ($services as $service) {
                $systemPrompt .= "- **{$service->titulo}**: {$service->descricao}. Preço: R$ {$service->preco}. Duração: {$service->tempo_de_aula} minutos. Tipo de agendamento: {$service->tipo_agendamento}.\n";
            }
            $systemPrompt .= "Quando o usuário perguntar sobre um serviço, forneça detalhes como descrição, preço, duração e como agendar. Se a pergunta for geral, sugira serviços relevantes.";
        } else {
            $systemPrompt .= "Você não tem serviços cadastrados ainda, mas pode responder perguntas gerais sobre surf e bodyboard.";
        }

        // Prompt do usuário, forçando resposta em português
        $userPrompt = "Responda em português: " . $question;

        // Faz a chamada à API com system e user messages
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . (env('DEEP_SEEK_API_KEY')),
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/v1/chat/completions', [
            'model' => 'deepseek-chat',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt],
            ],
            'temperature' => 0.7,
            'max_tokens' =>  (int) $bot->token_deepseek, // Aumentado para respostas mais detalhadas
        ]);

        // Loga a interação (opcional, usando BotLog)
            $cleanResponse = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $response->successful() );

        $responseText = $response->successful()
            ? ($response->json()['choices'][0]['message']['content'] ?? 'Sem resposta')
            : 'Erro';

        // Remove emojis e caracteres que causam problema no utf8mb3
        $cleanResponse = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $responseText);

        // Salva o log no banco
        $bot->logs()->create([
            'bot_id' => $bot->id,
            'mensagem_usuario' => $question,
            'resposta_bot' => $cleanResponse,
            'tokens_usados' => 0, // ou use o valor real se disponível
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'] ?? 'Sem resposta';
        }

        return 'Erro: ' . $response->body();
    }

    public function generateResponse(array $input)
    {
        $message = $input['message'];
        $context = $input['context'];

        // Example: Construct a prompt for the AI
        $prompt = "Você é um assistente de agendamento. O usuário disse: '$message'. Contexto: usuário está agendando com o professor ID {$context['professor_id']}. Disponibilize informações sobre serviços: " . json_encode($context['services']) . ". Responda de forma útil e amigável.";

        // Call your AI service (e.g., via HTTP request to an API)
  $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . (env('DEEP_SEEK_API_KEY')),
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/v1/chat/completions', [
            'model' => 'deepseek-chat',
            'messages' => [
                ['role' => 'system', 'content' => $prompt],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens' =>  100, // Aumentado para respostas mais detalhadas
        ]);


        return $response->json()['response'] ?? 'Desculpe, não entendi. Pode explicar melhor?';
    }

}