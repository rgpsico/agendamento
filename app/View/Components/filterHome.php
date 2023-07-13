<?php

namespace App\View\Components;

use App\Models\Modalidade;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class filterHome extends Component
{
    protected $modalidade;
    /**
     * Create a new component instance.
     */
    public function __construct(Modalidade $modalidade)
    {
        $this->modalidade = $modalidade;
    }



    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        $modalidade = $this->modalidade->all();

        return view(
            'components.filter-home',
            ['modalidade' => $modalidade]
        );
    }
}
