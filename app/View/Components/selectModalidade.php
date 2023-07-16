<?php

namespace App\View\Components;

use App\Models\Esporte;
use App\Models\Modalidade;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class selectModalidade extends Component
{
    public $modalidades;
    public $label;
    public $showCol;
    public $model;
    public function __construct(Esporte $modalidade, $label, $model)
    {
        $this->modalidades = $modalidade->select('nome', 'id')->distinct()->get();
        $this->label = $label;
        $this->model = $model;
    }

    public function render()
    {
        return view('components.select-modalidade');
    }
}
