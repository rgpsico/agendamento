<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\AlunoEndereco;
use App\Models\AlunoProfessor;
use App\Models\Alunos;
use App\Models\Professor;
use App\Models\Usuario;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $professor_id = Auth::user()->professor->id;

        $professor = Professor::with('alunos.usuario')->find($professor_id);

        $model = $professor->alunos;

        return view(
            $this->view . '.index',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'model' => $model
            ]
        );
    }





    public function create()
    {

        $fillable = [
            'nome',
            'email',
            'tipo_usuario',
            'data_nascimento',
            'telefone',
            'cep',
            'rua',
            'cidade',
            'estado',
            'numero'
        ];



        return view(
            $this->view . '.create',
            [
                'pageTitle' => $this->pageTitle,
                'model' => [],
                'fillable' => $fillable
            ]
        );
    }

    public function edit($id)
    {

        $model = $this->model::findOrFail($id);

        $fillable = [
            'nome',
            'email',
            'tipo_usuario',
            'data_nascimento',
            'telefone',
            'cep',
            'rua',
            'cidade',
            'estado',
            'numero'
        ];

        return view(
            $this->view . '.create',
            [
                'pageTitle' => $this->pageTitle,
                'model' => $model,
                'fillable' => $fillable
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
        $professor_id = Auth::user()->professor->id;
        // Updating User



        $dataNascimentoFormatada = DateTime::createFromFormat('d/m/Y', $request['data_nascimento'])->format('Y-m-d');
        $request['data_nascimento'] = $dataNascimentoFormatada;
        $user = Usuario::updateOrCreate(
            ['id' => $id],
            $request->only('nome', 'email', 'password', 'tipo_usuario', 'data_nascimento', 'telefone')
        );

        //Updating or Creating Aluno (Student)
        $student = Alunos::updateOrCreate(
            ['usuario_id' => $user->id],
            $request->only('...')
        );

        $request['professor_id'] = $professor_id;

        $professorAluno = AlunoProfessor::create([
            'aluno_id' => $student->id,
            'professor_id' => $request->professor_id
        ]);




        // Updating or Creating AlunoEndereco (StudentAddress)
        // $studentAddress = AlunoEndereco::updateOrCreate(
        //     ['aluno_id' => $id],
        //     $request->only('endereco', 'cidade', 'estado', 'cep')
        // );

        return redirect()->back()->with(['success' => 'Atualizado com sucesso']);
    }

    public function store(Request $request)
    {
        $professor_id = Auth::user()->professor->id;


        $request->validate([
            'nome' => 'required',
            // 'sobreNome' => 'required',
            // 'nascimento' => 'required|date',
            'email' => 'required|email',
            'cep' => 'required',
            'rua' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'numero' => 'required',
            // 'password' => 'required'
        ]);

        if (!$request->password) {
            $request['password'] = '124';
        }
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

        $professorAluno = AlunoProfessor::create(
            ['aluno_id' => $student->id, 'professor_id' => $professor_id],

        );

        return redirect()->route('alunos.index')->with(['success' => 'Atualizado com Sucesso']);
    }

    public function destroy($id)
    {
        $professor_id = Auth::user()->professor->id;

        $alunoProfessor = AlunoProfessor::where('aluno_id', $id)->where('professor_id', $professor_id)->first();

        if ($alunoProfessor) {
            $alunoProfessor->delete();
            return redirect()->route($this->route . '.index')->with('success', 'Aluno desassociado com sucesso!');
        } else {
            return redirect()->route($this->route . '.index')->with('error', 'Associação entre aluno e professor não encontrada.');
        }
    }
}
