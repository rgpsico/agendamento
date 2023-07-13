<?php

namespace App\View\Components;

use App\Models\Modalidade;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class filterHome extends Component
{
    public $modalidades;
    /**
     * Create a new component instance.
     */
    public function __construct(Modalidade $modalidades)
    {
        $this->modalidades = $modalidades;
    }



    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $modalidades = $this->modalidades->all();

        return view(
            'components.filter-home',
            ['modalidade' => $modalidades]
        );
    }
}
