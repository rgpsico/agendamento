<?php

namespace App\Http\Controllers;

use App\Mail\PaymentConfirmation;
use App\Models\Agendamento;
use App\Models\AlunoProfessor;
use App\Models\Alunos;
use App\Models\Empresa;
use App\Models\Modalidade;
use App\Models\PagamentoGateway;
use App\Models\Pagamento;
use App\Models\Professor;
use App\Models\Servicos;
use App\Services\AsaasService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Charge;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Import the Log facade
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\PagamentoComCartaoRequest;
use App\Services\AgendamentoService;
use App\Http\Requests\CriarPagamentoPresencialRequest;
use App\Services\TwilioService;
use App\Services\NotificationService;
class PagamentoController extends Controller
{
    protected $aluno_professor, $baseUri;
    protected $asaasService, $agendamentoService, $notificacaoService;

    public function __construct(
        AlunoProfessor $aluno_professor,
        AsaasService $asaasService,
        AgendamentoService $agendamentoService,
        NotificationService $notificacaoService

    ) {
        $this->aluno_professor = $aluno_professor;
        $this->asaasService = $asaasService;
        $this->agendamentoService = $agendamentoService;
        $this->baseUri = env('ASAAS_ENV') === 'production' ? env('ASAAS_URL') : env('ASAAS_SANDBOX_URL');
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

    public function generatePixQrCode(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'customer_id' => 'required|string', // Asaas customer ID
            'value' => 'required|numeric|min:0.01', // Payment value
            'description' => 'nullable|string|max:200', // Optional description
            'due_date' => 'nullable|date_format:Y-m-d', // Optional due date
        ]);

        $apiKey = env('ASAAS_KEY'); // Retrieve Asaas API key from .env

        $client = new Client();

        try {
            // Step 1: Create a payment
            $paymentResponse = $client->post($this->baseUri . "/payments", [
                'headers' => [
                    'Authorization' => "Bearer $apiKey",
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'customer' => $request->customer_id,
                    'billingType' => 'PIX',
                    'value' => $request->value,
                    'dueDate' => $request->due_date ?? now()->addDay()->format('Y-m-d'),
                    'description' => $request->description ?? 'Payment via Pix',
                ],
            ]);

            $paymentData = json_decode($paymentResponse->getBody(), true);
            $paymentId = $paymentData['id'];

            // Step 2: Retrieve the Pix QR code
            $qrCodeResponse = $client->get("$baseUrl/payments/$paymentId/pixQrCode", [
                'headers' => [
                    'Authorization' => "Bearer $apiKey",
                    'Accept' => 'application/json',
                ],
            ]);

            $qrCodeData = json_decode($qrCodeResponse->getBody(), true);

            // Return the QR code data
            return response()->json([
                'success' => true,
                'qr_code' => [
                    'encoded_image' => $qrCodeData['encodedImage'], // Base64 image
                    'payload' => $qrCodeData['payload'], // Copy-and-paste code
                    'expiration_date' => $qrCodeData['expirationDate'],
                ],
            ], 200);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Handle client errors (e.g., 400, 401, 404)
            Log::error('Asaas API Client Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate Pix QR code: ' . $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            // Handle other errors
            Log::error('Asaas API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function gerarPix(Request $request)
    {

        try {
            //  $aluno = Auth::user()->aluno;
            $apiKey = env('ASAAS_KEY'); // ou como você pega a API key

            // // // 1. Garantir que tem customer
            // if (!$aluno->asaas_customer_id) {
            //     $customerData = [
            //         'name' => $aluno->nome,
            //         'email' => $aluno->email,
            //         'cpfCnpj' => $aluno->cpf,
            //     ];
            //     $customer = $this->asaasService->createCustomer($customerData, $apiKey, 'sandbox');
            //     $aluno->asaas_customer_id = $customer['id'];
            //     $aluno->save();
            // }

            // 2. Criar pagamento PIX
            $pixPayment = $this->asaasService->createPixPayment([
                'customer' => 'cus_000006338089',
                'value' => $request->valor_aula,
                'dueDate' => date('Y-m-d'),
                'description' => $request->titulo
            ], $apiKey);

            return response()->json([
                'success' => true,
                'payment_id' => $pixPayment['id'],
                'qr_code_image' => $pixPayment['pixTransaction']['encodedImage'],
                'pix_code' => $pixPayment['pixTransaction']['payload'],
                'expiration_date' => $pixPayment['pixTransaction']['expirationDate']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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
            return redirect()->back()->withErrors('Você precisa estar logado como professor.');
        }

        $professor = Auth::user()->professor;
        $usuario = Auth::user();

        // Verificar se já possui integração
        $jaIntegrado = !empty($professor->asaas_wallet_id);

        return view('admin.integracoes.escolaassas', compact('professor', 'usuario', 'jaIntegrado'));
    }


    public function mostrarIntegracaopix()
    {

        // Verifica se o usuário está logado e é um professor
        if (!Auth::check() || !Auth::user()->professor) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado como professor.');
        }

        $professor = Auth::user()->professor;
        $usuario = Auth::user();

        // Verificar se já possui integração
        $jaIntegrado = !empty($professor->asaas_wallet_id);

        return view('admin.integracoes.pix', compact('professor', 'usuario', 'jaIntegrado'));
    }

    public function integrarAsaas(Request $request)
    {
        $professorId = $request->input('professor_id');
        $professor = Professor::with('usuario')->find($professorId);



        if (!$professor || !$professor->usuario) {
            return response()->json(['success' => false, 'message' => 'Professor não encontrado.'], 400);
        }

        $gateway = PagamentoGateway::where('name', 'asaas')->where('status', 1)->first();
        if (!$gateway) {
            return response()->json(['success' => false, 'message' => 'Gateway Asaas não configurado.'], 400);
        }



        $clienteData = [
            'name' => $professor->usuario->nome,
            'email' => $professor->usuario->email ?? 'teste@12212.com',
            'cpfCnpj' => $professor->usuario->cpf ?? '71180274059',
            'phone' => $professor->usuario->telefone ?? '21987654321',
            // Adicionar campos adicionais que possam ajudar na ativação da conta
            'mobilePhone' => $professor->usuario->celular ?? $professor->usuario->telefone ?? '21987654321',
            'notificationDisabled' => false,
        ];

        try {
            $asaasService = new AsaasService();
            $cliente = $asaasService->createCustomer($clienteData, $gateway->api_key, $gateway->mode);
            $customerId = $cliente['id'];

            \Log::info('Cliente Asaas criado com sucesso: ' . json_encode($cliente));

            // Tentar obter o walletId com algumas tentativas, com intervalos entre elas
            $wallet = $this->getCustomerWallet($customerId);



            // Se ainda não tiver walletId, tentar criar um manualmente (se houver endpoint para isso)
            if (!$walletId && method_exists($asaasService, 'createCustomerWallet')) {
                \Log::info("Tentando criar wallet manualmente para o cliente: $customerId");
                try {
                    $wallet = $asaasService->createCustomerWallet($customerId, $gateway->api_key, $gateway->mode);
                    $walletId = $wallet['walletId'] ?? null;
                } catch (\Exception $e) {
                    \Log::warning("Erro ao tentar criar wallet manualmente: " . $e->getMessage());
                }
            }

            // Verificar se o walletId foi gerado
            if (!$walletId) {
                \Log::warning('Wallet ID não encontrado após múltiplas tentativas para o cliente: ' . $customerId);

                // Salvar o customerId mesmo sem walletId
                $professor->update([
                    'asaas_customer_id' => $customerId,
                    'asaas_wallet_id' => null,
                ]);

                if ($gateway->mode === 'sandbox') {
                    return response()->json([
                        'success' => true,
                        'warning' => true,
                        'message' => 'Cliente criado com sucesso, mas o Wallet ID não foi gerado no ambiente sandbox. Você poderá tentar novamente mais tarde ou contatar o suporte do Asaas.',
                        'customerId' => $customerId,
                    ], 200);
                } else {
                    return response()->json([
                        'success' => true,
                        'warning' => true,
                        'message' => 'Cliente criado com sucesso, mas o Wallet ID não foi gerado. Isso pode ocorrer quando a conta Asaas ainda não está completamente ativada. Tente novamente mais tarde.',
                        'customerId' => $customerId,
                    ], 200);
                }
            }

            // Atualizar o professor com os dados do Asaas
            $professor->update([
                'asaas_customer_id' => $customerId,
                'asaas_wallet_id' => $walletId,
            ]);

            return response()->json([
                'success' => true,
                'customerId' => $customerId,
                'walletId' => $walletId,
                'message' => 'Integração com Asaas concluída com sucesso!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao integrar com Asaas: ' . $e->getMessage());
            $errorMessage = $e->getMessage();

            // Verificar se o erro é relacionado ao CPF/CNPJ já existente
            if (
                strpos($errorMessage, 'CPF/CNPJ já utilizado') !== false ||
                strpos($errorMessage, 'already in use') !== false
            ) {

                try {
                    // Tentar buscar o cliente existente pelo CPF/CNPJ
                    $cpfCnpj = $professor->usuario->cpf ?? '71180274059';
                    $clienteExistente = $this->findCustomerByCpfCnpj($cpfCnpj, $gateway->api_key, $gateway->mode);

                    if ($clienteExistente && isset($clienteExistente['id'])) {
                        $customerId = $clienteExistente['id'];

                        // Tentar obter o walletId do cliente existente
                        $wallet = $this->getCustomerWallet($customerId);

                        $walletId = $wallet['walletId'] ?? null;


                        // Atualizar o professor com os dados do cliente existente
                        $professor->update([
                            'asaas_customer_id' => $customerId,
                            'asaas_wallet_id' => $walletId,
                        ]);

                        return response()->json([
                            'success' => true,
                            'customerId' => $customerId,
                            'walletId' => $walletId,
                            'message' => 'Cliente já existente no Asaas, integração realizada com sucesso!'
                        ]);
                    }
                } catch (\Exception $ex) {
                    \Log::error('Erro ao buscar cliente existente: ' . $ex->getMessage());
                }
            }

            return response()->json(['success' => false, 'message' => 'Erro ao integrar: ' . $errorMessage], 500);
        }
    }

    public function getCustomer($customerId)
    {
        // Obtém a URL e a chave de API do arquivo de configuração
        $apiUrl =  $this->baseUri;
        $apiKey = env('ASAAS_KEY');


        // Faz a requisição à API do Asaas
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json',
        ])->timeout(60)->get("{$apiUrl}/customers/{$customerId}");

        // Verifica se a requisição foi bem-sucedida
        if ($response->successful()) {
            $customerData = $response->json();
            // O Wallet ID deve ser obtido no painel Asaas (seção Integrações)
            $walletId = 'seu_wallet_id'; // Substitua pelo Wallet ID da sua conta Asaas

            return response()->json([
                'customer' => $customerData,
                'wallet_id' => $walletId,
            ]);
        }

        // Trata erros
        return response()->json([
            'error' => 'Erro ao consultar cliente',
            'status' => $response->status(),
            'message' => $response->json()['errors'] ?? 'Erro desconhecido',
        ], $response->status());
    }


    // app/Http/Controllers/PaymentController.php


    public function pagarComCartao(PagamentoComCartaoRequest $request)
    {
        // 1. Obter dados do professor com walletId
        $professor = Professor::findOrFail($request->professor_id);

        if (!$professor->asaas_wallet_id) {
            return response()->json(['erro' => 'Professor sem carteira digital configurada.'], 400);
        }

        // 2. Criar agendamento
        $agendamento = $this->agendamentoService->criarAgendamento($request->all());

        // 3. Criar cliente no Asaas
        $clienteResponse = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env("ASAAS_KEY"),
        ])->post($this->baseUri . '/api/v3/customers', [
            'name' => $request->name,
            'email' => $request->email,
            'cpfCnpj' => $request->cpfCnpj,
            'phone' => $request->phone,
            'postalCode' => $request->postalCode,
            'address' => $request->address,
            'addressNumber' => $request->addressNumber,
            'province' => $request->province,
        ]);

        if (!$clienteResponse->successful()) {
            return response()->json(['erro_criando_cliente' => $clienteResponse->json()], 400);
        }

        $clienteId = $clienteResponse->json()['id'];

        // 4. Calcular o valor líquido e o split
        $valorTotal = $request->value; // Ex.: 5.00
        $taxaAsaas = 0.05; // Taxa de 5% (ajuste conforme seu plano)
        $valorLiquido = round($valorTotal * (1 - $taxaAsaas), 2); // Ex.: 5.00 * 0.95 = 4.75
        $valorProfessor = round($valorLiquido * 0.7, 2); // 70% do valor líquido
        $valorSaaS = round($valorLiquido * 0.3, 2);      // 30% do valor líquido

        // Ajustar para evitar erros de arredondamento
        if ($valorProfessor + $valorSaaS > $valorLiquido) {
            $valorSaaS = $valorLiquido - $valorProfessor; // Garantir que a soma seja igual ao líquido
        }

        // 5. Criar pagamento com split
        $pagamentoResponse = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_KEY'),
        ])->post($this->baseUri . '/api/v3/payments', [
            'customer' => $clienteId,
            'billingType' => 'CREDIT_CARD',
            'value' => $valorTotal,
            'dueDate' => now()->format('Y-m-d'),
            'description' => 'Pagamento com cartão via Laravel',
            'creditCard' => [
                'holderName' => $request->card_holder,
                'number' => $request->card_number,
                'expiryMonth' => $request->card_expiry_month,
                'expiryYear' => $request->card_expiry_year,
                'ccv' => $request->card_ccv,
            ],
            'creditCardHolderInfo' => [
                'name' => $request->name,
                'email' => $request->email,
                'cpfCnpj' => $request->cpfCnpj,
                'postalCode' => $request->postalCode,
                'addressNumber' => $request->addressNumber,
                'addressComplement' => '',
                'phone' => $request->phone,
                'mobilePhone' => $request->phone,
            ],
            // 💸 Split ajustado com base no valor líquido
            'split' => [
                [
                    'walletId' => $professor->asaas_wallet_id,
                    'fixedValue' => $valorProfessor,
                    'status' => 'ACTIVE',
                    'refusalOption' => 'TRANSFER',
                ],
                [
                    'walletId' => env('ASAAS_SAAS_WALLET_ID'),
                    'fixedValue' => $valorSaaS,
                    'status' => 'ACTIVE',
                    'refusalOption' => 'TRANSFER',
                ]
            ]
        ]);

        if (!$pagamentoResponse->successful()) {
            return response()->json(['erro_pagamento' => $pagamentoResponse->json()], 400);
        }

        // 6. Resposta de sucesso
        $redirectUrl = route('home.checkoutsucesso', ['id' => $request->professor_id]);

        return response()->json([
            'success' => true,
            'redirect_url' => $redirectUrl,
            'agendamento_id' => $agendamento->id,
            'payment_method' => 'cartao'
        ]);
    }



    public function criarPagamentoPresencial(CriarPagamentoPresencialRequest $request)
    {
        $tipo_de_horario = Servicos::where('id', $request->servico_id)->value('tipo_agendamento');

        // Verificar disponibilidade do professor
        if (Agendamento::verificarDisponibilidade($request, $tipo_de_horario)) {
            return redirect()->back()
                ->with('error', 'O professor já possui um agendamento neste horário. Procure uma nova data ou horário diferente')
                ->withInput();
        }

        // Criar o agendamento
        $agendamento = Agendamento::criarAgendamento($request->all());

        // Criar o registro de pagamento
        $pagamento = Pagamento::criarPagamentoPresencial($agendamento, $request->all());

        // ➕ Vincular aluno ao professor se ainda não for aluno
        $professorId = $request->input('professor_id');
        $alunoId = $request->input('aluno_id');

        $professor = Professor::find($professorId);

        if ($professor && $alunoId) {
            if (!$professor->alunos()->where('aluno_id', $alunoId)->exists()) {
                $professor->alunos()->attach($alunoId);
            }
        }

        // ➕ Enviar confirmação pelo canal configurado (WhatsApp, E-mail ou ambos)
        $notificationService = app(\App\Services\NotificationService::class);

        // Aqui você pode decidir o canal dinamicamente, por exemplo:
        // $canais = ['whatsapp']; // só WhatsApp
        // $canais = ['email'];    // só Email
        // $canais = ['whatsapp', 'email']; // ambos
        $canais = ['whatsapp', 'email'];

        $notificationService->enviarAgendamento($agendamento, $pagamento, $canais);

        // Redirecionar para a página de confirmação
        return redirect()->route('home.checkoutsucesso', ['id' => $professorId])
            ->with('success', 'Agendamento e pagamento presencial registrados com sucesso');
    }




    public function verRecibo($id)
    {
        // Obter o usuário autenticado (aluno)
        $aluno = Auth::user()->aluno;

        if (!$aluno) {
            return redirect()->route('home')->with('error', 'Usuário não autorizado para visualizar o recibo.');
        }

        // Obter o professor
        $professor = Professor::with('usuario')->findOrFail($id);

        // Buscar o agendamento mais recente do aluno com este professor
        $agendamento = Agendamento::where('aluno_id', $aluno->id)
            ->where('professor_id', $professor->id)
            ->latest()
            ->first();

        if (!$agendamento) {
            return redirect()->route('home')->with('error', 'Nenhum agendamento encontrado para este professor.');
        }

        // Buscar o pagamento associado ao agendamento
        $pagamento = Pagamento::where('agendamento_id', $agendamento->id)
            ->where('aluno_id', $aluno->id)
            ->first();

        if (!$pagamento) {
            return redirect()->route('home')->with('error', 'Nenhum pagamento encontrado para este agendamento.');
        }

        // Passar os dados para a view
        return view('admin.pagamento.recibo', [
            'agendamento' => $agendamento,
            'pagamento' => $pagamento,
            'professor' => $professor,
            'aluno' => $aluno,
        ]);
    }


    //  private static function convertToUSFormat($date)
    // {
    //     $date = str_replace('/', '-', $date);
    //     return \Carbon\Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
    // }


    public function pagamentoAsaas(Request $request)
    {
        try {
            $validated = $request->validate([
                'aluno_id' => 'required|exists:alunos,id',
                'professor_id' => 'required|exists:professores,id',
                'valor_aula' => 'required|numeric|min:0',
                'modalidade_id' => 'required|exists:modalidades,id',
                'data_aula' => 'required|string',
                'hora_aula' => 'required|string',
                'titulo' => 'required|string',
                'payment_method' => 'required|in:pix,cartao',
            ]);

            $aluno = Alunos::with('usuario')->find($validated['aluno_id']);
            $professor = Professor::with('usuario')->find($validated['professor_id']);

            if (!$aluno || !$professor || !$professor->usuario || !$aluno->usuario) {
                Log::error('Aluno or professor not found', [
                    'aluno_id' => $validated['aluno_id'],
                    'professor_id' => $validated['professor_id'],
                ]);
                return redirect()->back()->with('error', 'Aluno ou professor não encontrados.');
            }

            $empresa = $professor->usuario->empresa;

            if (!$empresa) {
                Log::error('Empresa not found for professor', [
                    'professor_id' => $validated['professor_id'],
                ]);
                return redirect()->back()->with('error', 'Empresa não encontrada para o professor.');
            }

            $gateway = PagamentoGateway::where('empresa_id', $empresa->id)
                ->where('name', 'asaas')
                ->where('status', 1)
                ->first();

            if (!$gateway) {
                Log::error('No active Asaas gateway found', [
                    'empresa_id' => $empresa->id,
                ]);
                return redirect()->back()->with('error', 'Nenhum gateway Asaas ativo configurado.');
            }

            if (!$professor->asaas_wallet_id) {
                Log::error('Professor has no Asaas wallet ID', [
                    'professor_id' => $validated['professor_id'],
                ]);
                return redirect()->back()->with('error', 'O professor precisa integrar com o Asaas antes de criar a cobrança.');
            }

            if ($professor->asaas_wallet_id === $gateway->split_account) {
                Log::error('Error: Professor wallet ID and SaaS wallet ID are the same', [
                    'professor_wallet_id' => $professor->asaas_wallet_id,
                    'saas_wallet_id' => $gateway->split_account,
                ]);
                return redirect()->back()->with('error', 'Erro: A carteira do professor não pode ser a mesma do proprietário do SaaS.');
            }

            $data_aula = self::convertToUSFormat($validated['data_aula']) . ' ' . $validated['hora_aula'];

            $alunoData = [
                'name' => $aluno->usuario->nome,
                'email' => $aluno->usuario->email,
                'cpfCnpj' => $aluno->usuario->cpf ?? '12345678909',
            ];

            $clientes = $this->asaasService->getClients($gateway->api_key, $gateway->mode);
            $alunoExistente = collect($clientes['data'] ?? [])->firstWhere('email', $aluno->usuario->email);
            $alunoId = $alunoExistente ? $alunoExistente['id'] : $this->asaasService->createCustomer($alunoData, $gateway->api_key, $gateway->mode)['id'];

            $tariff = $gateway->tariff_type == 'percentage'
                ? $validated['valor_aula'] * ($gateway->tariff_value / 100)
                : $gateway->tariff_value;
            $valor_cobranca = $validated['valor_aula'] + $tariff;

            if (abs($valor_cobranca - ($validated['valor_aula'] + $tariff)) > 0.01) {
                Log::error('Split amount mismatch', [
                    'valor_cobranca' => $valor_cobranca,
                    'valor_aula' => $validated['valor_aula'],
                    'tariff' => $tariff,
                ]);
                return redirect()->back()->with('error', 'Erro: A soma dos valores do split não corresponde ao valor total da cobrança.');
            }

            $cobrancaData = [
                'customer' => $alunoId,
                'billingType' => $validated['payment_method'] === 'pix' ? 'PIX' : 'CREDIT_CARD',
                'value' => $valor_cobranca,
                'dueDate' => now()->addDays(1)->format('Y-m-d'),
                'description' => $validated['titulo'],
                'split' => [
                    [
                        'walletId' => $professor->asaas_wallet_id,
                        'fixedValue' => $validated['valor_aula'],
                    ],
                ],
            ];

            if ($validated['payment_method'] === 'cartao') {
                $cobrancaData['creditCard'] = [
                    'holderName' => $request->input('card_name'),
                    'number' => str_replace(' ', '', $request->input('card_number')),
                    'expiryMonth' => explode('/', $request->input('card_expiry'))[0],
                    'expiryYear' => '20' . explode('/', $request->input('card_expiry'))[1],
                    'ccv' => $request->input('card_cvv'),
                ];
                $cobrancaData['creditCardHolderInfo'] = [
                    'name' => $request->input('card_name'),
                    'email' => $aluno->usuario->email,
                    'cpfCnpj' => str_replace(['.', '-'], '', $request->input('card_cpf')),
                ];
            }

            Log::info('Attempting to create Asaas payment', [
                'cobrancaData' => $cobrancaData,
                'api_key' => substr($gateway->api_key, 0, 5) . '...',
                'mode' => $gateway->mode,
            ]);

            $cobranca = $this->asaasService->cobranca($cobrancaData, $gateway->api_key, $gateway->mode);

            if ($cobranca['status'] == 'PENDING') {
                $aluno->professores()->attach($professor);

                $agendamento = Agendamento::create([
                    'aluno_id' => $validated['aluno_id'],
                    'modalidade_id' => $validated['modalidade_id'],
                    'professor_id' => $validated['professor_id'],
                    'data_da_aula' => $data_aula,
                    'valor_aula' => $validated['valor_aula'],
                    'horario' => $validated['hora_aula'],
                    'gateway_id' => $gateway->id,
                    'cobranca_id' => $cobranca['id'],
                ]);

                $pagamento = Pagamento::create([
                    'agendamento_id' => $agendamento->id,
                    'aluno_id' => $validated['aluno_id'],
                    'pagamento_gateway_id' => $gateway->id,
                    'asaas_payment_id' => $cobranca['id'],
                    'status' => $cobranca['status'],
                    'valor' => $valor_cobranca,
                    'metodo_pagamento' => $validated['payment_method'] === 'pix' ? 'PIX' : 'CREDIT_CARD',
                    'data_vencimento' => $cobranca['dueDate'],
                    'url_boleto' => null,
                    'qr_code_pix' => $validated['payment_method'] === 'pix' ? $cobranca['encodedImage'] : null,
                    'resposta_api' => json_encode($cobranca),
                ]);

                Log::info('Payment created successfully', [
                    'cobranca_id' => $cobranca['id'],
                    'aluno_id' => $validated['aluno_id'],
                    'professor_id' => $validated['professor_id'],
                ]);

                if ($validated['payment_method'] === 'pix') {
                    return redirect()->route('pix-payment', [
                        'cobranca_id' => $cobranca['id'],
                        'professor_id' => $professor->id,
                    ]);
                }

                return redirect()->route('home.checkoutsucesso', ['id' => $professor->id])
                    ->with('success', 'Agendamento e pagamento confirmados com sucesso')
                    ->with('payment_method', $validated['payment_method']);
            }

            Log::error('Payment creation failed', [
                'cobranca_response' => $cobranca,
            ]);
            return redirect()->back()->with('error', 'Erro ao criar cobrança: ' . ($cobranca['errorMessage'] ?? 'Desconhecido'));
        } catch (\Exception $e) {
            Log::error('Payment creation failed with exception', [
                'error' => $e->getMessage(),
                'cobrancaData' => $cobrancaData ?? null,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Erro ao criar cobrança: ' . $e->getMessage());
        }
    }

    public function pixPayment(Request $request, $cobranca_id, $professor_id)
    {
        $professor = Professor::findOrFail($professor_id);
        $aluno = Auth::user()->aluno;

        if (!$aluno) {
            return redirect()->route('home')->with('error', 'Usuário não autorizado.');
        }

        $pagamento = Pagamento::where('asaas_payment_id', $cobranca_id)
            ->where('aluno_id', $aluno->id)
            ->firstOrFail();

        $agendamento = Agendamento::where('id', $pagamento->agendamento_id)->firstOrFail();

        return view('pix-payment', [
            'cobranca_id' => $cobranca_id,
            'professor' => $professor,
            'pagamento' => $pagamento,
            'agendamento' => $agendamento,
        ]);
    }

    public function getCustomerWallet(Request $request)
    {

        $client = new Client();

        $response = $client->request('GET', 'https://api-sandbox.asaas.com/v3/wallets/', [
            'headers' => [
                'accept' => 'application/json',
                'access_token' => '$aact_hmlg_000MzkwODA2MWY2OGM3MWRlMDU2NWM3MzJlNzZmNGZhZGY6OmRiMDVhZjk3LWQzMjMtNDBlZi1iMGYyLTkzMGRlMzFiMmRiODo6JGFhY2hfOTcwODhmOTctYWYxYy00MzY0LTgzODAtOTA3MzBhOWY5NmJk',
            ],
        ]);
        dd($response->getBody());

        return $response->getBody();
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

    public function checkPaymentStatus($cobranca_id)
    {
        $pagamento = Pagamento::where('asaas_payment_id', $cobranca_id)->firstOrFail();
        $gateway = PagamentoGateway::findOrFail($pagamento->pagamento_gateway_id);

        $response = $this->asaasService->checkPaymentStatus($cobranca_id, $gateway->api_key, $gateway->mode);

        if (isset($response['status'])) {
            $pagamento->status = $response['status'];
            $pagamento->resposta_api = json_encode($response);
            $pagamento->save();

            return response()->json(['status' => $response['status']]);
        }

        return response()->json(['error' => 'Não foi possível verificar o status do pagamento'], 500);
    }

    public function pagamentoAsaas1(Request $request)
    {
        try {
            // Validate request data
            $validated = $request->validate([
                'aluno_id' => 'required|exists:alunos,id',
                'professor_id' => 'required|exists:professores,id',
                'valor_aula' => 'required|numeric|min:0',
                'modalidade_id' => 'required|exists:modalidade,id',
                'data_aula' => 'required|string',
                'hora_aula' => 'required|string',
                'titulo' => 'required|string',
            ]);

            // Fetch aluno and professor with their associated usuario
            $aluno = Alunos::with('usuario')->find($validated['aluno_id']);
            $professor = Professor::with('usuario')->find($validated['professor_id']);

            // Check if aluno or professor exists
            if (!$aluno || !$professor || !$professor->usuario || !$aluno->usuario) {
                Log::error('Aluno or professor not found', [
                    'aluno_id' => $validated['aluno_id'],
                    'professor_id' => $validated['professor_id'],
                ]);
                return redirect()->route('erroPagamento')->with('error', 'Aluno ou professor não encontrados.');
            }

            // Fetch the empresa associated with the professor
            $empresa = $professor->usuario->empresa;

            if (!$empresa) {
                Log::error('Empresa not found for professor', [
                    'professor_id' => $validated['professor_id'],
                ]);
                return redirect()->route('erroPagamento')->with('error', 'Empresa não encontrada para o professor.');
            }

            // Fetch the Asaas g      bateway for the empresa
            $gateway = PagamentoGateway::where('empresa_id', $empresa->id)
                ->where('name', 'asaas')
                ->where('status', 1)
                ->first();

            if (!$gateway) {
                Log::error('No active Asaas gateway found', [
                    'empresa_id' => $empresa->id,
                ]);
                return redirect()->route('erroPagamento')->with('error', 'Nenhum gateway Asaas ativo configurado.');
            }

            // Verify professor's wallet ID
            if (!$professor->asaas_wallet_id) {
                Log::error('Professor has no Asaas wallet ID', [
                    'professor_id' => $validated['professor_id'],
                ]);
                return redirect()->route('erroPagamento')->with('error', 'O professor precisa integrar com o Asaas antes de criar a cobrança.');
            }

            // Verify that the professor's wallet ID and SaaS owner's wallet ID are different
            if ($professor->asaas_wallet_id === $gateway->split_account) {
                Log::error('Error: Professor wallet ID and SaaS wallet ID are the same', [
                    'professor_wallet_id' => $professor->asaas_wallet_id,
                    'saas_wallet_id' => $gateway->split_account,
                ]);
                return redirect()->route('erroPagamento')->with('error', 'Erro: A carteira do professor não pode ser a mesma do proprietário do SaaS.');
            }

            // Convert date to US format
            $data_aula = self::convertToUSFormat($validated['data_aula']) . ' ' . $validated['hora_aula'];

            // Create or fetch aluno customer in Asaas
            $alunoData = [
                'name' => $aluno->usuario->nome,
                'email' => $aluno->usuario->email,
                'cpfCnpj' => $aluno->usuario->cpf ?? '12345678909',
            ];


            $clientes = $this->asaasService->getClients($gateway->api_key, $gateway->mode);
            $alunoExistente = collect($clientes['data'] ?? [])->firstWhere('email', $aluno->usuario->email);
            $alunoId = $alunoExistente ? $alunoExistente['id'] : $this->asaasService->createCustomer($alunoData, $gateway->api_key, $gateway->mode)['id'];

            // Calculate tariff
            $tariff = $gateway->tariff_type == 'percentage'
                ? $validated['valor_aula'] * ($gateway->tariff_value / 100)
                : $gateway->tariff_value;
            $valor_cobranca = $validated['valor_aula'] + $tariff;

            // Validate that the split amounts sum to the total value
            if (abs($valor_cobranca - ($validated['valor_aula'] + $tariff)) > 0.01) {
                Log::error('Split amount mismatch', [
                    'valor_cobranca' => $valor_cobranca,
                    'valor_aula' => $validated['valor_aula'],
                    'tariff' => $tariff,
                ]);

                return redirect()->route('erroPagamento')->with('error', 'Erro: A soma dos valores do split não corresponde ao valor total da cobrança.');
            }

            // Create payment with split
            $cobrancaData = [
                'customer' => $alunoId,
                'billingType' => in_array('pix', $gateway->methods ?? []) ? 'PIX' : 'CREDIT_CARD',
                'value' => $valor_cobranca,
                'dueDate' => now()->addDays(1)->format('Y-m-d'),
                'description' => $validated['titulo'],
                'split' => [
                    [
                        'walletId' => $professor->asaas_wallet_id,
                        'fixedValue' => $validated['valor_aula'],
                    ],
                    // Remove the SaaS owner's wallet from the split
                    // The remaining amount ($tariff) will automatically go to the main account
                ],
            ];

            // Log the payment data for debugging
            Log::info('Attempting to create Asaas payment', [
                'cobrancaData' => $cobrancaData,
                'api_key' => substr($gateway->api_key, 0, 5) . '...', // Obfuscate API key
                'mode' => $gateway->mode,
            ]);

            // Create the payment
            $cobranca = $this->asaasService->cobranca($cobrancaData, $gateway->api_key, $gateway->mode);

            // Check if payment was created successfully
            if ($cobranca['status'] == 'PENDING') {
                // Attach professor to aluno
                $aluno->professores()->attach($professor);

                // Create agendamento
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

                Log::info('Payment created successfully', [
                    'cobranca_id' => $cobranca['id'],
                    'aluno_id' => $validated['aluno_id'],
                    'professor_id' => $validated['professor_id'],
                ]);

                return redirect()->route('home.checkoutsucesso', ['id' => $professor->id]);
            }

            Log::error('Payment creation failed', [
                'cobranca_response' => $cobranca,
            ]);
            return redirect()->route('erroPagamento')->with('error', 'Erro ao criar cobrança: ' . ($cobranca['errorMessage'] ?? 'Desconhecido'));
        } catch (\Exception $e) {

            Log::error('Payment creation failed with exception', [
                'error' => $e->getMessage(),
                'cobrancaData' => $cobrancaData ?? null,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('erroPagamento')->with('error', 'Erro ao criar cobrança: ' . $e->getMessage());
        }
    }




    /**
     * Obtém ou cria um cliente no Asaas com base no e-mail do aluno.
     *
     * @param int $alunoId ID do aluno no sistema
     * @param string $apiKey Chave da API do Asaas
     * @param string $mode Modo da API (sandbox ou production)
     * @return array Retorna um array com 'customer_id' ou 'error' em caso de falha
     */
    public function getOrCreateAsaasCustomer($alunoId, $apiKey, $mode)
    {
        // Buscar aluno com os dados do usuário
        $aluno = Alunos::with('usuario')->find($alunoId);

        if (!$aluno || !$aluno->usuario) {
            return [
                'error' => 'Aluno não encontrado ou sem dados de usuário.',
                'status' => 404
            ];
        }

        // Dados do cliente para o Asaas
        $alunoData = [
            'name' => $aluno->usuario->nome,
            'email' => $aluno->usuario->email,
            'cpfCnpj' => $aluno->usuario->cpf ?? '12345678909', // CPF padrão caso não exista
        ];

        // Buscar clientes no Asaas
        $clientes = $this->asaasService->getClients($apiKey, $mode);

        if (isset($clientes['error'])) {
            return [
                'error' => 'Erro ao consultar clientes no Asaas: ' . ($clientes['errorMessage'] ?? 'Desconhecido'),
                'status' => 500
            ];
        }

        // Verificar se o cliente já existe pelo e-mail
        $alunoExistente = collect($clientes['data'] ?? [])->firstWhere('email', $aluno->usuario->email);

        // Se o cliente existe, retornar o ID
        if ($alunoExistente) {
            return [
                'customer_id' => $alunoExistente['id'],
                'status' => 200
            ];
        }

        // Se não existe, criar um novo cliente
        $novoCliente = $this->asaasService->createCustomer($alunoData, $apiKey, $mode);

        if (isset($novoCliente['error'])) {
            return [
                'error' => 'Erro ao criar cliente no Asaas: ' . ($novoCliente['errorMessage'] ?? 'Desconhecido'),
                'status' => 500
            ];
        }

        return [
            'customer_id' => $novoCliente['id'],
            'status' => 201
        ];
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
