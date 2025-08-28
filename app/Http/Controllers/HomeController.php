<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Alunos;
use App\Models\Aulas;
use App\Models\Bairros;
use App\Models\Configuracao;
use App\Models\Disponibilidade;
use App\Models\Empresa;
use App\Models\Modalidade;
use App\Models\PagamentoGateway;
use App\Models\Professor;
use App\Models\Usuario;
use App\Models\ConfiguracaoGeral;
use App\Models\PaymentConfiguration;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class HomeController extends Controller
{

    protected $pageTitle = "Aluno";
    protected $view = "public.home";
    protected $route = "alunos";
    protected $model;
    protected $aulas;
    protected $disponibilidade;
    protected $agendamento;
    protected $professor;
    public function __construct(
        Empresa $model,
        Aulas $aulas,
        Disponibilidade $disponibilidade,
        Agendamento $agendamento,
        Professor $professor

    ) {
        $this->model = $model;
        $this->aulas = $aulas;
        $this->disponibilidade = $disponibilidade;
        $this->agendamento = $agendamento;
        $this->professor = $professor;
    }

    public function index()
    {
       
        $model = $this->model::with('modalidade', 'endereco', 'galeria', 'avaliacao')->where('status', 'ativo')->get();
        $modalidade = Modalidade::all();
        $bairros = Bairros::all();

        // Adiciona configuração de agendamento para cada empresa
        foreach ($model as $empresa) {

            $empresa->tipoAgendamento = Configuracao::get($empresa->id, 'agendamento_tipo', 'horarios');
            $empresa->whatsappNumero = Configuracao::get($empresa->id, 'whatsapp_numero', '');
        }

        return view(
            $this->view . '.index',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'model' =>  $model,
                'modalidade' => $modalidade,
                'bairros' => $bairros
            ]
        );
    }


    public function show($id)
    {
        $model = $this->model->where('user_id', $id)->first();

        // Obtendo a configuração de agendamento da empresa
        $tipoAgendamento = Configuracao::get($model->id, 'agendamento_tipo', 'horarios');
        $whatsappNumero = Configuracao::get($model->id, 'whatsapp_numero', '');

        return view(
            $this->view . '.show',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'model' => $model,
                'tipoAgendamento' => $tipoAgendamento,
                'whatsappNumero' => $whatsappNumero
            ]
        );
    }



    public function booking($id)
    {



        $model = $this->model::with('servicos')->where('user_id', $id)->first();


        $aulas = $this->aulas->where('professor_id', $id)->get();


        $professor_id = $this->professor->where('usuario_id', $id)->value('id');


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
                'professor_id' => $professor_id
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
        $model = $this->model::with('paymentGateways')->where('user_id', $user_id)->first();
        $professor_id = Professor::with('usuario')->where('usuario_id', $user_id)->first();


        // Buscar configuração de pagamento da empresa
        $paymentConfig = PaymentConfiguration::getForEmpresa($user_id);

        // Obter métodos de pagamento habilitados
        $formasPagamento = $paymentConfig->getEnabledPaymentMethods();

        // Buscar token do gateway
        $token_gateway = PagamentoGateway::where('empresa_id', $model->id)
            ->where('status', 1)
            ->value('api_key');

        return view(
            $this->view . '.checkoutAuthAsaas',
            [
                'pageTitle' => $this->pageTitle,
                'token_gateway' => $token_gateway,
                'route' => $this->route,
                'model' => $model,
                'professor' => $professor_id,
                'user_id' => $user_id,
                'view' => $this->view,
                'formasPagamento' => $formasPagamento,
                'paymentConfig' => $paymentConfig // Passar toda a configuração para a view
            ]
        );
    }

    public function checkoutSucesso($professor_id)
    {
        $professor = Professor::with('usuario')->where('id', $professor_id)->first();

        // Buscando o último agendamento do professor e aluno.
        $agendamento = Agendamento::where('professor_id', $professor_id)->latest()->first();
        $aluno = $agendamento->aluno;  // Acessando a relação aluno no modelo Agendamento.

        return view(
            $this->view . '.checkoutsucesso',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'professor' => $professor,
                'aluno' => $aluno,
                'agendamento' => $agendamento
            ]
        );
    }


    public function registerProf()
    {


        $modalidade = Modalidade::all();
        $config = ConfiguracaoGeral::first();

        return view(
            'public.registrar.registerProf',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route,
                'modalidade' => $modalidade,
                'loginImage' => $config->login_image ? asset('storage/' . $config->login_image) : null,
                'registerImage' => $config->register_image ? asset('storage/' . $config->register_image) : null,
            ]
        );
    }

    public function registerAluno()
    {
        $modalidade = Modalidade::all();
        return view(
            'public.registrar.registerAluno',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'modalidade' => $modalidade,
                'route' => $this->route
            ]
        );
    }

    public function login()
    {
        $modalidade = Modalidade::all();
        $config = ConfiguracaoGeral::first();
        
        return view(
            'public.registrar.login',
            [
                'pageTitle' => $this->pageTitle,
                'modalidade' => $modalidade,
                'view' => $this->view,
                'loginImage' => optional($config)->login_image 
                    ? asset('storage/' . $config->login_image) 
                    : null,
                'route' => $this->route
            ]
        );
    }


    public function redirectToGoogle()
    {

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {


        try {
            $googleUser = Socialite::driver('google')->user();
            $user = Usuario::where('email', $googleUser->email)->first();

            if (!$user) {
                // Crie um novo usuário ou modifique conforme suas necessidades
                $user = Usuario::create([
                    'nome' => $googleUser->email,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'tipo_usuario' => 'Aluno',
                    'remember_token' => Str::random(60),
                    'password' => bcrypt(124)
                ]);

                Alunos::create([
                    'usuario_id' => $user->id
                ]);
            }


            Auth::login($user, true);


            $id = auth()->user()->aluno->id;

            // Obter os agendamentos para o aluno
            $agendamentos = Agendamento::with('professor')->where('aluno_id', $id)->get();

            return view('alunoadmin::alunos.index', compact('title', 'agendamentos')); // ou onde você deseja redirecionar após o login

        } catch (\Exception $e) {
            dd($e);
            // \Log::error('Erro com autenticação do Google: ' . $e->getMessage());

            dd($e);
            return redirect('/')->with('error', 'Erro ao tentar autenticar com o Google.');
        }
    }
}
