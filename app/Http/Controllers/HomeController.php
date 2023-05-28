<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $pageTitle = "Aluno";
    protected $view = "public.home";
    protected $route = "alunos";
    protected $model;

    public function __construct(Empresa $empresa)
    {
        $this->model = $empresa;
    }

    public function index()
    {

        return view(
            $this->view . '.index',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'model' => $this->model::with('endereco')->get()
            ]
        );
    }

    public function show($id)
    {

        $model = $this->model->where('uuid', $id)->first();

        return view(
            $this->view . '.show',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'model' => $model
            ]
        );
    }


    public function booking($id)
    {
        $model = $this->model->where('uuid', $id)->first();
        return view(
            $this->view . '.booking',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'model' => $model
            ]
        );
    }


    public function checkout($id)
    {
        return view(
            $this->view . '.checkout',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
            ]
        );
    }

    public function checkoutSucesso($id)
    {
        return view(
            $this->view . '.checkoutsucesso',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route
            ]
        );
    }

    public function register()
    {
        return view(
            'public.registrar.register',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route
            ]
        );
    }

    public function login()
    {
        return view(
            'public.registrar.login',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route
            ]
        );
    }
}
