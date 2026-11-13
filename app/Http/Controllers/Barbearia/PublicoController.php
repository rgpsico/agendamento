<?php

namespace App\Http\Controllers\Barbearia;

use App\Http\Controllers\Controller;
use App\Models\Alunos;
use App\Models\Empresa;
use App\Models\FilaAtendimento;
use App\Models\Modalidade;
use App\Models\NichoConfiguracao;
use App\Models\Professor;
use App\Support\Nicho;
use Illuminate\Http\Request;

/** Páginas que o cliente vê: barbearias, barbeiros da casa e o QR da parede. */
class PublicoController extends Controller
{
    public function index()
    {
        // nichos com fila de check-in; se o domínio é de um nicho, só as barbearias dele
        $nichos = NichoConfiguracao::where('ativo', true)->get()->filter(fn ($n) => $n->recurso('fila_checkin'));
        if ($atual = Nicho::atual()) {
            $nichos = $nichos->where('nicho', $atual->nicho);
        }

        $modalidades = Modalidade::whereIn('nicho', $nichos->pluck('nicho'))->pluck('id');
        $barbearias = Empresa::whereIn('modalidade_id', $modalidades)->where('status', 'ativo')->orderBy('nome')->get();

        // só uma barbearia: o cliente vai direto escolher o barbeiro, sem passar por uma lista de um item
        if ($barbearias->count() === 1) {
            return redirect()->route('barbearia.show', $barbearias->first());
        }

        return view('barbearia.index', ['nicho' => $nichos->first(), 'barbearias' => $barbearias]);
    }

    public function show(Request $request, Empresa $empresa)
    {
        $barbeiros = Professor::where('empresa_id', $empresa->id)->where('ativo', true)->with('usuario')->get()->map(function ($b) {
            $b->esperando = FilaAtendimento::where('professor_id', $b->id)->where('status', FilaAtendimento::AGUARDANDO)->count();

            return $b;
        });

        return view('barbearia.show', [
            'nicho' => $request->attributes->get('nicho'),
            'empresa' => $empresa,
            'barbeiros' => $barbeiros,
            'noLocal' => $this->noLocal($empresa),
        ]);
    }

    public function barbeiro(Request $request, Empresa $empresa, Professor $professor)
    {
        abort_unless($professor->empresa_id === $empresa->id && $professor->ativo, 404);

        $minhaFila = null;
        if ($usuario = $request->user()) {
            $aluno = Alunos::where('usuario_id', $usuario->id)->first();
            $minhaFila = $aluno ? FilaAtendimento::abertas()->where('empresa_id', $empresa->id)->where('aluno_id', $aluno->id)->first() : null;
        }

        return view('barbearia.barbeiro', [
            'nicho' => $request->attributes->get('nicho'),
            'empresa' => $empresa,
            'barbeiro' => $professor->load('usuario'),
            'esperando' => FilaAtendimento::where('professor_id', $professor->id)->where('status', FilaAtendimento::AGUARDANDO)->count(),
            'minhaFila' => $minhaFila,
            'noLocal' => $this->noLocal($empresa),
        ]);
    }

    /** Destino do QR da parede: prova que o cliente está na barbearia e libera o check-in nesta sessão. */
    public function qr(Request $request, Empresa $empresa, string $token)
    {
        abort_unless($empresa->checkin_token && hash_equals($empresa->checkin_token, $token), 404);

        $request->session()->put($this->chaveSessao($empresa), true);

        return redirect()->route('barbearia.show', $empresa);
    }

    /** O cliente pode entrar na fila agora? (leu o QR da barbearia, ou o dono desligou essa exigência) */
    public static function liberado(Request $request, Empresa $empresa): bool
    {
        return ! $empresa->checkin_exige_qr || (bool) $request->session()->get(self::chaveSessao($empresa));
    }

    public static function chaveSessao(Empresa $empresa): string
    {
        return "barbearia.qr.{$empresa->id}";
    }

    private function noLocal(Empresa $empresa): bool
    {
        return self::liberado(request(), $empresa);
    }
}
