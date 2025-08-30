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
    }

    public function render()
    {
        return view('components.breadcrumb');
    }
}
