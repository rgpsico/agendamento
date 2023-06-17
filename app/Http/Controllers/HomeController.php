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
        $model = $this->aulas->where('id', $id)->first();

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

        // Buscar a disponibilidade do professor para cada dia da semana
        foreach ($diasDaSemana as $index => $dia) {
            $disponibilidade = $this->disponibilidade->where('id_professores', $id)->where('id_dia', $index + 1)->first();

            if ($disponibilidade) {
                $horaInicio = intval(substr($disponibilidade->hora_inicio, 0, 2));
                $horaFim = intval(substr($disponibilidade->hora_fim, 0, 2));

                // Gerar os horários entre a hora de início e a hora de fim
                if ($horaInicio <= $horaFim) {
                    for ($i = $horaInicio; $i <= $horaFim; $i++) {
                        // Verificar se a aula já foi reservada
                        $reserva = $this->agendamento->where('professor_id', $id)->where('data_hora', $i . ':00')->first();
                        if (!$reserva) {
                            // Se a aula não foi reservada, incluir o horário na lista
                            $horarios[$dia][] = $i . ':00';
                        }
                    }
                }
            }
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
