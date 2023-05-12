<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    protected $pageTitle = "Bem vindo Admin!";
    protected $view = "dashboard";
    protected $route = "dashboard";
    protected $breadcumb = ['dashboard', 'Users', 'Patiente'];

    public function __construct()
    {
    }


    public function dashboard()
    {
        return $this->loadView();
    }

    public function create()
    {
        return $this->loadView('create');
    }

    public function show($id)
    {
        return $this->loadView('show');
    }

    private function loadView($viewSuffix = 'index', $data = [])
    {
        $defaultData = [
            'pageTitle' => $this->pageTitle,
            'route' => $this->route,
            'breadcumb' => $this->breadcumb,
        ];

        $mergedData = array_merge($defaultData, $data);

        return view('admin.' . $this->view . '.' . $viewSuffix, $mergedData);
    }
}
