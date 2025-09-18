<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\Bot;
use App\Models\Conversation;
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
    public function gerarImagem(
        string $prompt,
        int $width = 512,
        int $height = 512,
        string $quality = 'standard',
        string $responseFormat = 'url'
    ): array {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post("{$this->baseUrl}/images/generations", [
                    'model' => 'deepseek-image', // Verificar modelo correto na documentação
                    'prompt' => $this->sanitizePrompt($prompt),
                    'size' => "{$width}x{$height}",
                    'quality' => $quality,
                    'response_format' => $responseFormat,
                    'n' => 1,
                ]);

            if (!$response->successful()) {
                $this->logError('DeepSeek API Error', $response);
                throw new \Exception($this->getErrorMessage($response));
            }

            $data = $response->json();

            if (!isset($data['data']) || empty($data['data'])) {
                throw new \Exception('Resposta inválida da API DeepSeek');
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('Erro ao gerar imagem DeepSeek', [
                'message' => $e->getMessage(),
                'prompt' => $prompt,
                'dimensions' => "{$width}x{$height}",
            ]);
            throw $e;
        }
    }


    public function getDeepSeekResponseWithPrompt(Bot $bot, string $question, Conversation $conversation, int $empresa_id, int $contextMessages = 3): string
    {
        // 1️⃣ Pega últimas mensagens da conversa para contexto
        $messages = $conversation->messages()
            ->latest()
            ->take($contextMessages)
            ->get()
            ->reverse();

        $conversationContext = "";
        foreach ($messages as $msg) {
            $author = $msg->tipo === 'user' ? 'Cliente' : 'Bot';
            $conversationContext .= "{$author}: {$msg->mensagem}\n";
        }

        // Adiciona a pergunta atual do usuário
        $conversationContext .= "Cliente: {$question}\n";



        // 2️⃣ Carrega os serviços do bot
        $services = Servicos::with('disponibilidades.diaDaSemana')
            ->where('empresa_id', $empresa_id)
            ->get();


        $servicesText = "";
        if ($services->isNotEmpty()) {
            $servicesText .= "Serviços disponíveis e horários desta semana:\n";

            foreach ($services as $service) {
                $servicesText .= "- {$service->titulo}: {$service->descricao}. Preço: R$ {$service->preco}. Duração: {$service->tempo_de_aula} minutos. Tipo de agendamento: {$service->tipo_agendamento}\n";

                // Horários
                if ($service->disponibilidades->isNotEmpty()) {
                    $servicesText .= "  Horários:\n";
                    foreach ($service->disponibilidades as $disp) {
                        $dia = $disp->diaDaSemana->nome ?? $disp->id_dia;
                        $servicesText .= "    - {$dia}: {$disp->hora_inicio} às {$disp->hora_fim}\n";
                    }
                }
            }
        }

        // 3️⃣ Monta o system prompt usando o prompt/missão do bot
        $systemPrompt = $bot->prompt;;
        $systemPrompt .= "\nTom: " . ($bot->tom ?? 'informal') . ". Segmento: " . ($bot->segmento ?? 'estético') . ".\n";
        $systemPrompt .= $servicesText;


        if (preg_match('/horário|hora|disponível|quando/i', $question)) {
            $systemPrompt = "Responda de forma curta e natural. Apenas informe os horários disponíveis para o cliente.\n";
            $systemPrompt .= $this->montarPromptHorarios($empresa_id);
        }


        // 4️⃣ Chamada à API DeepSeek
        $data = $this->callDeepSeekApi($systemPrompt, $conversationContext, $bot);


        $responseText = $data['choices'][0]['message']['content'] ?? 'Sem resposta';
        $cleanResponse = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $responseText);

        // 6️⃣ Salva log e uso de tokens
        $usage = $data['usage'] ?? [];
        $tokensUsados = $usage['total_tokens'] ?? 0;
        $promptTokens = $usage['prompt_tokens'] ?? 0;
        $completionTokens = $usage['completion_tokens'] ?? 0;


        $bot->logs()->create(['bot_id' => $bot->id, 'mensagem_usuario' => $question, 'resposta_bot' => $cleanResponse, 'tokens_usados' => $tokensUsados, 'prompt_tokens' => $promptTokens, 'completion_tokens' => $completionTokens]);

        TokenUsage::registrarUso($bot->id, $empresa_id, $tokensUsados, $promptTokens, $completionTokens);


        // 7️⃣ Retorna a resposta limpa
        return $cleanResponse;
    }

    private function montarPromptHorarios(int $empresa_id, $serviceText = null): string
    {
        $services = Servicos::with('disponibilidades.diaDaSemana')
            ->where('empresa_id', $empresa_id)
            ->get();

        $horariosText = "Horários atualizados disponíveis:\n";

        foreach ($services as $service) {
            if ($service->disponibilidades->isNotEmpty()) {
                $horariosText .= "- {$service->titulo}:\n";

                foreach ($service->disponibilidades as $disp) {
                    $diaSemana = $disp->diaDaSemana->nome ?? $disp->id_dia;

                    // Mantemos a data no formato Y-m-d para consulta
                    $dataParaBanco = $disp->data
                        ? \Carbon\Carbon::parse($disp->data)->format('Y-m-d')
                        : null;

                    // Verifica se já existe agendamento nesse horário
                    $agendamentoExistente = Agendamento::where('servico_id', $service->id)
                        ->where('data_da_aula', $dataParaBanco)
                        ->where('horario', $disp->hora_inicio)
                        ->exists();



                    if ($agendamentoExistente) {
                        continue; // pula esse horário
                    }

                    // Formata data para exibição ao usuário
                    $dataExibir = $dataParaBanco
                        ? \Carbon\Carbon::parse($dataParaBanco)->format('d/m/Y')
                        : '';

                    $horariosText .= "   {$diaSemana}" . ($dataExibir ? " ({$dataExibir})" : "") . ": {$disp->hora_inicio} às {$disp->hora_fim}\n";
                }
            }
        }

        return $horariosText;
    }




    private function callDeepSeekApi(string $systemPrompt, string $conversationContext, Bot $bot)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('DEEP_SEEK_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/v1/chat/completions', [
            'model' => 'deepseek-chat',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $conversationContext],
            ],
            'temperature' => 0.7,
            'max_tokens' => (int) $bot->token_deepseek,
        ]);

        if (!$response->successful()) {
            throw new \Exception("Erro ao chamar DeepSeek API: " . $response->body());
        }

        return $response->json();
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
