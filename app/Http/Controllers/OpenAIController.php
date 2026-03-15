<?php

namespace App\Http\Controllers;

use App\Models\Disponibilidade;
use App\Models\Agendamento;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class OpenAIController extends Controller
{


    private function getChatContext(): array
    {
        return config('chat.base_messages', []);
    }


    public function testConnection(Request $request)
    {
        $apiKey = env('OPENAI_API_KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'OpenAI API key not configured'], 500);
        }

        $response = Http::withToken($apiKey)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => 'Diga olá'],
                ],
            ]);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json([
            'error' => 'Failed to connect to OpenAI',
            'response' => $response->json(),
        ], $response->status());
    }


    public function explicarSistema(Request $request): JsonResponse
    {
        $apiKey = env('OPENAI_API_KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'API Key não configurada'], 500);
        }

        // 1) Defina aqui, em linguagem natural, tudo o que seu sistema faz
        $descricaoSistema = <<<DESC
                            Você é o assistente virtual de um sistema de agendamento de aulas.  
                            Este sistema permite aos usuários:
                            1. Consultar horários disponíveis de professores para diferentes serviços (modalidades).  
                            2. Agendar uma aula escolhendo data, horário, professor e modalidade.  
                            3. Cancelar ou reagendar aulas já marcadas.  
                            4. Listar serviços disponíveis e informações sobre cada modalidade (valor, duração).  
                            5. Notificar automaticamente sobre confirmações e lembretes de aula.  
                            6. Gerenciar perfil de aluno, seus agendamentos e histórico de aulas.  
                            7. Integrar via chat: o usuário faz perguntas livres (“Quais horários o professor X tem terça-feira?”)  
                            e recebe respostas em linguagem natural, com opção de botões para agendar.  
                            DESC;

        // 2) Envia para o OpenAI gerar o texto explicativo
        $response = Http::withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
            'model'    => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => $descricaoSistema],
                ['role' => 'user',   'content' => 'Pode me explicar o que este sistema faz?'],
            ],
            'max_tokens' => 300,
        ]);

        $data = $response->json();

        if (!isset($data['choices'][0]['message']['content'])) {
            return response()->json([
                'error'   => 'Não foi possível gerar a explicação',
                'details' => $data
            ], 500);
        }

        $texto = trim($data['choices'][0]['message']['content']);

        return response()->json([
            'descricao' => $texto
        ]);
    }


    /**
     * Recebe a pergunta, junta com o contexto, chama o OpenAI e retorna a resposta.
     */
    public function chatComContexto(Request $request): JsonResponse
    {
        $pergunta = $request->input('mensagem');
        $apiKey   = env('OPENAI_API_KEY');

        if (!$apiKey) {
            return response()->json(['error' => 'API Key não configurada'], 500);
        }

        // 1) monta o array de mensagens: contexto + pergunta do usuário
        $messages = array_merge(
            $this->getChatContext(),
            [
                ['role' => 'user', 'content' => $pergunta],
            ]
        );

        // 2) chama o OpenAI
        $response = Http::withToken($apiKey)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model'    => 'gpt-3.5-turbo',
                'messages' => $messages,
                'max_tokens' => 200,
            ]);

        $data = $response->json();

        // 3) valida e retorna
        if (!isset($data['choices'][0]['message']['content'])) {
            return response()->json([
                'error'   => 'Erro ao gerar resposta',
                'details' => $data,
            ], 500);
        }

        return response()->json([
            'resposta' => trim($data['choices'][0]['message']['content'])
        ]);
    }

    public function chatComCliente(Request $request): JsonResponse
    {
        // 1) dados do request
        $professorId = $request->input('professor_id');
        $servicoId   = $request->input('servico_id');
        $pergunta    = $request->input('mensagem');

        $apiKey = env('OPENAI_API_KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'API Key não configurada'], 500);
        }

        // 2) schema de funções
        $functions = $this->getOpenAIFunctions();

        // 3) primeira chamada ao GPT
        $resp1 = Http::withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
            'model'         => 'gpt-3.5-turbo',
            'messages'      => [['role' => 'user', 'content' => $pergunta]],
            'functions'     => $functions,
            'function_call' => 'auto',
        ]);
        $data1 = $resp1->json();
        $msg1  = $data1['choices'][0]['message'] ?? null;
        if (!$msg1) {
            return response()->json(['erro' => 'Resposta inválida da OpenAI', 'resposta' => $data1], 500);
        }

        // 4) se pediu consultarHorarios
        if (isset($msg1['function_call']) && $msg1['function_call']['name'] === 'consultarHorarios') {
            $args = json_decode($msg1['function_call']['arguments'], true);
            if (json_last_error()) {
                return response()->json(['erro' => 'Argumentos JSON inválidos'], 500);
            }

            // injeta IDs
            $args['id_professor'] = $professorId;
            $args['id_servico']   = $servicoId;

            // 5) consulta local
            $resFunc      = $this->consultarHorarios(new Request($args));
            $conteudoFunc = json_decode($resFunc->getContent(), true);
            // Conteúdo formatado vem em $conteudoFunc['mensagem']
            $textoHorario = $conteudoFunc['mensagem'] ?? '';

            // 6) resposta final do GPT
            $resp2 = Http::withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
                'model'    => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $pergunta],
                    $msg1,
                    ['role' => 'function', 'name' => 'consultarHorarios', 'content' => $conteudoFunc['mensagem']],
                ],
            ]);
            $data2 = $resp2->json();
            $gptText = $data2['choices'][0]['message']['content'] ?? $textoHorario;

            // 7) monta botão de agendamento
            // supondo que você queira enviar a primeira opção de horário:
            $primeiro = $conteudoFunc['dados_brutos'][0] ?? null;
            $action   = $primeiro ? [
                'type' => 'book',
                'data' => $primeiro['data'],
                'hora_inicio' => $primeiro['hora_inicio'],
                'hora_fim' => $primeiro['hora_fim'],
                'id_professor' => $professorId,
                'id_servico' => $servicoId,
            ] : null;

            return response()->json([
                'mensagem' => trim($gptText),
                'action'   => $action,
            ]);
        }

        // 8) caso o GPT responda direto
        return response()->json([
            'mensagem' => $data1['choices'][0]['message']['content'] ?? '',
            'action'   => null,
        ]);
    }

    /**
     * Retorna o schema de funções para a OpenAI usar function-calling
     */
    private function getOpenAIFunctions(): array
    {
        return [
            [
                "name"        => "consultarHorarios",
                "description" => "Retorna horários disponíveis de um professor para um serviço em determinado dia da semana",
                "parameters"  => [
                    "type"       => "object",
                    "properties" => [
                        "dia_semana"   => [
                            "type"        => "string",
                            "description" => "Dia da semana em texto, ex: 'sexta-feira'"
                        ],
                        "id_servico"   => [
                            "type"        => "integer",
                            "description" => "ID do serviço (preenchido pelo backend)"
                        ],
                        "id_professor" => [
                            "type"        => "integer",
                            "description" => "ID do professor (preenchido pelo backend)"
                        ],
                    ],
                    "required"   => ["dia_semana", "id_servico", "id_professor"],
                ]
            ]
        ];
    }

    public function consultarHorarios(Request $request): JsonResponse
    {
        $diaSemana = strtolower($request->input('dia_semana'));
        $idServico = $request->input('id_servico');
        $idProfessor = $request->input('id_professor');

        $dias = [
            'domingo' => 0,
            'segunda-feira' => 1,
            'terça-feira' => 2,
            'quarta-feira' => 3,
            'quinta-feira' => 4,
            'sexta-feira' => 5,
            'sábado' => 6,
        ];

        if (!isset($dias[$diaSemana])) {
            return response()->json(['erro' => 'Dia da semana inválido.'], 400);
        }

        $idDia = $dias[$diaSemana];

        $horarios = Disponibilidade::where('id_dia', $idDia)
            ->where('id_servico', $idServico)
            ->where('id_professor', $idProfessor)
            ->whereDate('data', '>=', Carbon::today())
            ->get(['data', 'hora_inicio', 'hora_fim']);

        if ($horarios->isEmpty()) {
            return response()->json(['mensagem' => 'Nenhum horário disponível encontrado.']);
        }

        // Formata os horários para linguagem natural
        $respostaFormatada = "Horários disponíveis para $diaSemana:\n";
        foreach ($horarios as $horario) {
            $respostaFormatada .= "- " . date('d/m/Y', strtotime($horario->data)) .
                " das " . substr($horario->hora_inicio, 0, 5) .
                " às " . substr($horario->hora_fim, 0, 5) . "\n";
        }

        return response()->json([
            'mensagem' => $respostaFormatada,
            'dados_brutos' => $horarios
        ]);
    }

    /**
     * Cria um novo agendamento para um horário disponível
     */
    public function criarAgendamento(Request $request): JsonResponse
    {
        // 1) Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'id_professor' => 'required|integer|exists:professores,id',
            'id_servico' => 'required|integer|exists:servicos,id',
            'data' => 'required|date_format:Y-m-d',
            'hora_inicio' => 'required|date_format:H:i:s',
            'hora_fim' => 'required|date_format:H:i:s',
            'id_cliente' => 'required|integer|exists:clientes,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['erro' => $validator->errors()], 400);
        }

        $idProfessor = $request->input('id_professor');
        $idServico = $request->input('id_servico');
        $data = $request->input('data');
        $horaInicio = $request->input('hora_inicio');
        $horaFim = $request->input('hora_fim');
        $idCliente = $request->input('id_cliente');

        // 2) Verifica se o horário está disponível
        $disponibilidade = Disponibilidade::where('id_professor', $idProfessor)
            ->where('id_servico', $idServico)
            ->where('data', $data)
            ->where('hora_inicio', $horaInicio)
            ->where('hora_fim', $horaFim)
            ->first();

        if (!$disponibilidade) {
            return response()->json(['erro' => 'Horário não disponível.'], 400);
        }

        // 3) Verifica se já existe um agendamento para o mesmo horário
        $agendamentoExistente = Agendamento::where('id_professor', $idProfessor)
            ->where('id_servico', $idServico)
            ->where('data', $data)
            ->where('hora_inicio', $horaInicio)
            ->where('hora_fim', $horaFim)
            ->exists();

        if ($agendamentoExistente) {
            return response()->json(['erro' => 'Horário já agendado.'], 400);
        }

        // 4) Cria o agendamento
        $agendamento = Agendamento::create([
            'id_professor' => $idProfessor,
            'id_servico' => $idServico,
            'id_cliente' => $idCliente,
            'data' => $data,
            'hora_inicio' => $horaInicio,
            'hora_fim' => $horaFim,
            'status' => 'confirmado',
        ]);

        // 5) Remove a disponibilidade para evitar conflitos
        $disponibilidade->delete();

        // 6) Formata a resposta
        $respostaFormatada = "Agendamento confirmado para " . date('d/m/Y', strtotime($data)) .
            " das " . substr($horaInicio, 0, 5) . " às " . substr($horaFim, 0, 5) . ".";

        return response()->json([
            'mensagem' => $respostaFormatada,
            'agendamento' => $agendamento
        ], 201);
    }
}
