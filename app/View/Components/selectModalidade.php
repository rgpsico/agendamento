<?php

namespace App\View\Components;

use App\Models\Modalidade;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class selectModalidade extends Component
{
    public $modalidades;
    public $label;
    public $showCol;

    public function __construct(Modalidade $modalidade, $label)
    {
        $this->modalidades = $modalidade->select('nome')->distinct()->get();
        $this->label = $label;
    }

    public function render()
    {
        return view('components.select-modalidade');
    }
}
