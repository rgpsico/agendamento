<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class textAreaComponent extends Component
{
    protected $label, $message, $model, $name;
    /**
     * Create a new component instance.
     */
    public function __construct($label, $message, $name, $model = null)
    {
        $this->label = $label;
        $this->name = $name;
        $this->message = $message;
        $this->model = $model;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.text-area');
    }
}
