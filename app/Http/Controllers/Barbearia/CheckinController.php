<?php

namespace App\Http\Controllers\Barbearia;

use App\Http\Controllers\Controller;
use App\Models\AlunoProfessor;
use App\Models\Alunos;
use App\Models\Empresa;
use App\Models\FilaAtendimento;
use App\Models\NichoConfiguracao;
use App\Models\Professor;
use App\Services\Barbearia\AvisoBarbeiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Check-in do cliente na fila de um barbeiro e acompanhamento da posição. */
class CheckinController extends Controller
{
    public function checkin(Request $request, Empresa $empresa, Professor $professor)
    {
        abort_unless($professor->empresa_id === $empresa->id && $professor->ativo, 404);

        $usuario = $request->user();
        if ($usuario->tipo_usuario !== 'Aluno') {
            return back()->with('erro', 'Para entrar na fila, entre como cliente (barbeiros e donos não entram na fila).');
        }

        if (! PublicoController::liberado($request, $empresa)) {
            return back()->with('erro', 'Para entrar na fila, escaneie o QR code da barbearia.');
        }

        $fila = DB::transaction(function () use ($usuario, $empresa, $professor) {
            // trava o cliente enquanto confere: dois toques seguidos não criam duas posições
            $aluno = Alunos::firstOrCreate(['usuario_id' => $usuario->id]);
            Alunos::whereKey($aluno->id)->lockForUpdate()->first();

            if ($aberta = FilaAtendimento::abertas()->where('empresa_id', $empresa->id)->where('aluno_id', $aluno->id)->first()) {
                return [$aberta, false];
            }

            // cada cliente pode ter vários barbeiros: o vínculo nasce no primeiro check-in
            AlunoProfessor::firstOrCreate(['aluno_id' => $aluno->id, 'professor_id' => $professor->id]);

            return [FilaAtendimento::create([
                'empresa_id' => $empresa->id,
                'professor_id' => $professor->id,
                'aluno_id' => $aluno->id,
                'status' => FilaAtendimento::AGUARDANDO,
                'chegou_em' => now(),
            ]), true];
        });

        [$entrada, $nova] = $fila;

        // avisa o celular do barbeiro depois de responder ao cliente: o check-in nunca espera (nem falha por causa do push)
        if ($nova) {
            app()->terminating(fn () => app(AvisoBarbeiro::class)->novoCliente($entrada));
        }

        return redirect()->route('barbearia.fila', $entrada)
            ->with($nova ? 'ok' : 'erro', $nova ? 'Você entrou na fila!' : 'Você já está na fila desta barbearia.');
    }

    public function fila(Request $request, FilaAtendimento $fila)
    {
        $this->doCliente($request, $fila);

        $empresa = $fila->empresa;

        $dados = [
            'nicho' => NichoConfiguracao::daEmpresa($empresa),
            'empresa' => $empresa,
            'fila' => $fila->load('professor.usuario'),
            'posicao' => $fila->posicao(),
        ];

        // a tela se atualiza sozinha buscando só o miolo
        return $request->ajax() || $request->boolean('fragmento') ? view('barbearia._status_fila', $dados) : view('barbearia.fila', $dados);
    }

    public function desistir(Request $request, FilaAtendimento $fila)
    {
        $this->doCliente($request, $fila);

        if ($fila->aberta()) {
            $fila->update(['status' => FilaAtendimento::DESISTIU, 'finalizado_em' => now()]);
        }

        return redirect()->route('barbearia.fila', $fila)->with('ok', 'Você saiu da fila.');
    }

    /** A página da fila é só da pessoa que fez o check-in. */
    private function doCliente(Request $request, FilaAtendimento $fila): void
    {
        abort_unless(Alunos::where('id', $fila->aluno_id)->where('usuario_id', $request->user()->id)->exists(), 403);
    }
}
