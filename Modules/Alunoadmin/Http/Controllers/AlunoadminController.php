<?php

namespace Modules\Alunoadmin\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Aluno_galeria;
use App\Models\Alunos;
use App\Models\Usuario;
use App\Models\ConfiguracaoGeral;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AlunoadminController extends Controller
{
    protected $model;

    public function __construct(Usuario $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $config = ConfiguracaoGeral::first();
        $title = 'Aulas';


        if (!auth()->user()->aluno) {
            auth()->logout();
            return redirect()->back()->with('error', 'O aluno não existe.');
        }

        $id = auth()->user()->aluno->id;

        $query = Agendamento::with('professor.usuario', 'modalidade')
            ->where('aluno_id', $id);


        if ($request->filled('data')) {
            $query->whereDate('data_da_aula', $request->data);
        }


        if ($request->filled('professor')) {
            $query->whereHas('professor.usuario', function ($q) use ($request) {
                $q->where('nome', 'LIKE', '%' . $request->professor . '%');
            });
        }


        $agendamentos = $query->get();

        return view('alunoadmin::alunos.index', compact('title', 'agendamentos', 'config'));
    }








    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function fotos()
    {
        $title = 'Fotos';
        $user_id = auth()->user()->id;
        $model = Aluno_galeria::where('usuario_id', $user_id)->get();

        return view('alunoadmin::alunos.fotos', compact('title', 'model'));
    }


    public function perfil($id)
    {
        $title = 'Perfil';
        $user_id = auth()->user()->aluno->id;
        $model = Alunos::with('usuario', 'endereco')->where('id', $user_id)->first();
        return view('alunoadmin::alunos.perfil', compact('title', 'model'));
    }

    public function dashboard()
    {
        $title = 'DashBoard';
        return view('alunoadmin::alunos.dashboard', compact('title'));
    }
}
