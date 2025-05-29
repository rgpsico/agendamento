<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Professor;
use App\Models\Alunos;
use App\Models\Agendamento;
use App\Models\Pagamento;
use App\Models\PagamentoGateway;
use App\Models\AlunoProfessor;
use App\Services\AsaasService;


use Illuminate\Support\Facades\Http;


class PixQrController extends Controller
{

    protected $aluno_professor, $asaasService;

        public function __construct(AlunoProfessor $aluno_professor, AsaasService $asaasService)
    {
        $this->aluno_professor = $aluno_professor;
        $this->asaasService = $asaasService;
        $this->client = new Client();
        $this->apiKey = env('ASAAS_API_KEY');
        $this->baseUrl = env('ASAAS_ENV') === 'sandbox' ? env('ASAAS_SANDBOX_URL') : env('ASAAS_URL');
    }



  
     public function listPixKeys(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required|string',
            ]);

            $response = $this->client->get("{$this->baseUrl}/v3/pix/addressKeys", [
                'headers' => [
                    'access_token' => $this->apiKey,
                ],
                'query' => [
                    'customer' => $request->customer_id,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json([
                'success' => true,
                'keys' => $data['data'],
                'message' => 'Pix keys retrieved successfully',
            ]);

        } catch (\Exception $e) {
            Log::error('Error listing Pix keys: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to list Pix keys: ' . $e->getMessage(),
            ], 500);
        }
    }

    


public function completePixPaymentFlow(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'customer' => 'required|string',
        'value' => 'required|numeric|min:0.01',
        'description' => 'nullable|string',
    ]);

    try {
        // Step 1: Create PIX payment
        $paymentData = [
            'customer' => $request->input('customer'),
            'billingType' => 'PIX',
            'value' => $request->input('value'),
            'dueDate' => now()->addDays(1)->format('Y-m-d'),
            'description' => $request->input('description', 'Pagamento PIX Teste'),
        ];

        $client = new \GuzzleHttp\Client();
        
        // Create payment
        $createResponse = $client->request('POST', env('ASAAS_SANDBOX_URL') . '/v3/payments', [
            'headers' => [
                'accept' => 'application/json',
                'access_token' => env('ASAAS_API_KEY'),
                'content-type' => 'application/json',
            ],
            'json' => $paymentData
        ]);

        if ($createResponse->getStatusCode() < 200 || $createResponse->getStatusCode() >= 300) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar pagamento',
            ], 400);
        }

        $paymentCreated = json_decode($createResponse->getBody(), true);
        $paymentId = $paymentCreated['id'];

        // Step 2: Get QR Code
        $qrResponse = $client->request('GET', env('ASAAS_SANDBOX_URL') . "/v3/payments/{$paymentId}/pixQrCode", [
            'headers' => [
                'accept' => 'application/json',
                'access_token' => env('ASAAS_API_KEY'),
            ],
        ]);

        $qrCodeData = null;
        if ($qrResponse->getStatusCode() >= 200 && $qrResponse->getStatusCode() < 300) {
            $qrCodeData = json_decode($qrResponse->getBody(), true);
        }

        // Step 3: Simulate payment (opcional - apenas para teste)
        if ($request->input('auto_pay', false)) {
            sleep(2); // Simula tempo para processar
            
            $payResponse = $client->request('POST', env('ASAAS_SANDBOX_URL') . "/v3/payments/{$paymentId}/receiveInCash", [
                'headers' => [
                    'accept' => 'application/json',
                    'access_token' => env('ASAAS_API_KEY'),
                    'content-type' => 'application/json',
                ],
                'json' => [
                    'paymentDate' => now()->format('Y-m-d'),
                    'notifyCustomer' => true,
                ]
            ]);

            if ($payResponse->getStatusCode() >= 200 && $payResponse->getStatusCode() < 300) {
                $paymentCreated = json_decode($payResponse->getBody(), true);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Fluxo PIX criado com sucesso',
            'payment' => [
                'id' => $paymentCreated['id'],
                'status' => $paymentCreated['status'],
                'value' => $paymentCreated['value'],
                'dueDate' => $paymentCreated['dueDate'],
                'invoiceUrl' => $paymentCreated['invoiceUrl'] ?? null,
            ],
            'qr_code' => $qrCodeData,
            'instructions' => [
                'step_1' => 'Pagamento criado com status: ' . $paymentCreated['status'],
                'step_2' => 'QR Code gerado' . ($qrCodeData ? ' ✅' : ' ❌'),
                'step_3' => 'Para simular pagamento, chame: POST /payments/pix/simulate com payment_id',
                'step_4' => 'Para verificar status, chame: GET /payments/status com payment_id',
            ]
        ], 201);

    } catch (\Exception $e) {
        Log::error('Error in complete PIX flow', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erro no fluxo PIX: ' . $e->getMessage(),
        ], 500);
    }
}

    public function createPixPayment(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'customer' => 'required|string',
            'billingType' => 'required|string|in:PIX',
            'value' => 'required|numeric|min:0.01',
            'dueDate' => 'required|date',
            'description' => 'nullable|string|max:500',
            'externalReference' => 'nullable|string|max:255',
            'installmentCount' => 'nullable|integer|min:1',
            'installmentValue' => 'nullable|numeric',
            'discount' => 'nullable|array',
            'interest' => 'nullable|array',
            'fine' => 'nullable|array',
            'postalService' => 'nullable|boolean',
        ]);

        try {
            // Prepare payment data
            $paymentData = [
                'customer' => $request->input('customer'),
                'billingType' => $request->input('billingType'), // PIX
                'value' => $request->input('value'),
                'dueDate' => $request->input('dueDate'),
            ];

            // Add optional fields if provided
            if ($request->has('description')) {
                $paymentData['description'] = $request->input('description');
            }

            if ($request->has('externalReference')) {
                $paymentData['externalReference'] = $request->input('externalReference');
            }

            if ($request->has('installmentCount')) {
                $paymentData['installmentCount'] = $request->input('installmentCount');
            }

            if ($request->has('installmentValue')) {
                $paymentData['installmentValue'] = $request->input('installmentValue');
            }

            if ($request->has('discount')) {
                $paymentData['discount'] = $request->input('discount');
            }

            if ($request->has('interest')) {
                $paymentData['interest'] = $request->input('interest');
            }

            if ($request->has('fine')) {
                $paymentData['fine'] = $request->input('fine');
            }

            if ($request->has('postalService')) {
                $paymentData['postalService'] = $request->input('postalService');
            }

            // Make request to Asaas API using Guzzle
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', env('ASAAS_SANDBOX_URL') . '/v3/payments', [
                'headers' => [
                    'accept' => 'application/json',
                    'access_token' => env('ASAAS_API_KEY'),
                    'content-type' => 'application/json',
                ],
                'json' => $paymentData
            ]);

            // Log the response for debugging
            Log::info('Asaas PIX payment created', [
                'payment_data' => $paymentData,
                'status_code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody(), true),
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $data = json_decode($response->getBody(), true);

                // Check for error response
                if (isset($data['errors']) || isset($data['error'])) {
                    $errorMessage = isset($data['errors']) ? json_encode($data['errors']) : ($data['error']['description'] ?? 'Erro desconhecido');
                    Log::error('Asaas API returned an error', [
                        'payment_data' => $paymentData,
                        'error' => $errorMessage,
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro na criação do pagamento: ' . $errorMessage,
                    ], 400);
                }

                // Return successful response with payment data
                return response()->json([
                    'success' => true,
                    'message' => 'Pagamento PIX criado com sucesso',
                    'payment' => [
                        'id' => $data['id'],
                        'status' => $data['status'],
                        'value' => $data['value'],
                        'dueDate' => $data['dueDate'],
                        'invoiceUrl' => $data['invoiceUrl'] ?? null,
                        'bankSlipUrl' => $data['bankSlipUrl'] ?? null,
                        'pixTransaction' => $data['pixTransaction'] ?? null,
                    ],
                    'full_response' => $data, // Include full response for debugging (remove in production)
                ], 201);
            }

            // Handle unsuccessful API response
            Log::error('Asaas API error creating payment', [
                'payment_data' => $paymentData,
                'status' => $response->getStatusCode(),
                'response' => $response->getBody()->getContents(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar pagamento: HTTP ' . $response->getStatusCode(),
                'status_code' => $response->getStatusCode(),
            ], $response->getStatusCode());

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Handle 4xx errors (client errors)
            $response = $e->getResponse();
            $errorBody = json_decode($response->getBody()->getContents(), true);
            
            Log::error('Asaas API client error', [
                'payment_data' => $paymentData ?? [],
                'status' => $response->getStatusCode(),
                'error' => $errorBody,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . ($errorBody['errors'][0]['description'] ?? 'Dados inválidos'),
                'errors' => $errorBody['errors'] ?? [],
            ], $response->getStatusCode());

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Handle 5xx errors (server errors)
            Log::error('Asaas API server error', [
                'payment_data' => $paymentData ?? [],
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor de pagamento',
            ], 500);

        } catch (\Exception $e) {
            // Handle any other unexpected errors
            Log::error('Error creating PIX payment', [
                'payment_data' => $paymentData ?? [],
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao criar pagamento PIX',
            ], 500);
        }
    }

      public function deletePixKey(Request $request)
    {
       
        try {
            // Validate request data
            $request->validate([
                'key_id' => 'required|string',
            ]);

            // Attempt to delete the key using DELETE method
            $response = $this->client->delete("{$this->baseUrl}/v3/pix/addressKeys/{$request->key_id}", [
                'headers' => [
                    'accept' => 'application/json',
                    'access_token' => $this->apiKey,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            // Log the response for debugging
            Log::info('Pix key deletion response', [
                'key_id' => $request->key_id,
                'response' => $data,
                'status_code' => $response->getStatusCode(),
                'raw_response' => $response->getBody()->getContents(),
            ]);

            return response()->json($data, $response->getStatusCode());

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 500;
            $rawResponse = $response ? $response->getBody()->getContents() : '';
            $errorData = $rawResponse ? json_decode($rawResponse, true) : null;

            $errorMessage = $errorData && is_array($errorData) && isset($errorData['errors'][0]['description'])
                ? $errorData['errors'][0]['description']
                : $e->getMessage();

            Log::error('Error deleting Pix key', [
                'key_id' => $request->key_id,
                'status_code' => $statusCode,
                'error' => $errorMessage,
                'raw_response' => $rawResponse,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Pix key: ' . $errorMessage,
            ], $statusCode);
        } catch (\Exception $e) {
            Log::error('Unexpected error deleting Pix key', [
                'key_id' => $request->key_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        Log::info('Webhook recebido', ['payload' => $payload]);

        if (isset($payload['event']) && in_array($payload['event'], ['PAYMENT_CONFIRMED', 'PAYMENT_RECEIVED'])) {
            $paymentId = $payload['payment']['id'] ?? null;
            $status = $payload['payment']['status'] ?? null;

            if ($paymentId && $status === 'RECEIVED') {
                Log::info('Pagamento confirmado via webhook', ['payment_id' => $paymentId]);
            }
        }

        return response()->json(['success' => true], 200);
    }


     private static function convertToUSFormat($date)
    {
        $date = str_replace('/', '-', $date);
        return \Carbon\Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
    }

    public function fazerAgendamentoPix(Request $request)
    {
        try {
            // Validar os dados recebidos
            $validated = $request->validate([
                'aluno_id' => 'required|exists:alunos,id',
                'professor_id' => 'required|exists:professores,id',
                'valor_aula' => 'required|numeric|min:0.01', // Alterado para numeric e min:0.01
                'modalidade_id' => 'required|exists:modalidade,id',
                'data_aula' => 'required',
                // 'hora_aula' => 'required',
                'titulo' => 'required|string|max:255',
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

         
             $empresa = $professor->usuario->empresa->id;
             
            if (!$empresa) {
                Log::error('Empresa not found for professor', [
                    'professor_id' => $validated['professor_id'],
                ]);
                return redirect()->back()->with('error', 'Empresa não encontrada para o professor.');
            }

            $gateway = PagamentoGateway::where('empresa_id', $empresa)
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

            // Verificar limite noturno do PIX
            $currentHour = now()->hour;
            $isNocturnal = $currentHour >= 20 || $currentHour < 6; // Horário noturno: 20h às 6h
            if ($isNocturnal && $validated['payment_method'] === 'pix' && $validated['valor_aula'] > 1000) {
                Log::warning('Transação PIX noturna no sandbox pode estar sujeita a limites', [
                    'valor_aula' => $validated['valor_aula'],
                    'hora_atual' => now()->toDateTimeString(),
                ]);
                return redirect()->back()->with('error', 'Transações PIX noturnas (20h às 6h) no sandbox têm limite de R$ 1.000,00. Tente um valor menor ou confirme manualmente no painel da Asaas.');
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

            // Calcular tarifa e valor total da cobrança
            $tariff = $gateway->tariff_type == 'percentage'
                ? $validated['valor_aula'] * ($gateway->tariff_value / 100)
                : $gateway->tariff_value;
            $valor_cobranca = round($validated['valor_aula'] + $tariff, 2); // Arredondar para evitar erros de ponto flutuante

            // Verificar se a soma dos valores do split corresponde ao valor total
            $splitTotal = round($validated['valor_aula'] + $tariff, 2);
            if (abs($valor_cobranca - $splitTotal) > 0.01) {
                Log::error('Split amount mismatch', [
                    'valor_cobranca' => $valor_cobranca,
                    'valor_aula' => $validated['valor_aula'],
                    'tariff' => $tariff,
                    'split_total' => $splitTotal,
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
                        'fixedValue' => round($validated['valor_aula'], 2),
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
            return redirect()->back()->with('error', 'Erro ao criar cobrança: ' . (isset($cobranca['errors']) ? json_encode($cobranca['errors']) : 'Erro desconhecido'));
        } catch (\Exception $e) {
            Log::error('Payment creation failed with exception', [
                'error' => $e->getMessage(),
                'cobrancaData' => $cobrancaData ?? null,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Erro ao criar cobrança: ' . $e->getMessage());
        }
    }



  public function payPixQrCode(Request $request)
    {       
        $request->validate([
            'value' => 'required|numeric|min:0.01',
            'payload' => 'required|string' // QR Code payload é obrigatório
        ]);

        $payload = $request->input('payload');
        $value = $request->input('value');
        $scheduleDate = $request->input('scheduleDate'); // Opcional

        try {
            // Criar cliente HTTP para a requisição
            $client = new \GuzzleHttp\Client();
            
            // Preparar dados para envio
            $requestData = [
                'qrCode' => [
                    'payload' => $payload
                ],
                'value' => $value
            ];

            // Adicionar scheduleDate se fornecido
            if ($scheduleDate) {
                $requestData['scheduleDate'] = $scheduleDate;
            }

            $response = $client->request('POST', env('ASAAS_SANDBOX_URL') . "/v3/pix/qrCodes/pay", [
                'headers' => [
                    'accept' => 'application/json',
                    'access_token' => env('ASAAS_API_KEY'),
                    'content-type' => 'application/json',
                ],
                'json' => $requestData,
            ]);

            // Log da resposta para depuração
            Log::debug('Asaas PIX QR Code payment response', [
                'status_code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody(), true),
                'payload' => $payload,
                'value' => $value,
            ]);

            // Verificar se a requisição foi bem-sucedida
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $data = json_decode($response->getBody(), true);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Pagamento PIX realizado com sucesso!',
                    'data' => $data,
                ], 200);
            }

            // Log de erro caso a requisição falhe
            Log::error('Asaas PIX payment error', [
                'status' => $response->getStatusCode(),
                'response' => $response->getBody()->getContents(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao realizar o pagamento PIX: HTTP ' . $response->getStatusCode(),
            ], $response->getStatusCode());

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Erro específico do cliente HTTP (4xx)
            $response = $e->getResponse();
            $errorBody = json_decode($response->getBody()->getContents(), true);
            
            Log::error('Asaas PIX payment client error', [
                'status' => $response->getStatusCode(),
                'error' => $errorBody,
                'payload' => $payload,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro na requisição: ' . ($errorBody['errors'][0]['description'] ?? 'Erro desconhecido'),
                'errors' => $errorBody['errors'] ?? [],
            ], $response->getStatusCode());

        } catch (\Exception $e) {
            // Log de erro em caso de exceção geral
            Log::error('Error processing PIX QR Code payment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao processar o pagamento PIX.',
            ], 500);
        }
    }

    public function simulatePixPayment(Request $request)
    {
     
        // Validar o request
        $request->validate([
            'payment_id' => 'required|string',
        ]);

        $paymentId = $request->input('payment_id');

        try {
            // Fazer uma chamada à API da Asaas para simular o pagamento (endpoint específico do sandbox)
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', env('ASAAS_SANDBOX_URL') . "/v3/payments/{$paymentId}/simulate", [
                'headers' => [
                    'accept' => 'application/json',
                    'access_token' => env('ASAAS_API_KEY'),
                ],
            ]);

            // Log para depuração
            Log::debug('Asaas API response for payment simulation', [
                'payment_id' => $paymentId,
                'status_code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody(), true),
            ]);

            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $data = json_decode($response->getBody(), true);

                // Verificar se a simulação foi bem-sucedida
                if (isset($data['status']) && $data['status'] === 'RECEIVED') {
                    return response()->json([
                        'success' => true,
                        'message' => 'Pagamento simulado com sucesso!',
                        'status' => $data['status'],
                        'data' => $data,
                    ], 200);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Falha ao simular o pagamento.',
                    'data' => $data,
                ], 400);
            }

            Log::error('Asaas API error on payment simulation', [
                'payment_id' => $paymentId,
                'status' => $response->getStatusCode(),
                'response' => $response->getBody()->getContents(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao simular o pagamento: HTTP ' . $response->getStatusCode(),
            ], $response->getStatusCode());

        } catch (\Exception $e) {
            dd($e);
            Log::error('Error simulating PIX payment', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao simular o pagamento.',
            ], 500);
        }
    }

    public function createPixKey(Request $request)
    {
       
        try {
            $request->validate([
                'type' => 'required|in:EVP,CPF,CNPJ,EMAIL,PHONE',
                'key' => 'required_unless:type,EVP|string',
                'usuario_id' => 'required|exists:professores,usuario_id', // Validate usuario_id exists in professores
            ]);

            if (!$this->apiKey) {
                return response()->json([
                    'success' => false,
                    'error' => 'API Key do Asaas não configurada',
                ], 500);
            }

            $baseUrl = env('ASAAS_ENV', 'sandbox') === 'sandbox'
                ? 'https://api-sandbox.asaas.com/v3'
                : 'https://api.asaas.com/v3';

            $payload = [
                'type' => $request->type,
            ];

            if ($request->type !== 'EVP') {
                $payload['key'] = $this->formatPixKey($request->key, $request->type);
            }

            Log::debug('Criando chave PIX', [
                'type' => $request->type,
                'has_key' => isset($payload['key']['id']),
                'base_url' => $baseUrl,
                'usuario_id' => $request->usuario_id,
            ]);

            $response = $this->client->post("$baseUrl/pix/addressKeys", [
                'headers' => [
                    'access_token' => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $pixKeyData = json_decode($response->getBody(), true);

            // Save Pix key to Professor model
            $professor = Professor::where('usuario_id', $request->usuario_id)->first();

            if (!$professor) {
                Log::error('Professor não encontrado para salvar chave PIX', [
                    'usuario_id' => $request->usuario_id,
                    'pix_key_id' => $pixKeyData['id'] ?? 'N/A',
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Professor não encontrado para o usuario_id fornecido',
                ], 404);
            }

            // Store Pix key data in asaas_pix_key (e.g., as JSON or key value)
            $professor->asaas_pix_key = json_encode([
                'id' => $pixKeyData['id'],
                'type' => $pixKeyData['type'],
                'key' => $pixKeyData['key'] ?? null,
                'status' => $pixKeyData['status'],
                'created_at' => $pixKeyData['dateCreated'] ?? null,
            ]);
            $professor->save();

            Log::info('Chave PIX criada e salva com sucesso', [
                'id' => $pixKeyData['id'] ?? 'N/A',
                'type' => $pixKeyData['type'] ?? 'N/A',
                'status' => $pixKeyData['status'] ?? 'N/A',
                'professor_id' => $professor->id,
            ]);

            return response()->json([
                'success' => true,
                'pix_key' => [
                    'id' => $pixKeyData['id'],
                    'type' => $pixKeyData['type'],
                    'key' => $pixKeyData['key'] ?? null,
                    'status' => $pixKeyData['status'],
                    'created_at' => $pixKeyData['dateCreated'] ?? null,
                    'owner' => [
                        'name' => $pixKeyData['owner']['name'] ?? null,
                        'document' => $pixKeyData['owner']['document'] ?? null,
                    ],
                ],
            ], 201);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body';
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 400;

            Log::error('Erro na API do Asaas ao criar chave PIX: ' . $e->getMessage(), [
                'status_code' => $statusCode,
                'response_body' => $responseBody,
                'payload_sent' => $payload,
                'usuario_id' => $request->usuario_id,
            ]);

            $errorData = json_decode($responseBody, true);
            $errorMessage = 'Erro desconhecido';

            if ($errorData && isset($errorData['errors']) && is_array($errorData['errors'])) {
                $errorMessage = $errorData['errors'][0]['description'] ?? $errorData['errors'][0]['code'] ?? 'Erro na validação';
            } elseif ($errorData && isset($errorData['message'])) {
                $errorMessage = $errorData['message'];
            }

            return response()->json([
                'success' => false,
                'error' => 'Erro ao criar chave PIX: ' . $errorMessage,
                'details' => $errorData,
            ], $statusCode);

        } catch (\Exception $e) {
            Log::error('Erro inesperado ao criar chave PIX: ' . $e->getMessage(), [
                'usuario_id' => $request->usuario_id,
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erro interno ao criar chave PIX: ' . $e->getMessage(),
            ], 500);
        }
    }

/**
 * Método alternativo usando endpoint correto do Asaas
 */
public function createPixKeyAlternative(Request $request)
{
    // Para o Asaas, pode ser necessário usar endpoint diferente
    $request->validate([
        'type' => 'required|in:EVP,CPF,CNPJ,EMAIL,PHONE',
        'key' => 'nullable|string', // Opcional para todos os tipos
    ]);

    $apiKey = env('ASAAS_API_KEY');
    
    if (!$apiKey) {
        return response()->json([
            'success' => false,
            'error' => 'API Key do Asaas não configurada',
        ], 500);
    }

    $baseUrl = env('ASAAS_ENV', 'sandbox') === 'sandbox'
        ? 'https://api-sandbox.asaas.com/v3'
        : 'https://api.asaas.com/v3';

    $client = new Client();

    // Payload simplificado - alguns provedores só aceitam tipo EVP para chave aleatória
    $payload = ['type' => $request->type];
    
    // Se não for EVP e tiver chave, adiciona
    if ($request->type !== 'EVP' && $request->filled('key')) {
        $payload['key'] = $this->formatPixKey($request->key, $request->type);
    }

    Log::debug('Tentando criar chave PIX (método alternativo)', $payload);

    try {
        // Tenta primeiro o endpoint padrão
        $response = $client->post("$baseUrl/pix/addressKeys", [
            'headers' => [
                'access_token' => $apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => $payload,
        ]);

        $pixKeyData = json_decode($response->getBody(), true);

        return response()->json([
            'success' => true,
            'pix_key' => $pixKeyData,
            'method_used' => 'standard_endpoint'
        ], 201);

    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : '';
        
        Log::warning('Endpoint padrão falhou, tentando método alternativo', [
            'error' => $e->getMessage(),
            'response' => $responseBody
        ]);

        // Se falhar, tenta endpoint alternativo (alguns provedores usam /pix/keys)
        try {
            $response = $client->post("$baseUrl/pix/keys", [
                'headers' => [
                    'access_token' => $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $pixKeyData = json_decode($response->getBody(), true);

            return response()->json([
                'success' => true,
                'pix_key' => $pixKeyData,
                'method_used' => 'alternative_endpoint'
            ], 201);

        } catch (\Exception $e2) {
            // Se ambos falharem, retorna erro original
            $errorData = json_decode($responseBody, true);
            
            return response()->json([
                'success' => false,
                'error' => 'Erro ao criar chave PIX. Verifique os tipos suportados.',
                'available_types' => ['EVP', 'CPF', 'CNPJ', 'EMAIL', 'PHONE'],
                'details' => $errorData,
                'tried_endpoints' => [
                    "$baseUrl/pix/addressKeys",
                    "$baseUrl/pix/keys"
                ]
            ], 400);
        }

    } catch (\Exception $e) {
        Log::error('Erro inesperado: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Erro interno: ' . $e->getMessage(),
        ], 500);
    }
}
private function formatPixKey($key, $type)
{
    switch ($type) {
        case 'CPF':
        case 'CNPJ':
            // Remove caracteres especiais, mantém apenas números
            return preg_replace('/[^0-9]/', '', $key);
            
        case 'EMAIL':
            // Remove espaços e converte para minúsculo
            return strtolower(trim($key));
            
        case 'PHONE':
            // Remove caracteres especiais, mantém apenas números
            // Formato esperado: +5511999999999
            $cleanPhone = preg_replace('/[^0-9+]/', '', $key);
            
            // Se não começar com +55, adiciona
            if (!str_starts_with($cleanPhone, '+55')) {
                // Remove qualquer + no início e adiciona +55
                $cleanPhone = '+55' . ltrim($cleanPhone, '+');
            }
            
            return $cleanPhone;
            
        default:
            return $key;
    }
}





/**
     * Store a new PIX key for the authenticated user's professor.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'pix_key_type' => 'required|in:cpf,email,phone,random',
            'pix_key_value' => 'required_unless:pix_key_type,random',
            'wallet_id' => 'required|string',
        ]);

        // Ensure the wallet_id matches the user's professor record
        $professor = Auth::user()->professor;
        if (!$professor || $professor->asaas_wallet_id !== $validated['wallet_id']) {
            return response()->json([
                'success' => false,
                'message' => 'Subconta Asaas inválida ou não encontrada.',
            ], 403);
        }

        // Prepare data for Asaas API
        $asaasData = [
            'walletId' => $validated['wallet_id'],
            'type' => strtoupper($validated['pix_key_type']),
            'value' => $validated['pix_key_type'] === 'random' ? null : $validated['pix_key_value'],
        ];

        // Call Asaas API to create PIX key
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('ASAAS_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.asaas.com/v3/pix/addressKeys', $asaasData);

        $data = $response->json();

        if ($response->successful() && isset($data['key'])) {
            // Update or save the PIX key in the professores table
            $professor->asaas_pix_key = $data['key'];
            $professor->save();

            return response()->json([
                'success' => true,
                'message' => 'Chave PIX criada com sucesso!',
                'data' => ['pix_key' => $data['key']],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $data['errors'][0]['description'] ?? 'Erro ao criar chave PIX.',
        ], 400);
    }

    public function criarChavePix(Request $request)
        {
            $validated = $request->validate([
                'pix_key_type' => 'required|in:cpf,email,phone,random',
                'pix_key_value' => 'required_unless:pix_key_type,random',
                'wallet_id' => 'required|string',
            ]);

            // Chamada à API do Asaas para criar a chave PIX
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('ASAAS_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.asaas.com/v3/pix/addressKeys', [
                'walletId' => $validated['wallet_id'],
                'type' => strtoupper($validated['pix_key_type']),
                'value' => $validated['pix_key_type'] === 'random' ? null : $validated['pix_key_value'],
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['key'])) {
                // Salvar a chave PIX no banco
                $professor = Auth::user()->professor;
                $professor->asaas_pix_key = $data['key'];
                $professor->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Chave PIX criada com sucesso!',
                    'data' => ['pix_key' => $data['key']],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $data['errors'][0]['description'] ?? 'Erro ao criar chave PIX.',
            ], 400);
        }



    /**
     * Check the status of a PIX payment using Asaas API.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
 

public function checkPixStatus(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'payment_id' => 'required|string',
    ]);

    // Get the payment ID from the request
    $paymentId = $request->input('payment_id');
   
    try {
        // Make a request to the Asaas API status endpoint using Guzzle
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', env('ASAAS_SANDBOX_URL') . "/v3/payments/{$paymentId}/status", [
            'headers' => [
                'accept' => 'application/json',
                'access_token' => env('ASAAS_API_KEY'),
            ],
        ]);

        // Log the full response for debugging
        Log::debug('Asaas API response for payment status', [
            'payment_id' => $paymentId,
            'status_code' => $response->getStatusCode(),
            'response' => json_decode($response->getBody(), true),
        ]);

        // Check if the request was successful
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $data = json_decode($response->getBody(), true);

            // Check for error response (e.g., {"errors": [...]})
            if (isset($data['errors']) || isset($data['error'])) {
                $errorMessage = isset($data['errors']) ? json_encode($data['errors']) : ($data['error']['description'] ?? 'Erro desconhecido');
                Log::error('Asaas API returned an error', [
                    'payment_id' => $paymentId,
                    'error' => $errorMessage,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Erro na API de pagamento: ' . $errorMessage,
                ], 400);
            }
      

            // Check for status field
            if (isset($data['status'])) {
                return response()->json([
                    'success' => true,
                    'status' => $data['status'], // e.g., PENDING, RECEIVED, CONFIRMED, etc.
                    'data' => $data, // Retorna todos os dados do pagamento
                ], 200);
            }

            // Handle case where status is missing
            Log::warning('Asaas API missing status field', [
                'payment_id' => $paymentId,
                'response' => $data,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Status do pagamento não encontrado na resposta da API.',
                'response' => $data, // Include for debugging (remove in production)
            ], 400);
        }

        // Handle unsuccessful API response (e.g., 404, 401)
        Log::error('Asaas API error', [
            'payment_id' => $paymentId,
            'status' => $response->getStatusCode(),
            'response' => $response->getBody()->getContents(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erro ao consultar a API de pagamento: HTTP ' . $response->getStatusCode(),
            'status_code' => $response->getStatusCode(),
        ], $response->getStatusCode());

    } catch (\Exception $e) {
        // Log any unexpected errors
        Log::error('Error checking PIX payment status', [
            'payment_id' => $paymentId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erro interno ao verificar o status do pagamento.',
        ], 500);
    }
}


    public function generatePixQrCode(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:professores,usuario_id',
            'value' => 'required|numeric|min:0.01|max:999999.99',
            'description' => 'nullable|string|max:200',
            'due_date' => 'nullable|date_format:Y-m-d|after_or_equal:today',
        ]);

        if (!$this->apiKey) {
            return response()->json([
                'success' => false,
                'error' => 'API Key do Asaas não configurada',
            ], 500);
        }

        $baseUrl = env('ASAAS_ENV', 'sandbox') === 'sandbox'
            ? 'https://api-sandbox.asaas.com/v3'
            : 'https://api.asaas.com/v3';

        $professor = Professor::where('usuario_id', $request->usuario_id)->first();
        if (!$professor) {
            return response()->json([
                'success' => false,
                'error' => 'Professor não encontrado para o usuario_id fornecido',
            ], 404);
        }

        if (!$professor->asaas_customer_id) {
            return response()->json([
                'success' => false,
                'error' => 'Nenhum customer_id associado ao professor',
            ], 400);
        }

        $pixKeys = $professor->asaas_pix_key ? json_decode($professor->asaas_pix_key, true) : [];
        if (empty($pixKeys)) {
            return response()->json([
                'success' => false,
                'error' => 'Nenhuma chave PIX associada ao professor',
            ], 400);
        }

        Log::debug('Gerando PIX QR Code', [
            'usuario_id' => $request->usuario_id,
            'customer_id' => $professor->asaas_customer_id,
            'value' => $request->value,
            'base_url' => $baseUrl,
            'pix_keys_count' => count($pixKeys),
        ]);

        try {
            $paymentResponse = $this->client->post("$baseUrl/payments", [
                'headers' => [
                    'access_token' => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'customer' => $professor->asaas_customer_id,
                    'billingType' => 'PIX',
                    'value' => (float) $request->value,
                    'dueDate' => $request->due_date ?? now()->addDay()->format('Y-m-d'),
                    'description' => $request->description ?? 'Pagamento via PIX',
                    'externalReference' => 'PIX-' . uniqid(),
                    'installmentCount' => 1,
                    'installmentValue' => (float) $request->value, // Added to fix invalid_installmentValue error
                ],
            ]);

            $paymentData = json_decode($paymentResponse->getBody(), true);

            if (!isset($paymentData['id'])) {
                throw new \Exception('ID do pagamento não retornado pela API');
            }

            $paymentId = $paymentData['id'];

            Log::debug('Pagamento criado com sucesso', [
                'payment_id' => $paymentId,
                'status' => $paymentData['status'] ?? 'unknown',
            ]);

            $qrCodeResponse = $this->client->get("$baseUrl/payments/$paymentId/pixQrCode", [
                'headers' => [
                    'access_token' => $this->apiKey,
                    'Accept' => 'application/json',
                ],
            ]);

            $qrCodeData = json_decode($qrCodeResponse->getBody(), true);

            if (!isset($qrCodeData['encodedImage']) || !isset($qrCodeData['payload'])) {
                throw new \Exception('Dados do QR Code incompletos na resposta da API');
            }

            Log::debug('QR Code gerado com sucesso', [
                'payment_id' => $paymentId,
                'has_image' => isset($qrCodeData['encodedImage']),
                'has_payload' => isset($qrCodeData['payload']),
            ]);

            return response()->json([
                'success' => true,
                'payment' => [
                    'id' => $paymentId,
                    'status' => $paymentData['status'],
                    'value' => $paymentData['value'],
                    'due_date' => $paymentData['dueDate'],
                    'invoice_url' => $paymentData['invoiceUrl'] ?? null,
                ],
                'qr_code' => [
                    'encoded_image' => $qrCodeData['encodedImage'],
                    'payload' => $qrCodeData['payload'],
                    'expiration_date' => $qrCodeData['expirationDate'] ?? null,
                ],
            ], 200);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body';
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 400;

            Log::error('Erro na API do Asaas (Cliente): ' . $e->getMessage(), [
                'status_code' => $statusCode,
                'response_body' => $responseBody,
                'usuario_id' => $request->usuario_id,
            ]);

            $errorData = json_decode($responseBody, true);
            $errorMessage = 'Erro desconhecido';

            if ($errorData && isset($errorData['errors']) && is_array($errorData['errors'])) {
                $errorMessage = $errorData['errors'][0]['description'] ?? $errorData['errors'][0]['code'] ?? 'Erro na validação';
            } elseif ($errorData && isset($errorData['message'])) {
                $errorMessage = $errorData['message'];
            }

            return response()->json([
                'success' => false,
                'error' => 'Erro ao gerar QR Code PIX: ' . $errorMessage,
                'details' => $errorData,
            ], $statusCode);

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body';

            Log::error('Erro no servidor do Asaas: ' . $e->getMessage(), [
                'response_body' => $responseBody,
                'usuario_id' => $request->usuario_id,
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erro no servidor do Asaas. Tente novamente mais tarde.',
            ], 500);

        } catch (\Exception $e) {
            Log::error('Erro geral na geração do QR Code PIX: ' . $e->getMessage(), [
                'usuario_id' => $request->usuario_id,
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erro interno: ' . $e->getMessage(),
            ], 500);
        }
    }

    

}