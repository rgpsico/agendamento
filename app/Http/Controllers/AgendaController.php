<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Alunos;
use App\Models\Empresa;
use App\Models\Usuario;
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
}
