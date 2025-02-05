<?php

namespace App\Http\Controllers;

use App\Models\Configuracao;
use Illuminate\Http\Request;

class ConfiguracoesController extends Controller
{
    public function permissoes()
    {
        return view('configuracoes.permissoes');
    }

    public function pagamentos()
    {
        return view('configuracoes.pagamentos');
    }

    public function empresa()
    {
        return view('configuracoes.empresa');
    }

    public function usuarios()
    {
        return view('configuracoes.usuarios');
    }

    public function sistema()
    {
        return view('configuracoes.sistema');
    }

    public function indexadmin()
    {
        // Pegar todas as configurações salvas no banco
        $config = Configuracao::first();

        return view('configuracoesadmin.index', [
            'tipoAgendamento' => $config->agendamento_tipo ?? 'horarios',
            'whatsappNumero' => $config->whatsapp_numero ?? '',
            'loginImage' => $config->login_image ?? '',
            'registerImage' => $config->register_image ?? '',
            'homeMode' => $config->home_mode ?? 'carousel',
            'carouselImages' => json_decode($config->carousel_images ?? '[]', true),
            'sistemaTipo' => $config->sistema_tipo ?? 'passeio',
        ]);
    }

    public function index()
    {
        $empresaId = auth()->user()->empresa->id ?? null;

        // Obtém as configurações da empresa
        $tipoAgendamento = Configuracao::get($empresaId, 'agendamento_tipo', 'horarios');
        $whatsappNumero = Configuracao::get($empresaId, 'whatsapp_numero', '');

        return view('configuracoes.index', compact('tipoAgendamento', 'whatsappNumero'));
    }

    public function salvar(Request $request)
    {
        $empresaId = auth()->user()->empresa->id ?? null;

        // Atualiza o tipo de agendamento
        Configuracao::set($empresaId, 'agendamento_tipo', $request->input('agendamento_tipo'));

        // Atualiza o número do WhatsApp, se necessário
        if ($request->input('agendamento_tipo') === 'whatsapp') {
            Configuracao::set($empresaId, 'whatsapp_numero', $request->input('whatsapp_numero'));
        }

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}
