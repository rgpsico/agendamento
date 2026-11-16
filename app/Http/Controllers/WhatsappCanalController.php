<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\Empresa;
use App\Models\WhatsappCanal;
use App\Support\EmpresasDoUsuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Números de WhatsApp atendidos pela IA (área "Atendimento com IA").
 * - Master: liga um número (sender do Twilio da plataforma) a uma empresa e a um bot; remove.
 * - Dono / funcionário com a área: vê os números da empresa, escolhe o bot e liga/desliga a IA.
 */
class WhatsappCanalController extends Controller
{
    public function index()
    {
        $eu = auth()->user();
        $master = $eu->isMasterUser();
        $empresas = EmpresasDoUsuario::ids($eu);

        $canais = WhatsappCanal::with(['empresa', 'bot'])
            ->when(! $master, fn ($q) => $q->whereIn('empresa_id', $empresas ?: [0]))
            ->orderBy('empresa_id')->get();

        $bots = Bot::when(! $master, fn ($q) => $q->whereIn('empresa_id', $empresas ?: [0]))->orderBy('nome')->get();

        return view('admin.bot.whatsapp', [
            'canais'     => $canais,
            'bots'       => $bots,
            'master'     => $master,
            'empresasDisponiveis' => $master ? Empresa::orderBy('nome')->get(['id', 'nome']) : collect(),
            'webhookUrl' => url('/api/webhook/twilio'),
        ]);
    }

    /** Master: liga um número a uma empresa. */
    public function store(Request $request)
    {
        abort_unless(auth()->user()->isMasterUser(), 403);

        $request->merge(['numero' => WhatsappCanal::normalizarNumero($request->numero)]);
        $dados = $request->validate([
            'empresa_id' => 'required|exists:empresa,id',
            'bot_id'     => ['nullable', Rule::exists('bots', 'id')->where('empresa_id', $request->empresa_id)],
            'numero'     => 'required|regex:/^\+\d{10,15}$/|unique:whatsapp_canais,numero',
        ], [
            'numero.regex'  => 'Informe o número com DDI e DDD, ex.: +5521999999999.',
            'numero.unique' => 'Este número já está ligado a uma empresa.',
            'bot_id.exists' => 'O bot escolhido não é desta empresa.',
        ]);

        WhatsappCanal::create($dados + ['provedor' => 'twilio', 'ativo' => true, 'ia_ativa' => true]);

        return back()->with('success', 'Número ligado à empresa. Configure o webhook no Twilio (veja abaixo).');
    }

    /** Dono: escolhe o bot e liga/desliga a IA. Master: também ativa/desativa o número. */
    public function update(Request $request, WhatsappCanal $canal)
    {
        $this->daMinhaEmpresa($canal);

        $dados = $request->validate([
            'bot_id'   => ['nullable', Rule::exists('bots', 'id')->where('empresa_id', $canal->empresa_id)],
            'ia_ativa' => 'nullable|boolean',
            'ativo'    => 'nullable|boolean',
        ], ['bot_id.exists' => 'O bot escolhido não é desta empresa.']);

        $canal->bot_id = $dados['bot_id'] ?? null;
        $canal->ia_ativa = $request->boolean('ia_ativa');
        if (auth()->user()->isMasterUser()) {
            $canal->ativo = $request->boolean('ativo');
        }
        $canal->save();

        return back()->with('success', $canal->ia_ativa
            ? 'IA ligada: ela responde as mensagens que chegarem neste número.'
            : 'IA desligada: as mensagens chegam em Conversas para a equipe responder.');
    }

    public function destroy(WhatsappCanal $canal)
    {
        abort_unless(auth()->user()->isMasterUser(), 403);
        $canal->delete();

        return back()->with('success', 'Número desligado da empresa.');
    }

    private function daMinhaEmpresa(WhatsappCanal $canal): void
    {
        $eu = auth()->user();
        abort_unless($eu->isMasterUser() || in_array((int) $canal->empresa_id, EmpresasDoUsuario::ids($eu), true), 404);
    }
}
