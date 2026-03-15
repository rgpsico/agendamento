<?php

namespace App\Jobs;

use App\Models\Disponibilidade;
use App\Models\Feriado;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GerarHorariosJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $dados;

    public $tries = 3;
    public $timeout = 600;

    public function __construct(array $dados)
    {
        $this->dados = $dados;
    }

    public function handle()
    {
        Log::info("Iniciando geração de horários...", ['dados' => $this->dados]);

        try {
            // Extração de dados
            $servicoId = $this->dados['servico'];
            $professorId = $this->dados['professor_id'];
            $tempoAtendimento = (int) $this->dados['duracao']; // em minutos
            $intervalo = (int) $this->dados['intervalo']; // em minutos
            
            // Horários de trabalho
            $horaInicioConfig = $this->dados['inicio']; 
            $horaFimConfig = $this->dados['fim'];
            $horaInicioAlmocoConfig = $this->dados['almoco_inicio'];
            $horaFimAlmocoConfig = $this->dados['almoco_fim'];
            
            $diasFolga = $this->dados['folga'] ?? []; // Array de dias da semana (1=Seg, 7=Dom)
            $considerarFeriados = $this->dados['feriados'] ?? false;

            // 1. CORREÇÃO DA DATA:
            // Se vier no formulário, usa. Se não, pega o mês ATUAL INTEIRO (do dia 1 ao fim).
            if (isset($this->dados['data_inicio']) && isset($this->dados['data_fim'])) {
                $dataAtual = Carbon::parse($this->dados['data_inicio']);
                $dataFinal = Carbon::parse($this->dados['data_fim']);
            } else {
                // Fallback: Mês atual completo
                $dataAtual = Carbon::now()->startOfMonth();
                $dataFinal = Carbon::now()->endOfMonth();
            }

            // 2. PERFORMANCE: Carregar feriados uma vez só
            $feriados = [];
            if ($considerarFeriados) {
                $feriados = Feriado::whereBetween('data', [
                    $dataAtual->format('Y-m-d'), 
                    $dataFinal->format('Y-m-d')
                ])->pluck('data')->map(function($date) {
                    return Carbon::parse($date)->format('Y-m-d');
                })->toArray();
            }

            $disponibilidades = [];

            // Loop dos Dias
            while ($dataAtual->lte($dataFinal)) {
                $diaString = $dataAtual->format('Y-m-d');
                $diaDaSemana = $dataAtual->dayOfWeekIso; // 1 (Segunda) a 7 (Domingo)

                // Verifica Folga
                if (in_array($diaDaSemana, $diasFolga)) {
                    $dataAtual->addDay();
                    continue;
                }

                // Verifica Feriado (array em memória, sem query no loop)
                if ($considerarFeriados && in_array($diaString, $feriados)) {
                    $dataAtual->addDay();
                    continue;
                }

                // Configura horários do dia específico
                $horaAtual = Carbon::parse($diaString . ' ' . $horaInicioConfig);
                $fimExpediente = Carbon::parse($diaString . ' ' . $horaFimConfig);
                $inicioAlmoco = Carbon::parse($diaString . ' ' . $horaInicioAlmocoConfig);
                $fimAlmoco = Carbon::parse($diaString . ' ' . $horaFimAlmocoConfig);

                // Loop dos Horários dentro do dia
                while ($horaAtual->lt($fimExpediente)) {
                    
                    // Calcula fim do atendimento
                    $horaFimAtendimento = $horaAtual->copy()->addMinutes($tempoAtendimento);

                    // Verifica se o atendimento excede o expediente
                    if ($horaFimAtendimento->gt($fimExpediente)) {
                        break; 
                    }

                    // 3. LÓGICA DE ALMOÇO (Interseção de horários)
                    // Se o atendimento termina DEPOIS que o almoço começa 
                    // E começa ANTES do almoço terminar, existe conflito.
                    $conflitaComAlmoco = ($horaFimAtendimento->gt($inicioAlmoco) && $horaAtual->lt($fimAlmoco));

                    if ($conflitaComAlmoco) {
                        // Pula direto para o fim do almoço
                        $horaAtual = $fimAlmoco->copy();
                        continue;
                    }

                    // Adiciona na lista
                    $disponibilidades[] = [
                        'id_professor' => $professorId,
                        'id_servico'   => $servicoId,
                        'id_dia'       => $diaDaSemana,
                        'data'         => $diaString,
                        'hora_inicio'  => $horaAtual->format('H:i:s'),
                        'hora_fim'     => $horaFimAtendimento->format('H:i:s'),
                        'created_at'   => now(),
                        'updated_at'   => now()
                    ];

                    // Avança para o próximo horário (duração + intervalo)
                    $horaAtual->addMinutes($tempoAtendimento + $intervalo);
                }

                $dataAtual->addDay();
            }

            // 4. INSERT EM LOTE OTIMIZADO
            if (count($disponibilidades) > 0) {
                // Chunk maior para menos queries de insert
                foreach (array_chunk($disponibilidades, 100) as $chunk) {
                    Disponibilidade::insert($chunk);
                }
                Log::info(count($disponibilidades) . " horários gerados com sucesso.");
            } else {
                Log::warning("Nenhum horário gerado para o período.");
            }

        } catch (\Throwable $e) {
            Log::error("Erro job horarios: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }
}