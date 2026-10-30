<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Bot;
use App\Models\Disponibilidade;
use App\Models\EmpresaSite;
use App\Models\Servicos;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Página pública (compartilhável) com as datas e horários livres da escola.
 * Usa os mesmos horários semanais do painel (tabela disponibilidade) e esconde o que já foi agendado.
 */
class SiteHorariosController extends Controller
{
    private const DIAS_A_FRENTE = 75;
    private const FUSO = 'America/Sao_Paulo';

    public function mostrar(string $slug)
    {
        $site = EmpresaSite::with(['empresa', 'endereco'])->where('slug', $slug)->where('ativo', true)->firstOrFail();

        return $this->render($site);
    }

    /** Sites com domínio próprio: https://dominio/horarios */
    public function dominio(Request $request)
    {
        $site = app()->has('currentSite') ? app('currentSite') : EmpresaSite::resolveByHost($request->getHost());
        abort_unless($site && $site->ativo, 404);

        return $this->render($site->load(['empresa', 'endereco']));
    }

    private function render(EmpresaSite $site)
    {
        $hoje = Carbon::now(self::FUSO);
        $fim  = $hoje->copy()->addDays(self::DIAS_A_FRENTE);

        $servicos = Servicos::where('empresa_id', $site->empresa_id)->orderBy('id')->get();
        $semanal  = Disponibilidade::whereIn('id_servico', $servicos->pluck('id'))->get()->groupBy('id_servico');

        // Já agendados no período: mesma regra da API (professor + dia + horário)
        $ocupados = Agendamento::whereBetween('data_da_aula', [$hoje->toDateString(), $fim->toDateString()])
            ->whereIn('servico_id', $servicos->pluck('id'))
            ->get(['professor_id', 'data_da_aula', 'horario'])
            ->map(fn ($a) => $a->professor_id . '|' . Carbon::parse($a->data_da_aula)->toDateString() . '|' . Carbon::parse($a->horario)->format('H:i'))
            ->flip();

        $agenda = [];  // 'YYYY-MM-DD' => [servico_id => [['i'=>'09:00','f'=>'10:00'], ...]]
        $comHorario = [];

        for ($d = $hoje->copy()->startOfDay(); $d->lte($fim); $d->addDay()) {
            $dia = $d->toDateString();

            foreach ($servicos as $servico) {
                $slots = [];

                foreach ($semanal->get($servico->id, collect())->where('id_dia', $d->isoWeekday()) as $s) {
                    $inicio = Carbon::parse($s->hora_inicio)->format('H:i');

                    if ($d->isSameDay($hoje) && $inicio <= $hoje->format('H:i')) {
                        continue; // horário de hoje que já passou
                    }
                    if ($ocupados->has($s->id_professor . '|' . $dia . '|' . $inicio)) {
                        continue;
                    }
                    $slots[$inicio] = ['i' => $inicio, 'f' => Carbon::parse($s->hora_fim)->format('H:i')];
                }

                if ($slots) {
                    ksort($slots);
                    $agenda[$dia][$servico->id] = array_values($slots);
                    $comHorario[$servico->id] = true;
                }
            }
        }

        $servicosComHorario = $servicos->filter(fn ($s) => isset($comHorario[$s->id]))
            ->map(fn ($s) => ['id' => $s->id, 'titulo' => $s->titulo])
            ->values();

        $bot = $site->bot_id ? Bot::find($site->bot_id) : null;

        return view('site.horarios', [
            'site'     => $site,
            'agenda'   => $agenda,
            'servicos' => $servicosComHorario,
            'hoje'     => $hoje->toDateString(),
            'ate'      => $fim->toDateString(),
            'bot'      => ($bot && $bot->widget_ativo && $bot->status) ? $bot : null,
        ]);
    }
}
