<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Alunos;
use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $pageTitle = "Administrativo";
    protected $view = "admin.sistem";
    protected $route = "admin";
    protected $model;

    public function __construct() {}

    public function dashboard()
    {


        return view(
            $this->view . '.dashboard',
            [
                'pageTitle' => 'DashBoard',
                'view' => $this->view,
                'route' => $this->route,
            ]
        );
    }
}
