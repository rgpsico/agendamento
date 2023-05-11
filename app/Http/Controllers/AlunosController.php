<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlunosController extends Controller
{
    protected $pageTitle = "Alunos TESTE";

    public function index()
    {
        return view(
            'admin.alunos.index',
            ['pageTitle' => $this->pageTitle]
        );
    }
}
