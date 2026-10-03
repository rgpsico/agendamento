<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\ConfiguracaoGeral;

class Breadcrumb extends Component
{
    public $title;
    public $subtitle;
    public $breadcrumbs;
    public $banner;
    public $corNicho = null;

    public function __construct($title = null, $subtitle = null, $breadcrumbs = [])
    {
        $this->subtitle = $subtitle;
        $this->breadcrumbs = $breadcrumbs;

        // Pega a configuração global
        $config = ConfiguracaoGeral::first();

        // Define banner padrão ou do modelo
        $this->banner = $config && $config->home_image
            ? asset('storage/' . $config->home_image)
            : asset('admin/img/surfbread2.png');

        // Se o título não for passado, pega o home_title da configuração
        $this->title = $title ?? ($config->home_title ?? 'Home');

        // Nicho que não é o original: usa o nome e a imagem do próprio nicho, nunca a capa/título do pilates
        $nicho = app()->has('currentNicho') ? app('currentNicho') : null;
        if ($nicho && ! $nicho->usaMarcaGlobal()) {
            $this->banner = $nicho->registro_imagem_url ?? $nicho->login_imagem_url;
            $this->corNicho = $nicho->cor_primaria;
            $this->title = $title ?? $nicho->nome;
        }
    }

    public function render()
    {
        return view('components.breadcrumb');
    }
}
