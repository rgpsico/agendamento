<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\DiaDaSemana;
use App\Models\Disponibilidade;
use App\Models\Empresa;
use App\Models\EmpresaEndereco;
use App\Models\EmpresaGaleria;
use App\Models\Modalidade;
use App\Models\Professor;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    protected $pageTitle = "Empresa TESTE";
    protected $view = "empresas";
    protected $route = "empresa";



    public function __construct(
        Empresa $model,
        Usuario $usuario,
        Agendamento $agendamento,
        Professor $professor

    ) {
    }


    public function dashboard()
    {
        if (isset(Auth::user()->professor->id)) {
            $professor_id = Auth::user()->professor->id;

            $professor = Professor::find($professor_id);
            $numeroTotalDeAlunos = $professor->alunos->count();
            $numeroTotalDeAulas = $professor->agendamentos->count();

            $arrecadacao = Agendamento::where('professor_id', $professor_id)->sum('valor_aula');
            $aulasCanceladas = Agendamento::where('professor_id', $professor_id)->where('status', 'cancelada')->count();
            $aulasFeitas = Agendamento::where('professor_id', $professor_id)->where('status', 'realizadas')->count();

            $arrecadacaoUltimos30Dias = Agendamento::where('professor_id', $professor_id)
                ->whereDate('data_da_aula', '>=', Carbon::now()->subDays(30))
                ->sum('valor_aula');
        }


        return view(
            'admin.empresas.dashboard',
            [
                'pageTitle' => 'DashBoard',
                'numeroTotalDeAlunos' => $numeroTotalDeAlunos ?? '10',
                'arrecadacao' => $arrecadacao ?? '',
                'aulasCanceladas' => $aulasCanceladas ?? '',
                'arrecadacaoUltimos30Dias' => $arrecadacaoUltimos30Dias ?? '',
                'realizadas' =>  $aulasFeitas ?? '',
                'numero_total_de_aulas' => $numeroTotalDeAulas
            ]
        );
    }



    private function loadView($viewSuffix = 'index', $data = [])
    {
        $defaultData = [
            'pageTitle' => $this->pageTitle,
            'route' => $this->route
        ];

        $mergedData = array_merge($defaultData, $data);

        return view('admin.' . $this->view . '.' . $viewSuffix, $mergedData);
    }


    public function update(Request $request)
    {
        $data = $request->validate([
            'avatar' => 'nullable|image|max:2048',
            'nome' => 'required|max:255',
            'descricao' => 'required',
            'telefone' => 'required',
            'cnpj' => 'required',

            'valor_aula_de' => 'required',
            'valor_aula_ate' => 'required',
            'modalidade_id' => 'required',
        ]);


        $data = $request->all();

        $data['user_id'] = $request->user_id;

        // Processar o arquivo de avatar, se houver 

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = public_path('/avatar');
            $file->move($path, $filename);
            $data['avatar'] = $filename;
        }

        if ($request->hasFile('banner')) {

            $file = $request->file('banner');
            $filenameBanners = time() . '.' . $file->getClientOriginalExtension();
            $path = public_path('/banner');
            $file->move($path, $filenameBanners);
            $data['banners'] = $filenameBanners;
        }


        // Atualizar a empresa existente ou criar uma nova
        $empresa = Empresa::updateOrCreate(
            ['user_id' => $data['user_id']],
            $data
        );

        return redirect()->route('empresa.configuracao', ['userId' => $request->user_id])->with('success', 'Empresa atualizada ou criada com sucesso');
    }

    public function endereco_update(Request $request)
    {
        $data = $request->validate([
            'empresa_id' => 'required',
            'cep' => 'required',
            'endereco' => 'required',
            'cidade' =>  'required',
            'estado' => 'required',
            'uf' => 'required',
            'pais' => 'required',

        ]);


        $data = $request->all();
        $data['empresa_id'] = $request->empresa_id;

        $empresa = EmpresaEndereco::updateOrCreate(
            ['empresa_id' => $data['empresa_id']],
            $data
        );

        return redirect()->back()->with('success', 'Endereço atualizado com sucesso');
    }


    public function index()
    {

        return $this->loadView();
    }

    public function create()
    {
        return $this->loadView('create');
    }



    public function disponibilidade()
    {
        $diaDaSemana = DiaDaSemana::all();

        $id_professor = Auth::user()->professor->id;
        $disponibilidades = Disponibilidade::where('id_professor', $id_professor)->get();

        $horaInicio = $disponibilidades->first()->hora_inicio ?? null;
        $horaFim = $disponibilidades->first()->hora_fim ?? null;

        $mesmoHorario = $disponibilidades->every(function ($disponibilidade) use ($horaInicio, $horaFim) {
            return $disponibilidade->hora_inicio == $horaInicio && $disponibilidade->hora_fim == $horaFim;
        });

        // Busca as disponibilidades do professor
        return view(
            'admin.empresas.disponibilidade',
            [
                'pageTitle' => 'Disponibilidade',
                'diaDaSemana' => $diaDaSemana,
                'disponibilidades' => $disponibilidades,
                'mesmoHorario' => $mesmoHorario,
                'horaInicio' => $horaInicio,
                'horaFim' => $horaFim
            ]
        );
    }

    public function cadastrarDisponibilidade(Request $request)
    {
        $dias = $request->input('dias');
        $hora_inicio = $request->input('start');
        $hora_fim = $request->input('end');

        for ($i = 0; $i < count($dias); $i++) {
            // verifica se o horário de início e fim estão definidos para o dia atual
            if (!empty($hora_inicio[$i]) && !empty($hora_fim[$i])) {
                Disponibilidade::updateOrCreate(
                    ['id_professor' => $request->input('professor_id'), 'id_dia' => $dias[$i]],
                    ['hora_inicio' => $hora_inicio[$i], 'hora_fim' => $hora_fim[$i]]
                );
            }
        }

        return back()->with('success', 'Disponibilidade atualizada com sucesso');
    }


    public function show($id)
    {
        return $this->loadView('show');
    }

    public function agendatore(Request $request)
    {



        // Validação dos dados recebidos do formulário
        $validatedData = $request->validate([
            'aluno_id' => 'required|numeric',
            'modalidade_id' => 'required|numeric',
            'professor_id' => 'required|numeric',
            'data_da_aula' => 'required',
            'valor_aula' => 'required|numeric',
            'horario_aula' => 'required'
        ]);

        // Pegar a data da aula do formulário
        $dataAula = $validatedData['data_da_aula'];



        $validatedData['horario'] = $validatedData['horario_aula'];


        // Converter a data da aula para o formato americano (ISO 8601)
        $dataAulaFormatoAmericano = date('Y-m-d', strtotime($dataAula));

        // Substituir a data da aula no array de dados validados
        $validatedData['data_da_aula'] = $dataAulaFormatoAmericano;

        // Criação da nova aula com os dados validados
        $aula = Agendamento::create($validatedData);

        // Redireciona para alguma rota ou página após a criação
        return redirect()->back()->with('success', 'Aula criada com sucesso!');
    }

    public function agendaUpdate(Request $request, $id)
    {
        // Validação dos dados recebidos do formulário
        $validatedData = $request->validate([
            'aluno_id' => 'required|numeric',
            'modalidade_id' => 'required|numeric',
            'professor_id' => 'required|numeric',
            'data_da_aula' => 'required|date',
            'valor_aula' => 'required|numeric',
            'horario' => 'required'
        ]);

        // Busca a aula pelo ID fornecido
        $agendamento = Agendamento::findOrFail($id);

        // Atualiza os dados da aula com os dados validados
        $agendamento->update($validatedData);

        // Redireciona para alguma rota ou página após a atualização
        return redirect()->back()->with('success', 'Aula atualizada com sucesso!');
    }



    public function configuracao($userId)
    {
        $model = Empresa::where('user_id', $userId)->first();
        $modalidades = Modalidade::all();
        return view(
            'admin.empresas.treinoform',
            [
                'pageTitle' =>  'Configuração',
                'model' => $model,
                'modalidades' => $modalidades
            ]
        );
    }

    public function endereco($userId)
    {
        $model = Empresa::where('user_id', $userId)->first();

        return view(
            'admin.empresas.endereco',
            [
                'pageTitle' =>  'Editar Endereço',
                'model' => $model
            ]
        );
    }




    public function fotos()
    {

        $empresa_id = Auth::user()->empresa->id;
        $model = EmpresaGaleria::where('empresa_id', $empresa_id)->get();
        return view(
            'admin.empresas.fotos',
            [
                'pageTitle' =>  'Fotos',
                'model' => $model
            ]
        );
    }


    public function uploadImage(Request $request, EmpresaGaleria $empresaGaleria)
    {

        $images = $request->file('image');

        $empresa_id = Auth::user()->empresa->id;

        // Limita a quantidade de fotos a 5
        if (count($images) > 5) {
            return back()->with('error', 'Você pode enviar no máximo 5 imagens.');
        }

        $numeroDeImagens = EmpresaGaleria::where('empresa_id', $request->empresa_id)->count();

        if ($numeroDeImagens >= 5) {
            return back()->with('error', 'O maximo de imagens que voce pode ter são cinco imagens');
        }


        if ($request->hasfile('image') && $request->empresa_id) {

            foreach ($request->file('image') as $key => $image) {
                $name = time() . $key . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/galeria_escola');
                $image->move($destinationPath, $name);

                $empresaGaleria = new EmpresaGaleria();  // supondo que EmpresaGaleria é seu modelo para a galeria
                $empresaGaleria->image = $name;
                $empresaGaleria->empresa_id = $empresa_id;
                $empresaGaleria->save();
            }
        }

        return back()->with('success', 'Image Enviada com sucesso');
    }

    public function destroy($id)
    {
        $image = EmpresaGaleria::find($id);

        if ($image) {
            // Remove o arquivo de imagem do servidor
            $imagePath = public_path('galeria_escola/' . $image->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Exclui a imagem do banco de dados
            $image->delete();

            return back()->with('success', 'Imagem excluída com sucesso.');
        } else {
            return back()->with('error', 'Imagem não encontrada.');
        }
    }
}
