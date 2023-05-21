<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlunosController extends Controller
{
    protected $pageTitle = "Aluno";
    protected $view = "admin.escola.aluno";
    protected $route = "alunos";
    protected $model;

    public function __construct()
    {
    }

    public function index()
    {
        return view(
            $this->view . '.index',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
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
        return view(
            $this->view . '.show',
            ['pageTitle' => $this->pageTitle]
        );
    }
}
