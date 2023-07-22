<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataTeste extends Component
{
    public $model;
    public $headers;
    public $actions;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($model, $headers, $actions)
    {
        $this->model = $model;
        $this->headers = $headers;
        $this->actions = $actions;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.datateste');
    }
}
