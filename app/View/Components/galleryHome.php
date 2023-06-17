<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class galleryHome extends Component
{

    public $value;
    public $model;
    /**
     * Create a new component instance.
     */
    public function __construct($value, $model = null)
    {
        $this->value = $value;
        $this->model = $model;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        return view('components.gallery-home');
    }
}
