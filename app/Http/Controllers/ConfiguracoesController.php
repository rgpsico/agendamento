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
                'nomeSistema' => $config->nome_sistema ?? '',
                'logoHeader' => $config->logo_header ?? null,
                'logoFooter' => $config->logo_footer ?? null,
                'modalBoasVindas' => $config->modal_boas_vindas ?? '',
                'loginImage' => $config->login_image ?? null,
                'registerImage' => $config->register_image ?? null,
                'homeMode' => $config->home_mode ?? 'slider',
                'sliderImages' => $config->slider_images ?? [],
                'homeImage' => $config->home_image ?? null,
                'homeTitle' => $config->home_title ?? '',
                'sistemaTipo' => $config->sistema_tipo ?? 'passeio',
                'instagram' => $config->instagram ?? '',
                'whatsapp' => $config->whatsapp ?? '',
                'tiktok' => $config->tiktok ?? '',
                'email' => $config->email ?? '',
                'politicaPrivacidade' => $config->politica_privacidade ?? '',
                'termosCondicoes' => $config->termos_condicoes ?? '',
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


    // Busca ou cria a única configuração global
    $config = ConfiguracaoGeral::first();

    // Campos de texto e seleções
    $config->fill([
        'nome_sistema' => $request->input('nome_sistema') ?? 'Agendamento',
        'modal_boas_vindas' => $request->input('modal_boas_vindas') ?? 'Seja bem vindo',
        'home_mode' => $request->input('home_mode') ?? 'padrao',
        'home_title' => $request->input('home_title') ?? 'Passeio',
        'sistema_tipo' => $request->input('sistema_tipo') ?? 'horario',
        'instagram' => $request->input('instagram'),
        'whatsapp' => $request->input('whatsapp'),
        'tiktok' => $request->input('tiktok'),
        'email' => $request->input('email'),
        'politica_privacidade' => $request->input('politica_privacidade'),
        'termos_condicoes' => $request->input('termos_condicoes'),
        'agendamento_tipo' => $request->input('agendamento_tipo'),
        'whatsapp_numero' => $request->input('whatsapp_numero'),
    ]);

    // Upload da imagem de login
    if ($request->hasFile('login_image')) {
        $config->login_image = $request->file('login_image')->store('configuracoes', 'public');
    
    }

    // Upload da imagem de registro
    if ($request->hasFile('register_image')) {
        $config->register_image = $request->file('register_image')->store('configuracoes', 'public');
    }

    // Upload da imagem de logo header
    if ($request->hasFile('logo_header')) {
        $config->logo_header = $request->file('logo_header')->store('configuracoes', 'public');
              
    }

    // Upload da imagem de logo footer
    if ($request->hasFile('logo_footer')) {
        $config->logo_footer = $request->file('logo_footer')->store('configuracoes', 'public');
    }

    // Upload da imagem padrão da home
    if ($request->hasFile('home_image')) {
        $config->home_image = $request->file('home_image')->store('configuracoes', 'public');
    }

    // Upload múltiplo do slider
    if ($request->hasFile('slider_images')) {
        $paths = [];
        foreach ($request->file('slider_images') as $file) {
            $paths[] = $file->store('configuracoes', 'public');
        }
        $config->slider_images = $paths;
    }

    $config->save();

    return redirect()->back()->with('success', 'Configurações do sistema atualizadas com sucesso!');
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
