<?php

namespace App\View\Components;

use App\Models\Professor;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AlunosComponent extends Component
{
    public $alunos;

    /**
     * Create a new component instance.
     */
    public function __construct($professorId)
    {
        $professor = Professor::find($professorId);

        if ($professor) {
            $this->alunos = $professor->alunos;
        } else {
            $this->alunos = collect();
        }
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alunosComponent');
    }
}
