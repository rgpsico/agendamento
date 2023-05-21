<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgendaController extends Controller
{
    protected $pageTitle = "Agenda";
    protected $view = "admin.escola.agenda";
    protected $route = "agenda";
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

    public function form()
    {
        return view(
            $this->view . '._partials.form',
            ['pageTitle' => $this->pageTitle]
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
