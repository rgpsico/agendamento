<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $pageTitle = "Aluno";
    protected $view = "public.home";
    protected $route = "alunos";
    protected $model;

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

    public function show($id)
    {
        return view(
            $this->view . '.show',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
            ]
        );
    }


    public function booking($id)
    {
        return view(
            $this->view . '.booking',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
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
