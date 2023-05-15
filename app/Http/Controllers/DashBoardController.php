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

    public function index()
    {
        return view('admin.dashboard.index');
    }

    public function dashboardAlunos()
    {
        return view(
            'admin.dashboard.index',
            [
                'pageTitle' => $this->pageTitle
            ]
        );
    }
}
