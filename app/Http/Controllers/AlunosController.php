<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlunosController extends Controller
{
    protected $pageTitle = "Alunos TESTE";
    protected $view = "Aluno";
    protected $route = "Aluno";

    public function __construct()
    {
    }

    public function index()
    {
        return view(
            'admin.alunos.index',
            ['pageTitle' => $this->pageTitle]
        );
    }


    public function create()
    {
        return view(
            'admin.alunos.create',
            ['pageTitle' => $this->pageTitle]
        );
    }

    public function show($id)
    {
        return view(
            'admin.alunos.show',
            ['pageTitle' => $this->pageTitle]
        );
    }
}
