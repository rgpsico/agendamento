<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TextInput extends Component
{
    public $name;
    public $size;
    public $value; // Nova variável
    public $label; // Nova variável
    public $type; // Nova variável

    public $placeholder; // Nova variável
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $name, $size = '100%', $value = null, $placeholder = null, $type = 'text') // Valor padrão como null
    {
        $this->name = $name;
        $this->size = $size;
        $this->value = $value;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.text-input');
    }
}
