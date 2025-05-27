<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PixQrController extends Controller
{



  public function createPixKey(Request $request)
{
    // Validação dos dados de entrada - tipos corretos para Asaas
    $request->validate([
        'type' => 'required|in:EVP,CPF,CNPJ,EMAIL,PHONE', // EVP = chave aleatória no Asaas
        'key' => 'required_unless:type,EVP|string', // Obrigatório exceto para EVP
    ]);

    // Pegar API key do .env (usando nome consistente)
    $apiKey = env('ASAAS_API_KEY'); // Mudança: usar ASAAS_API_KEY
    
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

    // Preparar payload baseado no tipo de chave
    $payload = [
        'type' => $request->type,
    ];

    // Adicionar a chave apenas se não for EVP (chave aleatória)
    if ($request->type !== 'EVP') {
        $payload['key'] = $this->formatPixKey($request->key, $request->type);
    }

    Log::debug('Criando chave PIX', [
        'type' => $request->type,
        'has_key' => isset($payload['key']),
        'base_url' => $baseUrl
    ]);

    try {
        $response = $client->post("$baseUrl/pix/addressKeys", [
            'headers' => [
                'access_token' => $apiKey, // Consistente com outros métodos
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => $payload,
        ]);

        $pixKeyData = json_decode($response->getBody(), true);

        Log::info('Chave PIX criada com sucesso', [
            'id' => $pixKeyData['id'] ?? 'N/A',
            'type' => $pixKeyData['type'] ?? 'N/A',
            'status' => $pixKeyData['status'] ?? 'N/A'
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
            'payload_sent' => $payload
        ]);

        // Parse do erro
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
        Log::error('Erro inesperado ao criar chave PIX: ' . $e->getMessage());

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
 * Listar chaves PIX existentes
 */
public function listPixKeys()
{
    $apiKey = env('ASAAS_API_KEY');
    $baseUrl = env('ASAAS_ENV', 'sandbox') === 'sandbox'
        ? 'https://api-sandbox.asaas.com/v3'
        : 'https://api.asaas.com/v3';

    $client = new Client();

    try {
        $response = $client->get("$baseUrl/pix/addressKeys", [
            'headers' => [
                'access_token' => $apiKey,
                'Accept' => 'application/json',
            ],
        ]);

        $pixKeysData = json_decode($response->getBody(), true);

        return response()->json([
            'success' => true,
            'pix_keys' => $pixKeysData['data'] ?? [],
            'total_count' => $pixKeysData['totalCount'] ?? 0,
        ]);

    } catch (\Exception $e) {
        Log::error('Erro ao listar chaves PIX: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'error' => 'Erro ao listar chaves PIX: ' . $e->getMessage(),
        ], 500);
    }
}

/**
 * Deletar chave PIX
 */
public function deletePixKey($pixKeyId)
{
    $apiKey = env('ASAAS_API_KEY');
    $baseUrl = env('ASAAS_ENV', 'sandbox') === 'sandbox'
        ? 'https://api-sandbox.asaas.com/v3'
        : 'https://api.asaas.com/v3';

    $client = new Client();

    try {
        $response = $client->delete("$baseUrl/pix/addressKeys/$pixKeyId", [
            'headers' => [
                'access_token' => $apiKey,
                'Accept' => 'application/json',
            ],
        ]);

        Log::info('Chave PIX deletada', ['pix_key_id' => $pixKeyId]);

        return response()->json([
            'success' => true,
            'message' => 'Chave PIX deletada com sucesso',
        ]);

    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body';
        $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 400;

        Log::error('Erro ao deletar chave PIX: ' . $e->getMessage(), [
            'pix_key_id' => $pixKeyId,
            'status_code' => $statusCode,
            'response_body' => $responseBody
        ]);

        return response()->json([
            'success' => false,
            'error' => 'Erro ao deletar chave PIX',
        ], $statusCode);

    } catch (\Exception $e) {
        Log::error('Erro inesperado ao deletar chave PIX: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'error' => 'Erro interno ao deletar chave PIX',
        ], 500);
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


    
    public function generatePixQrCode(Request $request)
{
    // Validação mais robusta
    $request->validate([
        'customer_id' => 'required|string', // ID do cliente no Asaas
        'value' => 'required|numeric|min:0.01|max:999999.99', // Valor do pagamento
        'description' => 'nullable|string|max:200', // Descrição opcional
        'due_date' => 'nullable|date_format:Y-m-d|after_or_equal:today', // Data de vencimento
    ]);

    // Pegar a API key do arquivo .env
    $apiKey = env('ASAAS_KEY'); // Use a mesma variável do método anterior
    
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

    // Log para debug
    Log::debug('Gerando PIX QR Code', [
        'customer_id' => $request->customer_id,
        'value' => $request->value,
        'base_url' => $baseUrl
    ]);

    try {
        // Passo 1: Criar o pagamento PIX
        $paymentResponse = $client->post("$baseUrl/payments", [
            'headers' => [
                'access_token' => $apiKey, // MUDANÇA: usar access_token
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                'customer' => $request->customer_id,
                'billingType' => 'PIX',
                'value' => (float) $request->value, // Garantir que seja float
                'dueDate' => $request->due_date ?? now()->addDay()->format('Y-m-d'),
                'description' => $request->description ?? 'Pagamento via PIX',
                // Adicionar campos opcionais úteis
                'externalReference' => 'PIX-' . uniqid(), // Referência única
                'installmentCount' => 1, // PIX é sempre à vista
            ],
        ]);

        $paymentData = json_decode($paymentResponse->getBody(), true);
        
        if (!isset($paymentData['id'])) {
            throw new \Exception('ID do pagamento não retornado pela API');
        }
        
        $paymentId = $paymentData['id'];

        Log::debug('Pagamento criado com sucesso', [
            'payment_id' => $paymentId,
            'status' => $paymentData['status'] ?? 'unknown'
        ]);

        // Passo 2: Obter o QR Code PIX
        $qrCodeResponse = $client->get("$baseUrl/payments/$paymentId/pixQrCode", [
            'headers' => [
                'access_token' => $apiKey, // MUDANÇA: usar access_token
                'Accept' => 'application/json',
            ],
        ]);

        $qrCodeData = json_decode($qrCodeResponse->getBody(), true);

        // Verificar se os dados essenciais existem
        if (!isset($qrCodeData['encodedImage']) || !isset($qrCodeData['payload'])) {
            throw new \Exception('Dados do QR Code incompletos na resposta da API');
        }

        Log::debug('QR Code gerado com sucesso', [
            'payment_id' => $paymentId,
            'has_image' => isset($qrCodeData['encodedImage']),
            'has_payload' => isset($qrCodeData['payload'])
        ]);

        // Retornar dados completos
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
                'encoded_image' => $qrCodeData['encodedImage'], // Imagem base64
                'payload' => $qrCodeData['payload'], // Código copia e cola
                'expiration_date' => $qrCodeData['expirationDate'] ?? null,
            ],
        ], 200);

    } catch (\GuzzleHttp\Exception\ClientException $e) {
        // Erro 4xx (Bad Request, Unauthorized, etc.)
        $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body';
        $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 400;
        
        Log::error('Erro na API do Asaas (Cliente): ' . $e->getMessage(), [
            'status_code' => $statusCode,
            'response_body' => $responseBody
        ]);
        
        // Tentar fazer parse da resposta de erro
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
        // Erro 5xx (Server Error)
        $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body';
        
        Log::error('Erro no servidor do Asaas: ' . $e->getMessage(), [
            'response_body' => $responseBody
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Erro no servidor do Asaas. Tente novamente mais tarde.',
        ], 500);

    } catch (\Exception $e) {
        // Outros erros
        Log::error('Erro geral na geração do QR Code PIX: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'error' => 'Erro interno: ' . $e->getMessage(),
        ], 500);
    }
}
}