<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\AlunoEndereco;
use App\Models\Alunos;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AlunosController extends Controller
{
    protected $pageTitle = "Aluno";
    protected $view = "admin.escola.aluno";
    protected $route = "alunos";
    protected $model;

    public function __construct(Usuario $model)
    {
        $this->model = $model;
    }

    public function index()
    {

        $alunos = $this->model::alunos()->get();
        return view(
            $this->view . '.index',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'model' => $alunos
            ]
        );
    }


    public function create()
    {


        return view(
            $this->view . '.create',
            [
                'pageTitle' => $this->pageTitle,
                'model' => []
            ]
        );
    }

    public function show($id)
    {
        $model = $this->model::findOrFail($id);

        return view(
            $this->view . '.show',
            [
                'pageTitle' => $this->pageTitle,
                'model' => $model
            ]
        );
    }


    public function update(Request $request, $id)
    {


        // Updating User
        $user = Usuario::updateOrCreate(
            ['id' => $id],
            $request->only('nome', 'password', 'tipo_usuario', 'data_nascimento', 'telefone')
        );

        //Updating or Creating Aluno (Student)
        $student = Alunos::updateOrCreate(
            ['usuario_id' => $user->id],
            $request->only('...')
        );

        // Updating or Creating AlunoEndereco (StudentAddress)
        $studentAddress = AlunoEndereco::updateOrCreate(
            ['aluno_id' => $id],
            $request->only('endereco', 'cidade', 'estado', 'cep')
        );

        return response()->json(['message' => 'Dados atualizados com sucesso!']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'sobreNome' => 'required',
            'nascimento' => 'required|date',
            'email' => 'required|email',
            'cep' => 'required',
            'rua' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'numero' => 'required',
            'password' => 'required'
        ]);

        $request['tipo_usuario'] = 'Aluno';
        // Atualizando ou Criando o Usuário
        $user = Usuario::updateOrCreate(
            ['email' => $request->email],
            $request->only('nome', 'sobreNome', 'nascimento', 'password', 'tipo_usuario')
        );

        // Atualizando ou Criando o Aluno
        $student = Alunos::updateOrCreate(
            ['usuario_id' => $user->id],
            $request->only('...')
        );


        $request['endereco'] = $request->rua . ' ' . $request->numero . ' ' . $request->cidade;

        // Atualizando ou Criando o Endereço do Aluno
        $studentAddress = AlunoEndereco::updateOrCreate(
            ['aluno_id' => $student->id],
            $request->only('rua', 'cidade', 'estado', 'cep', 'numero', 'endereco')
        );

        return response()->json([
            'message' => 'Dados inseridos/atualizados com sucesso!',
            'aluno' => $user // Aqui, estou supondo que $user contém os dados do novo aluno
        ]);
    }
}
