<?php
namespace App\View\Components;

use App\Models\Modalidade;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModalidadeSelect extends Component
{
    public $modalidades;
    public $selectedModalidade;

    /**
     * Create a new component instance.
     */
    public function __construct($modalidadeId = null)
    {
        $this->modalidades = Modalidade::all();
        $this->selectedModalidade = $modalidadeId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        dd("aaaaa");
        return view('components.modalidadeselect');
    }
}
