<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class inputApiValidation extends Component
{
    /**
     * Create a new component instance.
     */

    public $name, $label, $placeholder, $col, $value;

    public function __construct($name, $label, $placeholder, $col = '4', $value)
    {
        $this->name = $name;
        $this->label = $label;
        $this->placeholder  = $placeholder;
        $this->col = $col;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input-api-validation');
    }
}
