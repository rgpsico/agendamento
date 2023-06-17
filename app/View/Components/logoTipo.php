<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class logoTipo extends Component
{
    public $imagem;
    public $altura;
    public $largura;
    /**
     * Create a new component instance.
     */
    public function __construct($imagem, $altura = '150', $largura = '150')
    {
        $this->imagem = $imagem;
        $this->altura = $altura;
        $this->largura = $largura;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.logo-tipo');
    }
}
