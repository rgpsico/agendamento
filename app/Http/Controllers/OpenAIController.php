<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Disponibilidade;
use App\Models\Modalidade;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\OpenAIService;



class OpenAIController extends Controller
{
    public function testConnection(Request $request, OpenAIService $openAIService)
    {
        try {
            $response = $openAIService->chat([
                ['role' => 'user', 'content' => 'Diga olá'],
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json([
            'error' => 'Failed to connect to OpenAI',
            'response' => $response->json(),
        ], $response->status());
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
            $action   = null;

            return response()->json([
                'mensagem' => trim($gptText),
                'action'   => $action,    // se não tiver horários, $action será null
            ]);
        }

        // 8) caso o GPT responda direto
        return response()->json([
            'mensagem' => $data1['choices'][0]['message']['content'] ?? '',
            'action'   => null,
        ]);
    }



    public function agendarHorario(Request $request): JsonResponse
    {
        // 1) validação básica
        $data = $request->validate([
            'aluno_id'       => 'required|integer|exists:alunos,id',
            'modalidade_id'  => 'required|integer|exists:modalidades,id',
            'professor_id'   => 'required|integer|exists:professores,id',
            'data_da_aula'   => 'required|date',
            'horario'        => 'required|date_format:H:i:s',
            'valor_aula'     => 'nullable|numeric',
        ]);

        // 2) transfoma data em Carbon e extrai dia da semana (0=domingo…6=sábado)
        $dt       = Carbon::parse($data['data_da_aula']);
        $diaSemana = $dt->dayOfWeek;

        // 3) verifica se existe disponibilidade cadastrada
        $existe = Disponibilidade::where('id_professor', $data['professor_id'])
            ->where('id_servico',    $data['modalidade_id'])
            ->where('id_dia',        $diaSemana)
            ->where('hora_inicio', '<=', $data['horario'])
            ->where('hora_fim',    '>=', $data['horario'])
            ->exists();

        if (! $existe) {
        }

        // 4) define valor da aula (se não veio, busca na modalidade)
        $valor = $data['valor_aula'] ?? Modalidade::find($data['modalidade_id'])->valor_aula;

        // 5) cria o agendamento
        $agendamento = Agendamento::create([
            'aluno_id'      => $data['aluno_id'],
            'modalidade_id' => $data['modalidade_id'],
            'professor_id'  => $data['professor_id'],
            'data_da_aula'  => $data['data_da_aula'],
            'valor_aula'    => $valor,
            'horario'       => $data['horario'],
        ]);

        return response()->json([
            'mensagem'     => 'Agendamento criado com sucesso!',
            'agendamento'  => $agendamento,
        ], 201);
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
            'dados_brutos' => $horarios // opcional, caso queira também os dados crus
        ]);
    }
}
