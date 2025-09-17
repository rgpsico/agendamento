<?php

namespace App\Services;

use App\Models\Bot;
use App\Models\Servicos;
use App\Models\TokenUsage;
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
        // 1. Carrega os serviços associados ao bot
        $services = Servicos::where('empresa_id', $empresa_id)->get();

        // 2. Constrói o system prompt
        $systemPrompt = "Você é um assistente especializado em serviços de praia, como aulas de surf e bodyboard. ";
        $systemPrompt .= "Seu tom é " . ($bot->tom ?? 'amigável e motivador') . ", e o segmento é " . ($bot->segmento ?? 'esportes aquáticos na praia') . ". ";
        $systemPrompt .= "Sempre responda em português de forma clara e detalhada. ";

        if ($services->isNotEmpty()) {
            $systemPrompt .= "Você gerencia os seguintes serviços:\n";
            foreach ($services as $service) {
                $systemPrompt .= "- **{$service->titulo}**: {$service->descricao}. Preço: R$ {$service->preco}. Duração: {$service->tempo_de_aula} minutos. Tipo de agendamento: {$service->tipo_agendamento}.\n";
            }
        }

        $userPrompt = "Responda em português: " . $question;

        // 3. Chamada à API DeepSeek
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('DEEP_SEEK_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/v1/chat/completions', [
            'model' => 'deepseek-chat',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => (int) $bot->token_deepseek,
        ]);

        // 4. Verifica se a resposta foi bem-sucedida
        if (!$response->successful()) {
            return 'Erro: ' . $response->body();
        }

        $data = $response->json();

        $responseText = $data['choices'][0]['message']['content'] ?? 'Sem resposta';

        // 5. Remove emojis e caracteres que podem quebrar o banco
        $cleanResponse = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $responseText);

        // 6. Pega os tokens usados
        $usage = $data['usage'] ?? [];

        $tokensUsados = $usage['total_tokens'] ?? 0;
        $promptTokens = $usage['prompt_tokens'] ?? 0;
        $completionTokens = $usage['completion_tokens'] ?? 0;

        // 7. Salva o log no banco
        $bot->logs()->create([
            'bot_id' => $bot->id,
            'mensagem_usuario' => $question,
            'resposta_bot' => $cleanResponse,
            'tokens_usados' => $tokensUsados,
            'prompt_tokens' => $promptTokens,
            'completion_tokens' => $completionTokens,
        ]);

        TokenUsage::create([
            'bot_id' => $bot->id,
            'empresa_id' => $empresa_id,
            'tokens_usados' => $tokensUsados,
            'valor_cobrado' => $tokensUsados * 0.0001, // ex: valor por token
            'prompt_tokens' => $promptTokens,          // se você quiser armazenar separado
            'completion_tokens' => $completionTokens,  // se você quiser armazenar separado
        ]);

        // 8. Retorna a resposta limpa
        return $cleanResponse;
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
