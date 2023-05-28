<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AlunosController extends Controller
{
    protected $pageTitle = "Aluno";
    protected $view = "admin.escola.aluno";
    protected $route = "alunos";
    protected $model;

    public function __construct(Usuario $model)
    {
        $this->model = $model;
    }

    public function index()
    {

        $alunos = $this->model::alunos()->get();
        return view(
            $this->view . '.index',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'model' => $alunos
            ]
        );
    }


    public function create()
    {
        return view(
            $this->view . '.create',
            ['pageTitle' => $this->pageTitle]
        );
    }

    public function show($id)
    {
        $model = $this->model::findOrFail($id);

        return view(
            $this->view . '.show',
            [
                'pageTitle' => $this->pageTitle,
                'model' => $model
            ]
        );
    }
}
