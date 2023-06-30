<?php

namespace Modules\Alunoadmin\Http\Controllers;

use App\Models\Alunos;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AlunoadminController extends Controller
{
    protected $model;

    public function __construct(Alunos $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $title = 'Alunos';
        return view('alunoadmin::alunos.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function fotos()
    {
        $title = 'Fotos';
        return view('alunoadmin::alunos.fotos', compact('title'));
    }

    public function perfil($id)
    {
        $model = $this->model->firstOrFail($id);
        $title = 'Perfil';
        return view('alunoadmin::alunos.perfil', compact('title', 'model'));
    }

    public function dashboard()
    {
        $title = 'DashBoard';
        return view('alunoadmin::alunos.dashboard', compact('title'));
    }
}
