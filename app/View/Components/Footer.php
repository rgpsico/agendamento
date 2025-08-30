<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\ConfiguracaoGeral;

class Footer extends Component
{
    public $config;

    public function __construct()
    {
        $this->config = ConfiguracaoGeral::first();
    }

    public function render()
    {
        return view('components.footer');
    }
}
