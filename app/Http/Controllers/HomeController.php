<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Aulas;
use App\Models\Disponibilidade;
use App\Models\Empresa;
use App\Models\Professor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $pageTitle = "Aluno";
    protected $view = "public.home";
    protected $route = "alunos";
    protected $model;
    protected $aulas;
    protected $disponibilidade;
    protected $agendamento;

    public function __construct(
        Empresa $model,
        Aulas $aulas,
        Disponibilidade $disponibilidade,
        Agendamento $agendamento

    ) {
        $this->model = $model;
        $this->aulas = $aulas;
        $this->disponibilidade = $disponibilidade;
        $this->agendamento = $agendamento;
    }

    public function index()
    {
        $model = $this->model::with('endereco', 'galeria')->get();


        return view(
            $this->view . '.index',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'model' =>  $model
            ]
        );
    }

    public function show($id)
    {
        $model = $this->model->where('user_id', $id)->first();

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
        $model = $this->model::with('servicos')->where('user_id', $id)->first();


        $aulas = $this->aulas->where('professor_id', $id)->get();

        // Array de dias da semana
        $diasDaSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

        $aulasDias = $aulas->map(function ($aula) use ($diasDaSemana) {
            $aula->dia_id = $diasDaSemana[$aula->dia_id - 1];
            return $aula;
        });

        // Inicializar o array de horários
        $horarios = [];
        foreach ($diasDaSemana as $dia) {
            $horarios[$dia] = [];
        }


        return view(
            $this->view . '.booking',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'aulasDias' => $aulasDias,
                'model' => $model,
                'horarios' => $horarios, // Passar os horários para a view
            ]
        );
    }

    public function checkout($user_id)
    {
        $model = $this->model->where('user_id', $user_id)->first();


        return view(
            $this->view . '.checkout',
            [

                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'model'  => $model
            ]
        );
    }

    public function checkoutAuth($user_id)
    {
        $model = $this->model->where('user_id', $user_id)->first();
        return view(
            $this->view . '.checkoutAuth',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'model'  => $model
            ]
        );
    }

    public function checkoutSucesso($user_id)
    {
        $model = $this->model->where('user_id', $user_id)->first();
        return view(
            $this->view . '.checkoutsucesso',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'model'  => $model
            ]
        );
    }

    public function registerProf()
    {
        return view(
            'public.registrar.registerProf',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route
            ]
        );
    }

    public function registerAluno()
    {
        return view(
            'public.registrar.registerAluno',
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
