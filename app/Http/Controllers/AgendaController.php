<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Agendamento;
use App\Models\Alunos;
use App\Models\Empresa;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    protected $pageTitle = "Agenda";
    protected $view = "admin.escola.agenda";
    protected $route = "agenda";
    protected $model;

    public function __construct(Agendamento $model)
    {
        $this->model =  $model;
    }

    public function index()
    {
        if (isset(Auth::user()->professor->id)) {
            $professor_id = Auth::user()->professor->id; // suponho que o professor esteja logado.

            // Obtenha o professor junto com seus agendamentos
            $professor = Professor::with('agendamentos.aluno', 'agendamentos.modalidade')->find($professor_id);
        }
        return view(
            $this->view . '.index',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'model' => $professor ?? ''
            ]
        );
    }

    public function treino()
    {
        $professor_id = Auth::user()->professor->id; // suponho que o professor esteja logado.

        // Obtenha o professor junto com seus agendamentos
        $model = Empresa::get();

        return view(
            $this->view . '.treino',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'model' => $model
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

    public function store(PaymentRequest $request)
    {


        // Se a validação falhar, o usuário será redirecionado de volta ao formulário com erros de validação.

        // Crie o novo usuário
        $user = new Usuario();
        $user->nome = $request->nome;
        // $user->sobre_nome = $request->sobre_nome;
        $user->email = $request->email;
        $user->password = '12456';
        $user->tipo_usuario = '1';
        // $user->telefone = $request->telefone;
        $user->save();

        $alunos = new Alunos();
        $alunos->usuario_id = $user->id;
        $alunos->save();

        return redirect()->back()->with(['success' => 'Feito com Sucesso']);
    }

    public function show($id)
    {
        return view(
            $this->view . '.show',
            ['pageTitle' => $this->pageTitle]
        );
    }

    public function edit($id)
    {
        $model = $this->model->find($id);


        return view(
            $this->view . '.create',
            [
                'pageTitle' => $this->pageTitle,
                'model' => $model,
                'view' => $this->view,
                'route' => $this->route
            ]
        );
    }

    public function destroy($id)
    {
        $model = $this->model->find($id);
        if ($model) {
            $model->delete();
            return redirect()->route($this->route . '.index')->with('success', 'Aula Excluida com sucesso!');
        } else {
            return redirect()->route($this->route . '.index')->with('error', 'Aula não encontrada.');
        }
    }
}
