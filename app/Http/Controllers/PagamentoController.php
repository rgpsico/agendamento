<?php

namespace App\Http\Controllers;

use App\Mail\PaymentConfirmation;
use App\Models\Agendamento;
use App\Models\AlunoProfessor;
use App\Models\Alunos;
use App\Models\Empresa;
use App\Models\PagamentoGateway;
use App\Models\Professor;
use App\Services\AsaasService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Charge;
use GuzzleHttp\Client; // Importar a classe Client do Guzzle

class PagamentoController extends Controller
{
    protected $aluno_professor;
    protected $asaasService;
    protected $url;

    public function __construct(AlunoProfessor $aluno_professor, AsaasService $asaasService)
    {
        $this->aluno_professor = $aluno_professor;
        $this->asaasService = $asaasService;
        $this->url = env("ASAAS_URL");
    }

    public function index()
    {
        $empresa_id = Auth::user()->empresa->id;
        $pagamento = PagamentoGateway::where('empresa_id', $empresa_id)->get();

        return view('admin.pagamento.index', [
            'pageTitle' => 'Configurações de Pagamento',
            'model' => $pagamento,
            'route' => null
        ]);
    }

    public function create()
    {
        return view('admin.pagamento.create', [
            'pageTitle' => 'Cadastrar Gateway de Pagamento',
            'model' => null
        ]);
    }

    public function edit($pagamentoID)
    {
        $model = PagamentoGateway::where('id', $pagamentoID)->firstOrFail();

        return view('admin.pagamento.create', [
            'pageTitle' => 'Editar Gateway de Pagamento',
            'model' => $model
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|in:asaas,stripe,mercadopago',
            'api_key' => 'required|string',
            'mode' => 'required|in:sandbox,production',
            'methods' => 'array|in:pix,boleto,card',
            'split_account' => 'required|string',
            'tariff_type' => 'required|in:fixed,percentage',
            'tariff_value' => 'required|numeric|min:0',
            'status' => 'required|boolean',
        ]);

        if ($request->status == 1) {
            PagamentoGateway::where('empresa_id', Auth::user()->empresa->id)
                ->update(['status' => 0]);
        }

        PagamentoGateway::create(array_merge($validated, [
            'empresa_id' => Auth::user()->empresa->id
        ]));

        return redirect()->route('empresa.pagamento.index')->with('success', 'Gateway cadastrado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|in:asaas,stripe,mercadopago',
            'api_key' => 'required|string',
            'mode' => 'required|in:sandbox,production',
            'methods' => 'array|in:pix,boleto,card',
            'split_account' => 'required|string',
            'tariff_type' => 'required|in:fixed,percentage',
            'tariff_value' => 'required|numeric|min:0',
            'status' => 'required|boolean',
        ]);

        $pagamento = PagamentoGateway::findOrFail($id);

        if ($request->status == 1) {
            PagamentoGateway::where('empresa_id', Auth::user()->empresa->id)
                ->update(['status' => 0]);
        }

        $pagamento->update(array_merge($validated, [
            'empresa_id' => Auth::user()->empresa->id
        ]));

        return redirect()->route('empresa.pagamento.index')->with('success', 'Gateway atualizado com sucesso!');
    }

    

    public function pagamentoStripe(Request $request)
    {

        Stripe::setApiKey(env('STRIPE_SECRET'));


        $token = $request->stripeToken;

        $aluno_id = $request->aluno_id;
        $professor_id = $request->professor_id;

        $valor_aula = $request->valor_aula;

        $modalidade_id = $request->modalidade_id;

        $originalDate = $request->data_aula;
        $hora_aula = $request->hora_aula;
        $conta_professor =  $request->token_gateway;

        $professor = Professor::with('usuario.empresa')->find($professor_id);
        // Para acessar a empresa diretamente
        $empresa = null;

        $data_agendamento_formato_eua = PagamentoController::convertToUSFormat($originalDate) . ' ' . $hora_aula;

        $aluno = Alunos::find($aluno_id);
        $professor = Professor::find($professor_id);


        $professor = Professor::with('usuario.empresa')->find($professor_id);

        // Para acessar a empresa diretamente
        $empresa = null;
        if ($professor && $professor->usuario && $professor->usuario->empresa) {
            $empresa = $professor->usuario->empresa;
        }



        if (!$aluno) {
            return response()->json(['erro' => 'Aluno não encontrado'], 404);
        }

        $aluno->professores()->attach($professor);

        try {
            $charge = \Stripe\Charge::create([
                'amount' => 20000, // Alterado para 20000 centavos para corresponder ao valor em centavos
                'currency' => 'usd',
                'source' => $token,
                'description' => $request->titulo,
            ]);


            if ($charge->paid == true) {
                $conta_professor = "acct_1P23pLPi8YEsgyY7";

                $amount_brl = PagamentoController::convertToBRL($valor_aula, 'USD');

                $transfer = \Stripe\Transfer::create([
                    'amount' => $amount_brl, // Valor convertido para BRL
                    'currency' => 'brl', // Definindo a moeda como BRL
                    'destination' => $conta_professor,
                    'transfer_group' => 'pagamento_aula_' . $aluno_id,
                    'source_transaction' => $charge->id, // Adicione o ID da transação de origem
                ]);

                Agendamento::create([
                    'aluno_id' => $aluno_id,
                    'modalidade_id' => $modalidade_id, // Defina essa variável conforme necessário
                    'professor_id' => $professor_id, // Defina essa variável conforme necessário
                    'data_da_aula' => $data_agendamento_formato_eua, // Defina essa variável conforme necessário
                    'valor_aula' => $valor_aula,
                    'horario' => $hora_aula
                ]);



                Mail::to(['email' => Auth::user()->email])->send(new PaymentConfirmation(Auth::user()));
                // Aqui você pode redirecionar o usuário para uma página de agradecimento, por exemplo
                return redirect()->route('home.checkoutsucesso', ['id' =>  $professor_id]);
            } else {
                // Aqui você pode redirecionar o usuário para uma página de erro, por exemplo
                return redirect()->route('erroPagamento');
            }
        } catch (\Exception $ex) {

            return redirect()->back()->with('error', 'Ocorreu um erro ao processar o seu pagamento. Por favor, tente novamente.' . $ex);
        }
    }

    public function test(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'gateway' => 'required|in:asaas,stripe,mercadopago',
        ]);

     
        if ($request->gateway == 'asaas') {
            try {
                $this->asaasService->testConnection($request->api_key);
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        }

        return response()->json(['success' => false, 'error' => 'Gateway não suportado para teste'], 400);
    }

    public function erroPagamento()
    {
        $error = session('error') ?? 'Ocorreu um erro desconhecido ao processar o pagamento. Tente novamente.';
        return view('admin.pagamento.erro', [
            'pageTitle' => 'Erro no Pagamento',
            'error' => $error
        ]);
    }

    public function mostrarIntegracao()
    {
        // Verifica se o usuário está logado e é um professor
        if (!Auth::check() || !Auth::user()->professor) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado como professor.');
        }

        return view('admin.integracoes.escolaassas');
    }
    public function integrarAsaas(Request $request)
    {
        $professorId = $request->input('professor_id');
        $professor = Professor::with('usuario')->find($professorId);
        $asaasService = new AsaasService();

        
        if (!$professor || !$professor->usuario) {
            return response()->json(['success' => false, 'message' => 'Professor não encontrado.'], 400);
        }
    
        $gateway = PagamentoGateway::where('name', 'asaas')->where('status', 1)->first();
        if (!$gateway) {
            return response()->json(['success' => false, 'message' => 'Gateway Asaas não configurado.'], 400);
        }

       return  $wallet = $this->getCustomerWallet('cus_000006716270', $gateway->api_key, $gateway->mode);
     
        // $clienteData = [
        //     'name' => $professor->usuario->nome,
        //     'email' => $professor->usuario->email ?? 'teste@12212.com',
        //     'cpfCnpj' => $professor->usuario->cpf ?? '71180274059',
        //     'phone' => $professor->usuario->telefone ?? '21987654321',
        //     // Adicionar campos adicionais que possam ajudar na ativação da conta
        //     'mobilePhone' => $professor->usuario->celular ?? $professor->usuario->telefone ?? '21987654321',
        //     'notificationDisabled' => false,
        // ];
    
        
    }
    
    public function getCustomerWallet($customerId, $apiKey, $mode)
    {
        $client = new Client();
        $url = ($mode === 'sandbox' ? 'https://sandbox.asaas.com' : 'https://api.asaas.com') . "/api/v3/customers/{$customerId}";

        // Definir os headers diretamente no método
        $headers = [
            'access_token' => $apiKey,
            'Content-Type' => 'application/json',
        ];

        \Log::info('Headers enviados para getCustomerWallet: ' . json_encode($headers));
        \Log::info('URL da requisição: ' . $url);

        try {
            $response = $client->request('GET', $url, [
                'headers' => $headers, // Usar $headers diretamente, sem $this->headers
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody(), true);

            \Log::info('Resposta do Asaas para getCustomerWallet: ' . json_encode($body));

            return ['walletId' => $body['walletId'] ?? null];
        } catch (\Exception $e) {
            \Log::error('Erro ao obter wallet: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Método para tentar encontrar um cliente pelo CPF/CNPJ
     */
    public function findCustomerByCpfCnpj($cpfCnpj, $apiKey, $mode)
    {
        $client = new Client();
        $url = rtrim($this->url, '/') . "/api/v3/customers?cpfCnpj=" . urlencode($cpfCnpj);
    
        try {
            $response = $client->request('GET', $url, [
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);
    
            $result = $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));
            \Log::info('Resposta da busca de cliente por CPF/CNPJ: ' . json_encode($result));
    
            // Verificar se existe algum cliente na resposta
            if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
                return $result['data'][0]; // Retorna o primeiro cliente encontrado
            }
    
            return null;
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar cliente por CPF/CNPJ: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Método para tentar criar uma carteira para o cliente (caso o Asaas tenha um endpoint específico para isso)
     */
    public function createCustomerWallet($customerId, $apiKey, $mode)
    {
        $client = new Client();
        // Verifique na documentação do Asaas se existe um endpoint específico para criar carteiras
        $url = rtrim($this->url, '/') . "/api/v3/customers/{$customerId}/wallet";
    
        try {
            $response = $client->request('POST', $url, [
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
                'json' => [
                    'customerId' => $customerId,
                    'active' => true
                ]
            ]);
    
            $wallet = $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));
            \Log::info('Resposta da criação de wallet: ' . json_encode($wallet));
    
            return $wallet;
        } catch (\Exception $e) {
            \Log::error('Erro ao criar wallet: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteAllPayments($apiKey, $mode)
    {
        
        $client = new Client();
        $url = rtrim($this->url, '/') . '/api/v3/payments';

        $response = $client->request('GET', $url, [
            'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
        ]);

        $payments = json_decode($response->getBody(), true)['data'] ?? [];
        foreach ($payments as $payment) {
            $client->request('DELETE', $url . '/' . $payment['id'], [
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);
            \Log::info('Cobrança excluída: ' . $payment['id']);
        }
    }

    public function pagamentoAsaas(Request $request)
    {
        $validated = $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'professor_id' => 'required|exists:professores,id',
            'valor_aula' => 'required|numeric|min:0',
            'modalidade_id' => 'required|exists:modalidade,id',
            'data_aula' => 'required|string',
            'hora_aula' => 'required|string',
            'titulo' => 'required|string',
        ]);

        $aluno = Alunos::with('usuario')->find($validated['aluno_id']);
        $professor = Professor::with('usuario')->find($validated['professor_id']);

        if (!$aluno || !$professor || !$professor->usuario || !$aluno->usuario) {
            return redirect()->route('erroPagamento')->with('error', 'Aluno ou professor não encontrados.');
        }

        $empresa = $professor->usuario->empresa;
        $gateway = PagamentoGateway::where('empresa_id', $empresa->id)
            ->where('name', 'asaas')
            ->where('status', 1)
            ->first();
      
        if (!$gateway) {
            return redirect()->route('erroPagamento')->with('error', 'Nenhum gateway Asaas ativo configurado.');
        }   
        
        // Verificar se o professor tem walletId
        if (!$professor->asaas_wallet_id) {
            return redirect()->route('erroPagamento')->with('error', 'O professor precisa integrar com o Asaas antes de criar a cobrança.');
        }
    
        $data_aula = self::convertToUSFormat($validated['data_aula']) . ' ' . $validated['hora_aula'];
      
        // Criar ou buscar cliente do aluno
        $alunoData = [
            'name' => $aluno->usuario->nome,
            'email' => $aluno->usuario->email,
            'cpfCnpj' => $aluno->usuario->cpf ?? '12345678909',
        ];
        $clientes = $this->asaasService->getClients($gateway->api_key, $gateway->mode);
        $alunoExistente = collect($clientes['data'] ?? [])->firstWhere('email', $aluno->usuario->email);
        $alunoId = $alunoExistente ? $alunoExistente['id'] : $this->asaasService->createCustomer($alunoData, $gateway->api_key, $gateway->mode)['id'];

        // Calcular tarifa
        $tariff = $gateway->tariff_type == 'percentage'
            ? $validated['valor_aula'] * ($gateway->tariff_value / 100)
            : $gateway->tariff_value;
        $valor_cobranca = $validated['valor_aula'] + $tariff;
     
        // Criar cobrança com split
        $cobrancaData = [
            'customer' => $alunoId,
            'billingType' => in_array('pix', $gateway->methods ?? []) ? 'PIX' : 'CREDIT_CARD',
            'value' => $valor_cobranca,
            'dueDate' => now()->addDays(1)->format('Y-m-d'),
            'description' => $validated['titulo'],
            'split' => [
                [
                    'walletId' => $professor->asaas_wallet_id, // Wallet ID do professor
                    'fixedValue' => $validated['valor_aula'], // Valor que o professor recebe
                ],
                [
                    'walletId' => $gateway->split_account, // Wallet ID do dono do SaaS
                    'fixedValue' => $tariff, // Tarifa do SaaS
                ],
            ],
        ];

        $cobranca = $this->asaasService->cobranca($cobrancaData, $gateway->api_key, $gateway->mode);

        if ($cobranca['status'] == 'PENDING') {
            $aluno->professores()->attach($professor);
            Agendamento::create([
                'aluno_id' => $validated['aluno_id'],
                'modalidade_id' => $validated['modalidade_id'],
                'professor_id' => $validated['professor_id'],
                'data_da_aula' => $data_aula,
                'valor_aula' => $validated['valor_aula'],
                'horario' => $validated['hora_aula'],
                'gateway_id' => $gateway->id,
                'cobranca_id' => $cobranca['id'],
            ]);

            dd("aaa");
            return redirect()->route('home.checkoutsucessoasaas', ['id' => $professor->id]);
              
        }

        return redirect()->route('erroPagamento')->with('error', 'Erro ao criar cobrança: ' . ($cobranca['errorMessage'] ?? 'Desconhecido'));
    }
    // Funções de conversão mantidas como no original
    public static function convertToBRL($amount, $from_currency)
    {
        $exchange_rates = [
            'USD' => 5.0, // Atualize com uma API de câmbio real
        ];

        if (isset($exchange_rates[$from_currency])) {
            return $amount * $exchange_rates[$from_currency];
        }

        throw new \Exception("Taxa de câmbio para '$from_currency' não encontrada.");
    }

    public static function convertToUSFormat($originalDate)
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $originalDate)) {
            return $originalDate;
        }

        $originalDate = str_replace(
            [' jan. ', ' fev. ', ' mar. ', ' abr. ', ' mai. ', ' jun. ', ' jul. ', ' ago. ', ' set. ', ' out. ', ' nov. ', ' dez. '],
            [' jan ', ' fev ', ' mar ', ' abr ', ' mai ', ' jun ', ' jul ', ' ago ', ' set ', ' out ', ' nov ', ' dez '],
            $originalDate
        );

        $parts = explode(' ', trim($originalDate));

        if (count($parts) !== 3) {
            throw new \Exception("Formato de data inválido: '{$originalDate}'. Esperado: '3 fev 2025'");
        }

        $months = [
            'jan' => '01',
            'fev' => '02',
            'mar' => '03',
            'abr' => '04',
            'mai' => '05',
            'jun' => '06',
            'jul' => '07',
            'ago' => '08',
            'set' => '09',
            'out' => '10',
            'nov' => '11',
            'dez' => '12',
        ];

        $day = $parts[0];
        $monthAbbrev = strtolower($parts[1]);
        $year = $parts[2];

        if (!isset($months[$monthAbbrev])) {
            throw new \Exception("Mês inválido: '{$monthAbbrev}' na data '{$originalDate}'.");
        }

        $monthNumber = $months[$monthAbbrev];
        return "{$year}-{$monthNumber}-{$day}";
    }
}