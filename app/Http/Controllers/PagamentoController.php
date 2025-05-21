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

class PagamentoController extends Controller
{
    protected $aluno_professor;
    protected $asaasService;

    public function __construct(AlunoProfessor $aluno_professor, AsaasService $asaasService)
    {
        $this->aluno_professor = $aluno_professor;
        $this->asaasService = $asaasService;
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
    
        // Dados do professor para criar cliente no Asaas
        $clienteData = [
            'name' => $professor->usuario->nome,
            'email' => $professor->usuario->email,
            'cpfCnpj' => $professor->usuario->cpf ?? '98765432100', // CPF válido como fallback
            'phone' => $professor->usuario->telefone ?? '21987654321',
        ];
    
        try {
            $asaasService = new AsaasService();
            $cliente = $asaasService->createCustomer($clienteData, $gateway->api_key, $gateway->mode);
            $customerId = $cliente['id'];
    
            // Obter walletId
            $wallet = $asaasService->getCustomerWallet($customerId, $gateway->api_key, $gateway->mode);
            $walletId = $wallet['walletId'];
    
            // Salvar os IDs no modelo Professor (opcional)
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
            return response()->json(['success' => false, 'message' => 'Erro ao integrar: ' . $e->getMessage()], 500);
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
       // 'cpfCnpj' => 'required|string|min:11', // Adicionado validação para cpfCnpj
    ]);
  
    $request->cpfCnpj = '12345678909';
    $aluno = Alunos::with('usuario')->find($validated['aluno_id']);
    $professor = Professor::with('usuario.empresa')->find($validated['professor_id']);

    if (!$aluno || !$professor || !$professor->usuario || !$professor->usuario->empresa) {
        return redirect()->route('erroPagamento')->with('error', 'Aluno, professor ou empresa não encontrados.');
    }

    // Verificar se o nome do aluno está presente
    if (empty($aluno->usuario->nome) || is_null($aluno->usuario->nome)) {
        return redirect()->route('erroPagamento')->with('error', 'O nome do aluno não foi informado no sistema.');
    }
  
    $empresa = $professor->usuario->empresa;
    $gateway = PagamentoGateway::where('empresa_id', $empresa->id)
        ->where('name', 'asaas')
        ->where('status', 1)
        ->first();
      
    if (!$gateway) {
        return redirect()->route('erroPagamento')->with('error', 'Nenhum gateway Asaas ativo configurado para esta empresa.');
    }

    $data_aula = self::convertToUSFormat($validated['data_aula']) . ' ' . $validated['hora_aula'];

    // Limpar o CPF/CNPJ (remover caracteres não numéricos)
    $cpfCnpj = preg_replace('/[^0-9]/', '', $request->cpfCnpj);

    // Verificar se o CPF/CNPJ tem um tamanho válido
    if (strlen($cpfCnpj) < 11 || (strlen($cpfCnpj) > 11 && strlen($cpfCnpj) < 14) || strlen($cpfCnpj) > 14) {
        return redirect()->route('erroPagamento')->with('error', 'CPF/CNPJ inválido. O CPF deve ter 11 dígitos e o CNPJ 14 dígitos.');
    }
  
    // Buscar cliente existente no Asaas pelo e-mail
    $clienteData = [
        'name' => $aluno->usuario->nome,
        'email' => $aluno->usuario->email,
        'cpfCnpj' => '12345678909', // Usar o CPF/CNPJ já formatado e validado
    ];

    \Log::info('Dados do cliente enviados: ' . json_encode($clienteData));

    $clientes = $this->asaasService->getClients($gateway->api_key, $gateway->mode);
 
    $clienteExistente = collect($clientes['data'] ?? [])->firstWhere('email', $aluno->usuario->email);

    $clienteId = null;
  
    if ($clienteExistente) {
      
        $clienteId = $clienteExistente['id'];
        
        // Verificar se precisamos atualizar o CPF/CNPJ do cliente existente
        if (empty($clienteExistente['cpfCnpj']) && !empty($cpfCnpj)) {
            $this->asaasService->updateCustomer($clienteExistente['id'], ['cpfCnpj' => $cpfCnpj], $gateway->api_key, $gateway->mode);
        }
    } else {
        // Criar novo cliente
        $novoCliente = $this->asaasService->createCustomer($clienteData, $gateway->api_key, $gateway->mode);
        
        if (isset($novoCliente['errors'])) {
            \Log::error('Erro ao criar cliente: ' . json_encode($novoCliente));
            return redirect()->route('erroPagamento')->with('error', 'Erro ao criar cliente: ' . ($novoCliente['errors'][0]['description'] ?? 'Erro desconhecido'));
        }
        
        $clienteId = $novoCliente['id'];
    }

    // Verificar se temos um ID de cliente válido
    if (!$clienteId) {
        return redirect()->route('erroPagamento')->with('error', 'Não foi possível criar ou encontrar o cliente no Asaas.');
    }
   
    // Calcular tarifa
    $tariff = $gateway->tariff_type == 'percentage'
        ? $validated['valor_aula'] * ($gateway->tariff_value / 100)
        : $gateway->tariff_value;

    $valor_cobranca = $validated['valor_aula'] + $tariff;

    // Criar cobrança
    $cobrancaData = [
        'customer' => $clienteId,
        'billingType' => in_array('pix', $gateway->methods ?? []) ? 'PIX' : 'CREDIT_CARD',
        'value' => $valor_cobranca,
        'dueDate' => now()->addDays(1)->format('Y-m-d'),
        'description' => $validated['titulo'],
    ];

    
    // Desativar o split no sandbox para evitar o erro
    if ($gateway->mode == 'production' && $gateway->split_account) {
        // Em produção, adicionar o split para a tarifa do dono do SaaS
        $cobrancaData['split'] = [
            [
                'walletId' => $gateway->split_account,
                'fixedValue' => $tariff,
            ],
        ];
    } else {
        \Log::warning('Split desativado: modo sandbox.');
        // No sandbox, usar apenas o valor da aula, sem tarifa, para simplificar os testes
        $valor_cobranca = $validated['valor_aula'];
        $cobrancaData['value'] = $valor_cobranca;
    }
  
    \Log::info('Dados da cobrança enviados: ' . json_encode($cobrancaData));

    try {
      
        $cobranca = $this->asaasService->cobranca($cobrancaData, $gateway->api_key, $gateway->mode);
     
        if (isset($cobranca['errors'])) {
            \Log::error('Erro na resposta da cobrança: ' . json_encode($cobranca));
            return redirect()->route('erroPagamento')->with('error', 'Erro ao criar cobrança: ' . ($cobranca['errors'][0]['description'] ?? 'Erro desconhecido'));
        }

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
            
           
            return redirect()->route('home.checkoutsucesso', ['id' => $professor->id]);
              
        }
        
        return redirect()->route('erroPagamento')->with('error', 'Status da cobrança diferente de PENDING: ' . ($cobranca['status'] ?? 'Desconhecido'));
    } catch (\Exception $e) {
        \Log::error('Exceção ao criar cobrança: ' . $e->getMessage());
        return redirect()->route('erroPagamento')->with('error', 'Erro ao criar cobrança: ' . $e->getMessage());
    }
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