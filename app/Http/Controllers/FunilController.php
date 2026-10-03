<?php

namespace App\Http\Controllers;

use App\Models\LeadQualificacao;
use App\Services\FunilService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Funil público: /comecar → perguntas → oferta → teste grátis. */
class FunilController extends Controller
{
    /** Entrada pública: cria o registro sozinho (guardando a origem da campanha) e abre o formulário. */
    public function comecar(Request $request)
    {
        $q = LeadQualificacao::create([
            'utm_source'   => mb_substr((string) $request->query('utm_source'), 0, 100) ?: null,
            'utm_medium'   => mb_substr((string) $request->query('utm_medium'), 0, 100) ?: null,
            'utm_campaign' => mb_substr((string) $request->query('utm_campaign'), 0, 150) ?: null,
            'host'         => $request->getHost(),
        ]);

        return redirect()->route('validador.public', $q->token);
    }

    public function oferta(string $token)
    {
        $q = LeadQualificacao::where('token', $token)->firstOrFail();

        if (! $q->concluiuFormulario()) {
            return redirect()->route('validador.public', $token);
        }
        if ($q->status !== 'respondido') {
            return redirect()->route('validador.public', $token); // já está em teste ou é cliente
        }

        $nicho = app()->has('currentNicho') ? app('currentNicho') : null;

        return view('validador.oferta', [
            'q'      => $q,
            'plano'  => config('funil.plano'),
            'dias'   => (int) config('funil.trial_dias', 14),
            'marca'  => $nicho?->nome ?? 'PilatesGestão',
            'emoji'  => $nicho?->emoji ?? '🧘',
            'corA'   => $nicho?->cor_primaria ?? '#059669',
            'corB'   => $nicho?->cor_secundaria ?? '#0f172a',
            'logo'   => $nicho?->logo_url,
        ]);
    }

    public function iniciarTeste(Request $request, string $token, FunilService $funil)
    {
        $q = LeadQualificacao::where('token', $token)->firstOrFail();

        if ($q->status !== 'respondido') {
            return redirect()->route('validador.public', $token);
        }

        $data = $request->validate([
            'negocio' => 'required|string|max:120',
            'email'   => 'required|email|max:150|unique:usuarios,email',
            'senha'   => 'required|string|min:6|max:100',
        ], [
            'email.unique' => 'Esse e-mail já tem conta. Entre pelo login.',
            'senha.min'    => 'A senha precisa de pelo menos 6 caracteres.',
        ]);

        $user = $funil->iniciarTeste($q, $data['negocio'], $data['email'], $data['senha']);
        Auth::login($user);

        $request->session()->put('funil_prefill', [
            'nome'          => $data['negocio'],
            'email'         => $data['email'],
            'telefone'      => $q->whatsapp,
            'descricao'     => $q->sobre_negocio,
            'modalidade_id' => $funil->modalidadeSugerida($q),
        ]);

        return redirect()->route('funil.teste_iniciado');
    }

    /** Página rápida que avisa o Pixel (StartTrial) e segue pro onboarding. */
    public function testeIniciado(Request $request)
    {
        $q = LeadQualificacao::where('usuario_id', Auth::id())->where('status', 'trial')->latest()->first();
        abort_unless($q, 404);

        return view('validador.teste-iniciado', ['q' => $q]);
    }

    /** Segue pro cadastro da empresa já com os dados que a pessoa deu. */
    public function onboarding(Request $request)
    {
        $prefill = $request->session()->pull('funil_prefill', []);

        return redirect()->route('empresa.configuracao', ['userId' => Auth::id()])->withInput($prefill);
    }
}
