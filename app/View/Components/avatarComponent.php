<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class avatarComponent extends Component
{
    public $model, $label;
    /**
     * Create a new component instance.
     */
    public function __construct($model, $label = 'Logo da Escola de surf ')
    {
        $this->model = $model;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.avatar-component');
    }
}
