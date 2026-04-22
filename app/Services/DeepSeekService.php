<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\Bot;
use App\Models\Conversation;
use App\Models\Disponibilidade;
use App\Models\Servicos;
use App\Models\TokenUsage;
use Carbon\Carbon;
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
            'max_tokens' => max(1, min(8192, (int) ($bot->token_deepseek ?: 1000))),
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


    public function getDeepSeekResponseWithPrompt(Bot $bot, string $question, Conversation $conversation, int $empresa_id, int $contextMessages = 10): string
    {
        // 1. System prompt enxuto — dados vêm pelas tools
        $systemPrompt  = $bot->prompt . "\n";
        $systemPrompt .= "Tom: " . ($bot->tom ?? 'amigável') . ". Segmento: " . ($bot->segmento ?? '') . ".\n";
        $systemPrompt .= "Você tem acesso a ferramentas para consultar serviços e horários em tempo real. Use-as sempre que o cliente perguntar sobre serviços ou disponibilidade.\n";
        $systemPrompt .= "Sempre responda em português.";

        // 2. Histórico da conversa
        $history = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->take($contextMessages)
            ->get();

        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        foreach ($history as $msg) {
            $role = ($msg->role === 'assistant') ? 'assistant' : 'user';
            if (!empty($msg->body)) {
                $messages[] = ['role' => $role, 'content' => $msg->body];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $question];

        // 3. Loop de function calling
        $tools        = $this->definirTools();
        $maxIteracoes = 5;
        $totalTokens  = 0;
        $promptTokens = 0;
        $completionTokens = 0;

        for ($i = 0; $i < $maxIteracoes; $i++) {
            $data   = $this->callDeepSeekApiWithTools($messages, $tools, $bot);
            $choice = $data['choices'][0];

            $usage             = $data['usage'] ?? [];
            $totalTokens      += $usage['total_tokens'] ?? 0;
            $promptTokens     += $usage['prompt_tokens'] ?? 0;
            $completionTokens += $usage['completion_tokens'] ?? 0;

            if (($choice['finish_reason'] ?? '') === 'tool_calls') {
                $assistantMsg = $choice['message'];
                $messages[]   = $assistantMsg;

                foreach ($assistantMsg['tool_calls'] as $toolCall) {
                    $nome      = $toolCall['function']['name'];
                    $args      = json_decode($toolCall['function']['arguments'], true) ?? [];
                    $resultado = $this->executarTool($nome, $args, $bot);

                    $messages[] = [
                        'role'         => 'tool',
                        'tool_call_id' => $toolCall['id'],
                        'content'      => json_encode($resultado, JSON_UNESCAPED_UNICODE),
                    ];
                }

                continue;
            }

            // Resposta final em texto
            $responseText  = $choice['message']['content'] ?? 'Sem resposta';
            $cleanResponse = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $responseText);

            $bot->logs()->create([
                'bot_id'            => $bot->id,
                'mensagem_usuario'  => $question,
                'resposta_bot'      => $cleanResponse,
                'tokens_usados'     => $totalTokens,
                'prompt_tokens'     => $promptTokens,
                'completion_tokens' => $completionTokens,
            ]);

            TokenUsage::registrarUso($bot->id, $empresa_id, $totalTokens, $promptTokens, $completionTokens);

            return $cleanResponse;
        }

        return 'Desculpe, não consegui processar sua solicitação no momento.';
    }

    private function definirTools(): array
    {
        return [
            [
                'type' => 'function',
                'function' => [
                    'name'        => 'listar_servicos',
                    'description' => 'Lista todos os serviços que este bot gerencia, com id, título, descrição, preço e duração.',
                    'parameters'  => [
                        'type'       => 'object',
                        'properties' => new \stdClass(),
                        'required'   => [],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name'        => 'verificar_disponibilidade',
                    'description' => 'Retorna os horários disponíveis (ainda não agendados) para um serviço em uma data específica.',
                    'parameters'  => [
                        'type'       => 'object',
                        'properties' => [
                            'servico_id' => [
                                'type'        => 'integer',
                                'description' => 'ID do serviço (obtido em listar_servicos)',
                            ],
                            'data' => [
                                'type'        => 'string',
                                'description' => 'Data desejada no formato YYYY-MM-DD',
                            ],
                        ],
                        'required' => ['servico_id', 'data'],
                    ],
                ],
            ],
        ];
    }

    private function executarTool(string $nome, array $args, Bot $bot): array
    {
        return match ($nome) {
            'listar_servicos'          => $this->toolListarServicos($bot),
            'verificar_disponibilidade' => $this->toolVerificarDisponibilidade(
                (int) $args['servico_id'],
                $args['data'],
                $bot
            ),
            default => ['erro' => "Ferramenta desconhecida: {$nome}"],
        };
    }

    private function toolListarServicos(Bot $bot): array
    {
        $servicos = $bot->services()->get();

        if ($servicos->isEmpty()) {
            return ['servicos' => [], 'mensagem' => 'Nenhum serviço cadastrado para este bot.'];
        }

        return [
            'servicos' => $servicos->map(fn($s) => [
                'id'               => $s->id,
                'titulo'           => $s->titulo,
                'descricao'        => $s->descricao,
                'preco'            => $s->preco,
                'duracao_minutos'  => $s->tempo_de_aula,
                'tipo_agendamento' => $s->tipo_agendamento,
            ])->values()->toArray(),
        ];
    }

    private function toolVerificarDisponibilidade(int $servicoId, string $data, Bot $bot): array
    {
        $servico = $bot->services()->where('servicos.id', $servicoId)->first();

        if (!$servico) {
            return ['erro' => 'Serviço não encontrado ou não pertence a este bot.'];
        }

        // isoWeekday(): 1=Segunda ... 7=Domingo — igual ao id da tabela dias_da_semana
        $carbon      = Carbon::parse($data);
        $diaSemanaId = $carbon->isoWeekday();

        $disponibilidades = Disponibilidade::where('id_servico', $servicoId)
            ->where('id_dia', $diaSemanaId)
            ->get();

        if ($disponibilidades->isEmpty()) {
            return [
                'servico'              => $servico->titulo,
                'data'                 => $carbon->format('d/m/Y'),
                'dia_semana'           => $carbon->locale('pt_BR')->dayName,
                'horarios_disponiveis' => [],
                'mensagem'             => 'Sem horários cadastrados para este dia.',
            ];
        }

        $ocupados = Agendamento::where('servico_id', $servicoId)
            ->where('data_da_aula', $carbon->format('Y-m-d'))
            ->pluck('horario')
            ->map(fn($h) => Carbon::parse($h)->format('H:i'))
            ->toArray();

        $disponiveis = $disponibilidades
            ->filter(fn($d) => !in_array(Carbon::parse($d->hora_inicio)->format('H:i'), $ocupados))
            ->map(fn($d) => [
                'inicio' => Carbon::parse($d->hora_inicio)->format('H:i'),
                'fim'    => Carbon::parse($d->hora_fim)->format('H:i'),
            ])
            ->values()
            ->toArray();

        return [
            'servico'              => $servico->titulo,
            'data'                 => $carbon->format('d/m/Y'),
            'dia_semana'           => $carbon->locale('pt_BR')->dayName,
            'horarios_disponiveis' => $disponiveis,
            'total_disponivel'     => count($disponiveis),
        ];
    }

    private function callDeepSeekApiWithTools(array $messages, array $tools, Bot $bot): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('DEEP_SEEK_API_KEY'),
            'Content-Type'  => 'application/json',
        ])->post('https://api.deepseek.com/v1/chat/completions', [
            'model'       => 'deepseek-chat',
            'messages'    => $messages,
            'tools'       => $tools,
            'tool_choice' => 'auto',
            'temperature' => 0.7,
            'max_tokens'  => max(1, min(8192, (int) ($bot->token_deepseek ?: 2000))),
        ]);

        if (!$response->successful()) {
            throw new \Exception('Erro DeepSeek API: ' . $response->body());
        }

        return $response->json();
    }

    private function callDeepSeekApi(string $systemPrompt, string $conversationContext, Bot $bot): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('DEEP_SEEK_API_KEY'),
            'Content-Type'  => 'application/json',
        ])->post('https://api.deepseek.com/v1/chat/completions', [
            'model'    => 'deepseek-chat',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $conversationContext],
            ],
            'temperature' => 0.7,
            'max_tokens'  => max(1, min(8192, (int) ($bot->token_deepseek ?: 1000))),
        ]);

        if (!$response->successful()) {
            throw new \Exception('Erro ao chamar DeepSeek API: ' . $response->body());
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
