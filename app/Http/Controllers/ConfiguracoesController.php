<?php

namespace App\Http\Controllers;

use App\Models\Configuracao;
use App\Models\ConfiguracaoGeral;
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
      
        // Buscar as configurações salvas
        $config = ConfiguracaoGeral::first();

        return view('configuracoesadmin.index', [
        'tipoAgendamento' => optional($config)->agendamento_tipo ?? 'horarios',
        'whatsappNumero' => optional($config)->whatsapp_numero ?? '',
        'loginImage' => optional($config)->login_image ? asset('storage/' . $config->login_image) : null,
        'registerImage' => optional($config)->register_image ? asset('storage/' . $config->register_image) : null,
        'homeMode' => optional($config)->home_mode ?? 'carousel',
        'carouselImages' => json_decode(optional($config)->carousel_images ?? '[]', true),
        'sistemaTipo' => optional($config)->sistema_tipo ?? 'passeio',
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


    public function salvarConfigGeral(Request $request)
    {
        // Validação dos campos obrigatórios
        // $request->validate([
        //     'agendamento_tipo' => 'required|string',
        //     'whatsapp_numero' => 'nullable|string',
        //     'home_mode' => 'required|string',
        //     'sistema_tipo' => 'required|string',
        //     'login_image' => 'nullable|image|max:2048',
        //     'register_image' => 'nullable|image|max:2048',
        //     'carousel_images.*' => 'nullable|image|max:2048',
        // ]);

        // Obter a primeira configuração ou criar nova
        $config = ConfiguracaoGeral::firstOrNew([]);

        // Atualizar configurações gerais
        $config->agendamento_tipo = $request->input('agendamento_tipo', 'horarios'); // Definir padrão caso esteja vazio
        $config->whatsapp_numero = $request->input('whatsapp_numero', '');

        // Upload da imagem da tela de login
        if ($request->hasFile('login_image')) {
            $config->login_image = $request->file('login_image')->store('configuracoes', 'public');
        }

        // Upload da imagem da tela de registro
        if ($request->hasFile('register_image')) {
            $config->register_image = $request->file('register_image')->store('configuracoes', 'public');
        }

        // Configuração da Página Inicial (Carrossel ou Breadcrumb)
        $config->home_mode = $request->input('home_mode', 'carousel');

        // Upload de imagens do carrossel
        if ($request->hasFile('carousel_images')) {
            $carouselPaths = [];
            foreach ($request->file('carousel_images') as $image) {
                $carouselPaths[] = $image->store('carrossel', 'public');
            }
            $config->carousel_images = json_encode($carouselPaths);
        }

        // Definição do Tipo de Sistema
        $config->sistema_tipo = $request->input('sistema_tipo', 'passeio');

        // Salvar tudo no banco
        $config->save();

        return redirect()->route('configuracoes.indexAdmin')->with('success', 'Configurações salvas com sucesso!');
    }
}
