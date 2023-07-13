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
    public $model;

    public function __construct(Modalidade $modalidades, $label, $model)
    {
        $this->modalidades = $modalidades->select('nome')->distinct()->get();
        $this->label = $label;
        $this->model = $model;
    }

    public function render()
    {
        return view(
            'components.select-modalidade',
            [
                'modalidades' => $this->modalidades
            ]
        );
    }
}
