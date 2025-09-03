<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Aluno_Galeria;
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

        if (!auth()->user()->professor) {
            auth()->logout();
            return redirect()->back()->with('error', 'O Professor com esse e-mail não existe.');
        }

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


        $dataNascimentoFormatada = DateTime::createFromFormat('d/m/Y', $request['data_nascimento'])->format('Y-m-d');
        $request['data_nascimento'] = $dataNascimentoFormatada;


        $request->validate([
            'nome' => 'required',
            'telefone' => 'required',
            // 'data_nascimento' => 'required|date',
            'email' => 'required|email',
            // 'cep' => 'required',
            // 'rua' => 'required',
            // 'cidade' => 'required',
            // 'estado' => 'required',
            // 'numero' => 'required',
            // 'password' => 'required'
        ]);


        if (!$request->password) {
            $request['password'] = '124';
        }
        $request['tipo_usuario'] = 'Aluno';
        // Atualizando ou Criando o Usuário
        $user = Usuario::updateOrCreate(
            ['email' => $request->email],
            $request->only('nome', 'sobreNome', 'nascimento', 'password', 'tipo_usuario', 'telefone')
        );

        // Atualizando ou Criando o Aluno
        $student = Alunos::updateOrCreate(
            ['usuario_id' => $user->id],
            $request->only('...')
        );

        $request['rua'] = '200';
        $request['numero'] = 'saint roman ';
        $request['cidade'] = 'RJ';
        $request['estado'] = 'RJ';
        $request['cep'] = '22071060';

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

    public function destroy($id, $professor_id)
    {


        $alunoProfessor = AlunoProfessor::where('aluno_id', $id)->where('professor_id', $professor_id)->first();

        if ($alunoProfessor) {
            $alunoProfessor->delete();

            return response()->json(['message' => 'Excluído com sucesso'], 200);
        } else {

            return response()->json(['message' => 'Excluído com sucesso'], 404);
        }
    }
    public function uploadImage(Request $request, Aluno_Galeria $empresaGaleria)
    {

        $images = $request->file('image');

        $usuario_id = Auth::user()->aluno->id;

        // Limita a quantidade de fotos a 5
        if (count($images) > 5) {
            return back()->with('error', 'Você pode enviar no máximo 5 imagens.');
        }

        $numeroDeImagens = Aluno_Galeria::where('usuario_id', $request->usuario_id)->count();

        if ($numeroDeImagens >= 5) {
            return back()->with('error', 'O maximo de imagens que voce pode ter são cinco imagens');
        }



        if ($request->hasfile('image') && $request->usuario_id) {


            foreach ($request->file('image') as $key => $image) {
                $name = time() . $key . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/aluno_galeria');
                $image->move($destinationPath, $name);

                $Aluno_Galeria = new Aluno_Galeria();  // supondo que EmpresaGaleria é seu modelo para a galeria
                $Aluno_Galeria->image = $name;
                $Aluno_Galeria->usuario_id = $usuario_id;
                $Aluno_Galeria->save();
            }
        }

        return back()->with('success', 'Image Enviada com sucesso');
    }
}
