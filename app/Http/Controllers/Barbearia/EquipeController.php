<?php

namespace App\Http\Controllers\Barbearia;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\NichoConfiguracao;
use App\Models\Professor;
use App\Models\Usuario;
use App\Support\Telefone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/** Painel do dono da barbearia: equipe de barbeiros (cada um com login próprio) e o QR de check-in da parede. */
class EquipeController extends Controller
{
    /** Cada tipo de usuário cai no seu painel. */
    public function painel(Request $request)
    {
        if ($this->barbearias($request)->isNotEmpty()) {
            return redirect()->route('barbearia.visao');
        }

        // barbeiro vai para a fila dele; quem não é dono nem barbeiro de uma barbearia não tem painel aqui
        abort_unless(BarbeiroController::doUsuario($request->user()), 403, 'Você não tem acesso ao painel da barbearia.');

        return redirect()->route('barbearia.painel.fila');
    }

    public function index(Request $request)
    {
        $empresa = $this->empresa($request);

        return view('barbearia.equipe', [
            'nicho' => NichoConfiguracao::daEmpresa($empresa),
            'empresa' => $empresa,
            'barbearias' => $this->barbearias($request),
            'barbeiros' => Professor::where('empresa_id', $empresa->id)->with('usuario')->orderByDesc('ativo')->orderBy('id')->get(),
            'souBarbeiro' => (bool) BarbeiroController::doUsuario($request->user()),
            'linkQr' => route('barbearia.qr', [$empresa, $empresa->tokenCheckin()]),
        ]);
    }

    public function store(Request $request)
    {
        $empresa = $this->empresa($request);
        $request->merge(['telefone' => Telefone::normalizar($request->input('telefone'))]);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'min:2', 'max:120'],
            'telefone' => ['required', 'digits_between:10,11'],
            'senha' => ['required', 'string', 'min:4', 'max:60'],
        ], [
            'nome.required' => 'Digite o nome do barbeiro.',
            'telefone.digits_between' => 'Digite o celular com DDD, ex: 21 99999-9999.',
            'senha.min' => 'A senha precisa ter pelo menos 4 caracteres.',
        ]);

        if (Usuario::where('telefone', $dados['telefone'])->exists()) {
            return back()->withInput($request->except('senha'))->withErrors(['telefone' => 'Este celular já tem cadastro.']);
        }

        DB::transaction(function () use ($dados, $empresa) {
            $usuario = Usuario::forceCreate([
                'nome' => $dados['nome'],
                'email' => Telefone::emailFicticio($dados['telefone']),
                'password' => Hash::make($dados['senha']),
                'tipo_usuario' => 'Professor',
                'telefone' => $dados['telefone'],
            ]);

            Professor::create([
                'usuario_id' => $usuario->id,
                'empresa_id' => $empresa->id,
                'modalidade_id' => $empresa->modalidade_id,
                'sobre' => '',
                'avatar' => '',
                'ativo' => true,
            ]);
        });

        return redirect()->route('barbearia.equipe', ['empresa' => $empresa->id])->with('ok', 'Barbeiro cadastrado. Ele já pode entrar com o celular e a senha.');
    }

    public function ativo(Request $request, Professor $professor)
    {
        $empresa = $this->empresa($request);
        abort_unless($professor->empresa_id === $empresa->id, 403);

        $professor->update(['ativo' => ! $professor->ativo]);

        return redirect()->route('barbearia.equipe', ['empresa' => $empresa->id])
            ->with('ok', $professor->ativo ? 'Barbeiro ativado.' : 'Barbeiro desativado: ele não aparece mais para os clientes.');
    }

    /** Liga/desliga a exigência do QR e renova o código (se o QR impresso vazou). */
    public function checkin(Request $request)
    {
        $empresa = $this->empresa($request);

        $empresa->update(['checkin_exige_qr' => $request->boolean('exige_qr')]);
        if ($request->boolean('renovar')) {
            $empresa->tokenCheckin(true);
        }

        return redirect()->route('barbearia.equipe', ['empresa' => $empresa->id])
            ->with('ok', $request->boolean('renovar') ? 'QR renovado: imprima o novo; o antigo parou de valer.' : 'Configuração salva.');
    }

    /** Troca ou remove a capa da barbearia (foto larga que aparece na lista e no topo da página). */
    public function capa(Request $request)
    {
        $empresa = $this->empresa($request);
        $disco = Storage::disk('public');

        if ($request->boolean('remover')) {
            $this->apagarCapa($empresa);
            $empresa->update(['capa' => null]);

            return $this->voltarEquipe($request, $empresa, 'Capa removida.');
        }

        $request->validate(
            ['capa' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144']],
            ['capa.required' => 'Escolha uma foto.', 'capa.image' => 'O arquivo precisa ser uma imagem.', 'capa.mimes' => 'Use uma foto JPG, PNG ou WebP.', 'capa.max' => 'A foto passou de 6 MB. Escolha uma menor.']
        );

        $antiga = $empresa->capa;
        $caminho = $disco->putFile('barbearia/capas', $request->file('capa'));
        $empresa->update(['capa' => $caminho]);

        if ($antiga && $antiga !== $caminho) {
            $disco->delete($antiga);
        }

        return $this->voltarEquipe($request, $empresa, 'Capa atualizada.');
    }

    private function apagarCapa(Empresa $empresa): void
    {
        if ($empresa->capa) {
            Storage::disk('public')->delete($empresa->capa);
        }
    }

    private function voltarEquipe(Request $request, Empresa $empresa, string $mensagem)
    {
        // o celular envia a foto por fetch (já reduzida): responde JSON; o formulário comum volta para a página
        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'mensagem' => $mensagem, 'capa' => $empresa->fresh()->capaUrl()]);
        }

        return redirect()->route('barbearia.equipe', ['empresa' => $empresa->id])->with('ok', $mensagem);
    }

    /** Barbearias (empresas de nicho com fila) do usuário logado como dono. */
    private function barbearias(Request $request)
    {
        return Empresa::where('user_id', $request->user()->id)->where('status', 'ativo')->get()
            ->filter(fn ($e) => optional(NichoConfiguracao::daEmpresa($e))->recurso('fila_checkin'))->values();
    }

    /** A barbearia que o dono está gerenciando: a escolhida (se for dele) ou a primeira. */
    private function empresa(Request $request): Empresa
    {
        $minhas = $this->barbearias($request);
        abort_if($minhas->isEmpty(), 403, 'Você não tem uma barbearia.');

        return $minhas->firstWhere('id', (int) $request->input('empresa')) ?? $minhas->first();
    }
}
