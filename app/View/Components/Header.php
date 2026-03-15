<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\ConfiguracaoGeral;

class Header extends Component
{
    public $config;

    public function __construct()
    {
        // Busca a primeira configuração do sistema
        $this->config = ConfiguracaoGeral::first();
    }

    public function render()
    {
        return view('components.header');
    }
}
