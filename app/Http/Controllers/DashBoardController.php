<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashBoardController extends Controller
{
    protected $pageTitle = "Bem vindo ";
    protected $view = "dashboard";
    protected $route = "dashboard";
    protected $breadcumb = ['dashboard', 'Users', 'Patiente'];
    protected $model;

    public function __construct(Usuario $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $activeSchools = $this->model::getActiveSchools();
        $inactiveSchools = $this->model::getInactiveSchools();
        $cancelledSchools = $this->model::getCancelledSchools();
        $totalRevenue = $this->model::getTotalRevenue();

        return view('admin.dashboard.index', compact('activeSchools'));
    }

    public function dashboardAlunos()
    {
        return view(
            'admin.dashboard.index',
            [
                'pageTitle' => $this->pageTitle . ' ' . strtoupper(Auth::user()->nome ?? '')
            ]
        );
    }
}
