<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AtendimentoWhatsApp extends Component
{
    public $numero;
    public $mensagem;

    /**
     * Create a new component instance.
     */
    public function __construct($numero, $mensagem = 'Olá! Preciso de atendimento.')
    {
        $this->numero = $numero;
        $this->mensagem = urlencode($mensagem);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.atendimento-whats-app');
    }
}
