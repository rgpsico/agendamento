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

    public $tries = 5;

    public $timeout = 600;

    public function __construct(array $dados)
    {
        $this->dados = $dados;
    }

    public function handle()
    {
        Log::info("Iniciando o job de geração de horários", $this->dados);

        try {
            $servicoId = $this->dados['servico'];
            $professorId = $this->dados['professor_id'];
            $tempoAtendimento = $this->dados['duracao'];
            $intervalo = $this->dados['intervalo'];
            $horaInicio = $this->dados['inicio'];
            $horaFim = $this->dados['fim'];
            $horaInicioAlmoco = $this->dados['almoco_inicio'];
            $horaFimAlmoco = $this->dados['almoco_fim'];
            $diasFolga = $this->dados['folga'];
            $considerarFeriados = $this->dados['feriados'];

            $dataAtual = Carbon::today();
            $dataFinal = Carbon::today()->addWeek();

            $disponibilidades = [];

            while ($dataAtual->lte($dataFinal)) {
                $diaDaSemana = $dataAtual->dayOfWeekIso;

                if (in_array($diaDaSemana, $diasFolga)) {
                    $dataAtual->addDay();
                    continue;
                }

                if ($considerarFeriados) {
                    $feriadoExiste = Feriado::where('data', $dataAtual->format('Y-m-d'))->exists();
                    if ($feriadoExiste) {
                        $dataAtual->addDay();
                        continue;
                    }
                }

                $horaAtual = Carbon::parse($dataAtual->format('Y-m-d') . ' ' . $horaInicio);
                $fimExpediente = Carbon::parse($dataAtual->format('Y-m-d') . ' ' . $horaFim);
                $inicioAlmoco = Carbon::parse($dataAtual->format('Y-m-d') . ' ' . $horaInicioAlmoco);
                $fimAlmoco = Carbon::parse($dataAtual->format('Y-m-d') . ' ' . $horaFimAlmoco);

                while ($horaAtual->lte($fimExpediente)) {
                    if ($horaAtual->between($inicioAlmoco, $fimAlmoco, true)) {
                        $horaAtual = $fimAlmoco->copy();
                    }

                    $horaFimAtendimento = $horaAtual->copy()->addMinutes($tempoAtendimento);

                    if ($horaFimAtendimento->lte($fimExpediente)) {
                        $disponibilidades[] = [
                            'id_professor' => $professorId,
                            'id_servico' => $servicoId,
                            'id_dia' => $diaDaSemana,
                            'data' => $dataAtual->format('Y-m-d'),
                            'hora_inicio' => $horaAtual->format('H:i:s'),
                            'hora_fim' => $horaFimAtendimento->format('H:i:s'),
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }

                    $horaAtual->addMinutes($tempoAtendimento + $intervalo);
                }

                $dataAtual->addDay();
            }

            // Insert em chunk
            $chunkSize = 10;
            foreach (array_chunk($disponibilidades, $chunkSize) as $chunk) {
                Disponibilidade::insert($chunk);
            }

            Log::info("Job finalizado com sucesso");
        } catch (\Throwable $e) {
            Log::error("Erro ao processar job: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e; // importante para o worker saber que falhou
        }
    }
}
