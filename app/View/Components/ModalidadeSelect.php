<?php

namespace App\View\Components;

use App\Models\Modalidade;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModalidadeSelect extends Component
{
    public $alunos;
    public $modalidades;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->modalidades = Modalidade::all();
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modalidadeselect');
    }
}
