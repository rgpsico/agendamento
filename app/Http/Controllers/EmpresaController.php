<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Http\Requests\ProfessorStoreRequest;
use App\Http\Requests\StoreEmpresaRequest;
use App\Models\Agendamento;
use App\Models\DiaDaSemana;
use App\Models\Disponibilidade;
use App\Models\Empresa;
use App\Models\EmpresaEndereco;
use App\Models\EmpresaGaleria;
use App\Models\Modalidade;
use App\Models\Professor;
use App\Models\Servicos;
use App\Models\Usuario;
use App\Models\Alunos;
use App\Models\Feriado;
use App\Services\ServicoService;
use App\Services\SiteService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmpresaController extends Controller
{
    protected $pageTitle = "Empresa TESTE";
    protected $view = "empresas";
    protected $route = "empresa";

    protected $siteService;

    public function __construct(SiteService $siteService)
    {
        $this->siteService = $siteService;
    }


    public function dashboard(Request $request)
    {

        if (!Auth::user()->empresa) {
            return redirect()->route('empresa.configuracao', ['userId' => Auth::user()->id]);
        }


        $professor_id = Auth::user()->professor->id ?? null;

        if (!$professor_id) {
            return redirect()->route('alunos.index')->with('error', 'Acesso negado.');
        }

        // Definir datas padrão (últimos 30 dias)
        $data_inicial = $request->input('data_inicial', Carbon::now()->subDays(30)->format('Y-m-d'));
        $data_final = $request->input('data_final', Carbon::now()->format('Y-m-d'));

        // Filtragem dos agendamentos
        $agendamentos = Agendamento::where('professor_id', $professor_id)
            ->whereBetween('data_da_aula', [$data_inicial, $data_final])
            ->get();

        // Cálculos do Dashboard
        $professor = Professor::find($professor_id);
        $numeroTotalDeAlunos = $professor->alunos->count();
        $numeroTotalDeAulas = $agendamentos->count();
        $arrecadacao = $agendamentos->sum('valor_aula');
        $aulasCanceladas = $agendamentos->where('status', 'cancelada')->count();
        $aulasFeitas = $agendamentos->where('status', 'realizada')->count();

        // Arrecadação por dia (para gráfico)
        $arrecadacaoPorDia = $agendamentos->groupBy('data_da_aula')->map(function ($day) {
            return $day->sum('valor_aula');
        });

        return view('admin.empresas.dashboard', [
            'pageTitle' => 'DashBoard',
            'numeroTotalDeAlunos' => $numeroTotalDeAlunos,
            'arrecadacao' => $arrecadacao,
            'aulasCanceladas' => $aulasCanceladas,
            'realizadas' => $aulasFeitas,
            'numero_total_de_aulas' => $numeroTotalDeAulas,
            'data_inicial' => $data_inicial,
            'data_final' => $data_final,
            'arrecadacaoPorDia' => $arrecadacaoPorDia,
            'empresa' => []
        ]);
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

    public function store(StoreEmpresaRequest $request, ServicoService $servicoService)
    {
        try {
            // Dados já validados pelo FormRequest
            $validated = $request->validated();       
        
             $CriarEmpresa = Empresa::createEmpresa($validated);
    
            if ($CriarEmpresa->user) {
                $CriarEmpresa->user->update(['email' => $validated['email']]);            }

            // Atualiza o professor para associar à empresa
            Professor::where('usuario_id', $validated['user_id'])->update([
                'empresa_id' => $CriarEmpresa->id
            ]);

           
            $UploadDoavatar = FileUploadHelper::uploadFile($request, 'avatar', 'avatar');

            if ($UploadDoavatar) {
                $CriarEmpresa->update(['avatar' => $UploadDoavatar]);
            }

            $UploadDobanner = FileUploadHelper::uploadFile($request, 'banner', 'banner');
            if ($UploadDobanner) {
                $CriarEmpresa->update(['banners' => $UploadDobanner]);
            }

            // Salva o endereço da empresa
            EmpresaEndereco::createOrUpdateEndereco($CriarEmpresa->id, $validated);
            $this->siteService->criarSiteAutomatico($CriarEmpresa->id, $validated['modalidade_id']);

            // Cria serviço automático
            
             $servicoService->criarServicoAutomatico($CriarEmpresa->id, $validated['modalidade_id']);

            return redirect()
                ->route('empresa.pagamento.boleto')
                ->with('success', 'Empresa criada com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Erro ao criar empresa: ' . $e->getMessage()])
                ->withInput();
        }
    }






    public function criarServicoAutomatico($empresa_id, $modalidade_id, $tipo_agendamento = 'dia')
    {
        // Busca a modalidade no banco pelo ID
        $modalidade = Modalidade::find($modalidade_id);

        if (!$modalidade) {
            return null; // Modalidade não encontrada
        }

        // Definir serviços padrão por nome da modalidade
        $servicosPadrao = [
            'Surf' => [
                'titulo' => 'Aula de Surf',
                'descricao' => 'Aula básica de Surf',
                'preco' => 100,
                'tempo_de_aula' => 60,
                'imagem' => 'imagens/surf_padrao.jpg',
            ],
            'BodyBoard' => [
                'titulo' => 'Aula de BodyBoard',
                'descricao' => 'Aula básica de BodyBoard',
                'preco' => 80,
                'tempo_de_aula' => 60,
                'imagem' => 'imagens/bodyboard_padrao.jpg',
            ],
            'Passeios' => [
                'titulo' => 'Passeio Guiado',
                'descricao' => 'Passeio turístico guiado',
                'preco' => 120,
                'tempo_de_aula' => 120,
                'imagem' => 'imagens/passeio_padrao.jpg',
            ],
            'Corrida' => [
                'titulo' => 'Treino de Corrida',
                'descricao' => 'Treino básico de corrida',
                'preco' => 60,
                'tempo_de_aula' => 45,
                'imagem' => 'imagens/corrida_padrao.jpg',
            ],
            'Futevôlei' => [
                'titulo' => 'Aula de Futevôlei',
                'descricao' => 'Aula básica de Futevôlei',
                'preco' => 90,
                'tempo_de_aula' => 60,
                'imagem' => 'imagens/futevolei_padrao.jpg',
            ],
        ];

        $nomeModalidade = $modalidade->nome;
        $servico = $servicosPadrao[$nomeModalidade] ?? null;

        if ($servico) {
            return Servicos::create([
                'empresa_id' => $empresa_id,
                'imagem' => $servico['imagem'],
                'titulo' => $servico['titulo'],
                'descricao' => $servico['descricao'],
                'preco' => $servico['preco'],
                'tempo_de_aula' => $servico['tempo_de_aula'],
                'tipo_agendamento' => $tipo_agendamento,
            ]);
        }

        return null;
    }

  

    public function update(Request $request)
    {
      
        // Validação dos dados
        $validatedData = $request->validate([
            'empresa_id' => 'required|exists:empresa,id',
            'avatar' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:2048',
            'nome' => 'required|max:255',
            // 'email' => [
            //     'required',
            //     'email',
            //     'max:255',
            //     Rule::unique('usuarios', 'email')->ignore($empresa->user->id ?? 0),
            // ],
            'site_url' => 'nullable|url|max:255',
            'descricao' => 'required',
            'telefone' => 'required|max:20',
            'cnpj' => 'required|max:18',
            'valor_aula_de' => 'required|numeric|min:0',
            'valor_aula_ate' => 'required|numeric|min:0',
            'modalidade_id' => 'required|exists:modalidade,id',
            'cep' => 'required',
            'endereco' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'uf' => 'required',
            'pais' => 'required',
            'data_vencimento' =>'nullable'
        ]);

        try {
            // Buscar a empresa pelo ID
            $empresa = Empresa::findOrFail($request->empresa_id);

            // Preparar dados para atualização
            $dataToUpdate = [
                'nome' => $validatedData['nome'],
                'descricao' => $validatedData['descricao'],
                'telefone' => $validatedData['telefone'],
                'cnpj' => $validatedData['cnpj'],
                'data_vencimento' => $validatedData['data_vencimento'] ?? today()->format('Y-m-d'), // Formatar data para YYYY-MM-DD
                'valor_aula_de' => $validatedData['valor_aula_de'],
                'valor_aula_ate' => $validatedData['valor_aula_ate'],
                'modalidade_id' => $validatedData['modalidade_id'],
            ];
     
            // Adicionar site_url se fornecido
            if (!empty($validatedData['site_url'])) {
                $dataToUpdate['site_url'] = $validatedData['site_url'];
            }

            // Processar upload do avatar
            if ($request->hasFile('avatar')) {
                if ($empresa->avatar && file_exists(public_path('/avatar/' . $empresa->avatar))) {
                    unlink(public_path('/avatar/' . $empresa->avatar));
                }
                $file = $request->file('avatar');
                $filename = 'avatar_' . $empresa->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = public_path('/avatar');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $file->move($path, $filename);
                $dataToUpdate['avatar'] = $filename;
            }

            // Processar upload do banner
            if ($request->hasFile('banner')) {
                if ($empresa->banner && file_exists(public_path('/banner/' . $empresa->banner))) {
                    unlink(public_path('/banner/' . $empresa->banner));
                }
                $file = $request->file('banner');
                $filenameBanner = 'banner_' . $empresa->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = public_path('/banner');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $file->move($path, $filenameBanner);
                $dataToUpdate['banner'] = $filenameBanner;
            }

            // Atualizar o email do usuário associado (se necessário)
            // if (isset($validatedData['email']) && $empresa->user && $empresa->user->email !== $validatedData['email']) {
            //     $empresa->user->update(['email' => $validatedData['email']]);
            // }

            // Atualizar a empresa
            $empresa->update($dataToUpdate);

            $this->siteService->criarSiteAutomatico($empresa->id, $validatedData['modalidade_id']);
            $criarServico = $this->criarServicoAutomatico($empresa->id, $validatedData['modalidade_id']);

            EmpresaEndereco::updateOrCreate(
                ['empresa_id' => $empresa->id],
                [
                    'cep' => $validatedData['cep'],
                    'endereco' => $validatedData['endereco'],
                    'cidade' => $validatedData['cidade'],
                    'estado' => $validatedData['estado'],
                    'uf' => $validatedData['uf'],
                    'pais' => $validatedData['pais'],
                ]
            );

            // Resposta para requisição AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Empresa atualizada com sucesso!',
                    'empresa' => $empresa->fresh()
                ]);
            }

            return redirect()->back()->with('success', 'Empresa atualizada com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar empresa: Duplicate email', [
                'empresa_id' => $request->empresa_id,
          //      'email' => $validatedData['email'],
                'error' => $e->getMessage(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar empresa: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erro ao atualizar empresa: ' . $e->getMessage())
                ->withInput();
        }
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

    public function all()
    {

        return $this->loadView();
    }

    // ALTERNATIVA 2: Usar helper request() diretamente


    // ALTERNATIVA 3: Criar método separado para filtros
    public function index()
    {
        return $this->indexWithFilters();
    }

    private function indexWithFilters()
    {
        $request = request();

        // Resto do código igual...
        $query = Empresa::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('modalidade_id')) {
            $query->where('modalidade_id', $request->modalidade_id);
        }

        if ($request->filled('status')) {
            if ($request->status == 'ativo') {
                $query->where('data_vencimento', '>=', now());
            } elseif ($request->status == 'inativo') {
                $query->where('data_vencimento', '<', now());
            }
        }

        $empresas = $query->get();
        $modalidades = Modalidade::all();
        $pageTitle = 'Empresa';
        $route = $this->route;

        return view('admin.empresas.index', compact(
            'empresas',
            'modalidades',
            'pageTitle',
            'route'
        ));
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


    public function gerarHorarios(Request $request)
    {
        $request->validate([
            'servico' => 'required|integer|exists:servicos,id',
            'duracao' => 'required|integer|min:1',
            'intervalo' => 'required|integer|min:0',
            'inicio' => 'required|date_format:H:i',
            'fim' => 'required|date_format:H:i',
            'almoco_inicio' => 'required|date_format:H:i',
            'almoco_fim' => 'required|date_format:H:i',
            'folga' => 'array',
            'folga.*' => 'integer|between:1,7',
            'feriados' => 'nullable|boolean',
        ]);

        $dados = [
            'servico' => $request->input('servico'),
            'professor_id' => Auth::user()->professor->id,
            'duracao' => $request->input('duracao'),
            'intervalo' => $request->input('intervalo'),
            'inicio' => $request->input('inicio'),
            'fim' => $request->input('fim'),
            'almoco_inicio' => $request->input('almoco_inicio'),
            'almoco_fim' => $request->input('almoco_fim'),
            'folga' => $request->input('folga', []),
            'feriados' => $request->has('feriados') && $request->feriados == '1',
        ];

        dispatch(new \App\Jobs\GerarHorariosJob($dados));

        return redirect()->back()->with('success', 'Processamento iniciado! Seus horários estão sendo gerados.');
    }





    public function autoHorario()
    {
        // Busca os dias da semana no banco
        $dias_da_semana = DiaDaSemana::all();
        $idempresa = Auth::user()->empresa->id;
        $servicos = Servicos::where('empresa_id', $idempresa)->get();

        // Retorna a view com os dados    return view('admin.horarios.auto');
        return view('admin.horarios.auto', [
            'dias_da_semana' => $dias_da_semana,
            'servicos' => $servicos
        ]);
    }

    public function disponibilidadePersonalizada()
    {

        $diaDaSemana = DiaDaSemana::all();

        $id_professor = Auth::user()->professor->id;
        $empresa_id = Auth::user()->empresa->id;
        $disponibilidades = Disponibilidade::where('id_professor', $id_professor)->get();


        $servicos = Servicos::where('empresa_id', $empresa_id)->get();

        $horaInicio = $disponibilidades->first()->hora_inicio ?? null;
        $horaFim = $disponibilidades->first()->hora_fim ?? null;

        $mesmoHorario = $disponibilidades->every(function ($disponibilidade) use ($horaInicio, $horaFim) {
            return $disponibilidade->hora_inicio == $horaInicio && $disponibilidade->hora_fim == $horaFim;
        });


        // Busca as disponibilidades do professor
        return view(
            'admin.empresas.disponibilidadepersonalizada',
            [
                'pageTitle' => 'Disponibilidade personalizada',
                'diaDaSemana' => $diaDaSemana,
                'disponibilidades' => $disponibilidades,
                'mesmoHorario' => $mesmoHorario,
                'horaInicio' => $horaInicio,
                'horaFim' => $horaFim,
                'servicos' => $servicos
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
                    [
                        'id_professor' => $request->input('professor_id'),
                        'id_servico' => 1,
                        'id_dia' => $dias[$i]
                    ],
                    ['hora_inicio' => $hora_inicio[$i], 'hora_fim' => $hora_fim[$i]]
                );
            }
        }

        return back()->with('success', 'Disponibilidade atualizada com sucesso');
    }


    public function show($id)
    {
        // Busca a empresa pelo ID
        $empresa = Empresa::with(['user', 'endereco', 'professores.alunos.usuario'])
            ->where('id', $id)
            ->firstOrFail();

        // Cria endereço vazio se não existir
        if (!$empresa->endereco) {
            $empresa->endereco = new \App\Models\EmpresaEndereco();
        }

        // Obtém todos os alunos únicos da empresa através dos professores
        $alunos = $empresa->professores()
            ->with('alunos.usuario') // Carrega os alunos e seus usuários
            ->get()
            ->pluck('alunos') // Obtém todos os alunos dos professores
            ->flatten()
            ->unique('id') // Remove duplicatas
            ->values(); // Reindexa a coleção

        return view('admin.empresas.show', compact('empresa', 'alunos'));
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
            'data_da_aula' => 'required|date_format:d/m/Y', // Ajusta a validação para o formato recebido
            'valor_aula' => 'required|numeric',
            'horario' => 'required'
        ]);

        // Converte a data para o formato do MySQL (Y-m-d)
        $validatedData['data_da_aula'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validatedData['data_da_aula'])->format('Y-m-d');

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
        try {
            $empresa = Empresa::find($id);

            if (!$empresa) {
                return back()->with('error', 'Empresa não encontrada.');
            }

            // Exclusão lógica - marca como inativa definindo data de vencimento no passado
            $empresa->update([
                'data_vencimento' => now()->subDay(), // Define para ontem
                'status' => 'inativo' // Se você tiver um campo status
            ]);

            return back()->with('success', 'Empresa desativada com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao desativar empresa: ' . $e->getMessage());
        }
    }

    // Método adicional para reativar empresa
    public function restore($id)
    {
        try {
            $empresa = Empresa::find($id);

            if (!$empresa) {
                return back()->with('error', 'Empresa não encontrada.');
            }

            // Reativa a empresa definindo nova data de vencimento
            $empresa->update([
                'data_vencimento' => now()->addYear(), // Adiciona 1 ano a partir de hoje
                'status' => 'ativo'
            ]);

            return back()->with('success', 'Empresa reativada com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao reativar empresa: ' . $e->getMessage());
        }
    }
}
