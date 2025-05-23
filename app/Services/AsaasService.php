<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AsaasService
{
    private $headers;
    private $url;
    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->headers = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ];
        $this->url = env('APP_ENV') === 'production' 
            ? env('ASAAS_URL', 'https://api.asaas.com') 
            : env('ASAAS_SANDBOX_URL', 'https://sandbox.asaas.com');
    }

    /**
     * Test the connection to the Asaas API.
     *
     * @param string $apiKey
     * @return array
     * @throws \Exception
     */
    public function testConnection($apiKey)
    {
        $testUrl = rtrim($this->url, '/') . '/api/v3/customers';

        try {
            Log::info('Testing connection to URL: ' . $testUrl . ' with API key: ' . $apiKey);
            $response = $this->client->request('GET', $testUrl, [
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
                'query' => ['limit' => 1],
            ]);

            Log::info('Connection response: ' . $response->getBody());
            return $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            Log::error('Error testing connection: ' . $e->getMessage());
            throw new \Exception('Error testing connection: ' . $e->getMessage());
        }
    }

    /**
     * Create a new customer in Asaas.
     *
     * @param array $data
     * @param string $apiKey
     * @param string $mode
     * @return array
     * @throws \Exception
     */
    public function createCustomer($data, $apiKey, $mode)
    {
        $url = rtrim($this->url, '/') . '/api/v3/customers';

        Log::info('Data sent to create customer: ' . json_encode($data));

        try {
            $response = $this->client->request('POST', $url, [
                'body' => json_encode($data),
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);

            return $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage());
            throw new \Exception('Error creating customer: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing customer in Asaas.
     *
     * @param string $customerId
     * @param array $data
     * @param string $apiKey
     * @param string $mode
     * @return array
     * @throws \Exception
     */
    public function updateCustomer($customerId, $data, $apiKey, $mode)
    {
        $url = rtrim($this->url, '/') . '/api/v3/customers/' . $customerId;

        Log::info('Data sent to update customer: ' . json_encode($data));

        try {
            $response = $this->client->request('POST', $url, [
                'body' => json_encode($data),
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);

            return $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            Log::error('Error updating customer: ' . $e->getMessage() . ' - Data: ' . json_encode($data));
            throw new \Exception('Error updating customer: ' . $e->getMessage());
        }
    }

    /**
     * Create a new payment in Asaas.
     *
     * @param array $data
     * @param string $apiKey
     * @param string $mode
     * @return array
     * @throws \Exception
     */
    public function cobranca($data, $apiKey, $mode)
    {
        $url = rtrim($this->url, '/') . '/api/v3/payments';

        Log::info('Data sent to create payment: ' . json_encode($data));

        try {
            $response = $this->client->request('POST', $url, [
                'body' => json_encode($data),
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);

            return $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            Log::error('Error creating payment: ' . $e->getMessage() . ' - Data: ' . json_encode($data));
            throw new \Exception('Error creating payment: ' . $e->getMessage());
        }
    }

    /**
     * Create PIX payment and get QR Code
     *
     * @param array $data - Payment data
     * @param string $apiKey
     * @return array
     * @throws \Exception
     */
    public function createPixPayment($data, $apiKey)
    {
        // Força o billingType para PIX
        $data['billingType'] = 'PIX';
        
        $url = rtrim($this->url, '/') . '/api/v3/payments';

        Log::info('Creating PIX payment: ' . json_encode($data));

        try {
            $response = $this->client->request('POST', $url, [
                'body' => json_encode($data),
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);

            $payment = $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));

            // Se não veio o QR Code na resposta, buscar separadamente
            if (!isset($payment['pixTransaction']) || empty($payment['pixTransaction']['encodedImage'])) {
                $pixData = $this->getPixQrCode($payment['id'], $apiKey);
                $payment['pixTransaction'] = $pixData;
            }

            return $payment;

        } catch (\Exception $e) {
            Log::error('Error creating PIX payment: ' . $e->getMessage() . ' - Data: ' . json_encode($data));
            throw new \Exception('Error creating PIX payment: ' . $e->getMessage());
        }
    }

    /**
     * Get PIX QR Code for an existing payment
     *
     * @param string $paymentId
     * @param string $apiKey
     * @return array
     * @throws \Exception
     */
    public function getPixQrCode($paymentId, $apiKey)
    {
        $url = rtrim($this->url, '/') . "/api/v3/payments/{$paymentId}/pixQrCode";

        Log::info('Getting PIX QR Code for payment: ' . $paymentId);

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);

            return $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));

        } catch (\Exception $e) {
            Log::error('Error getting PIX QR Code: ' . $e->getMessage() . ' - Payment ID: ' . $paymentId);
            throw new \Exception('Error getting PIX QR Code: ' . $e->getMessage());
        }
    }

    /**
     * Check payment status
     *
     * @param string $paymentId
     * @param string $apiKey
     * @return array
     * @throws \Exception
     */
    public function getPaymentStatus($paymentId, $apiKey)
    {
        $url = rtrim($this->url, '/') . "/api/v3/payments/{$paymentId}";

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);

            return $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));

        } catch (\Exception $e) {
            Log::error('Error checking payment status: ' . $e->getMessage() . ' - Payment ID: ' . $paymentId);
            throw new \Exception('Error checking payment status: ' . $e->getMessage());
        }
    }

    /**
     * Retrieve a list of customers from Asaas.
     *
     * @param string $apiKey
     * @param string $mode
     * @return array
     * @throws \Exception
     */
    public function getClients($apiKey, $mode)
    {
        $url = rtrim($this->url, '/') . '/api/v3/customers';

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);

            return $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            Log::error('Error listing customers: ' . $e->getMessage());
            throw new \Exception('Error listing customers: ' . $e->getMessage());
        }
    }

    /**
     * Create a new Asaas subaccount for a professor.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function createSubaccount($request)
    {
        DB::beginTransaction();

        try {
            // Busca o usuário relacionado ao professor
            $professor = Professor::with('usuario')->findOrFail($request->professor_id);
            $usuario = $professor->usuario;

            // Dados completos para o Asaas
            $subaccountData = [
                'name' => $request->name ?? $usuario->nome,
                'email' => $usuario->email,
                'cpfCnpj' => $request->cpfCnpj,
                // ... outros campos
            ];

            // Chamada simplificada sem precisar passar a API key
            $response = $this->asaasService->createSubaccount($subaccountData);

            // Atualizar professor
            $professor->update([
                'asaas_customer_id' => $response['id'],
                'asaas_wallet_id' => $response['walletId']
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Subconta criada com sucesso!',
                'data' => $response
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar subconta: ' . $e->getMessage()
            ], 500);
        }
    }


    public function gerarPix(Request $request)
{
    try {
        $aluno = Auth::user()->aluno;
        $apiKey = config('asaas.api_key'); // ou como você pega a API key
        
        // 1. Garantir que tem customer
        if (!$aluno->asaas_customer_id) {
            $customerData = [
                'name' => $aluno->nome,
                'email' => $aluno->email,
                'cpfCnpj' => $aluno->cpf,
            ];
            $customer = $this->asaasService->createCustomer($customerData, $apiKey, 'sandbox');
            $aluno->asaas_customer_id = $customer['id'];
            $aluno->save();
        }
        
        // 2. Criar pagamento PIX
        $pixPayment = $this->asaasService->createPixPayment([
            'customer' => $aluno->asaas_customer_id,
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

    /**
     * Retrieve the wallet ID for a customer.
     *
     * @param string $customerId
     * @param string $apiKey
     * @param string $mode
     * @return array
     * @throws \Exception
     */
    public function getCustomerWallet($customerId, $apiKey, $mode)
    {
        $url = rtrim($this->url, '/') . "/api/v3/customers/{$customerId}";

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);

            $customer = $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));
            return ['walletId' => $customer['walletId'] ?? null];
        } catch (\Exception $e) {
            Log::error('Error retrieving wallet: ' . $e->getMessage());
            throw new \Exception('Error retrieving wallet: ' . $e->getMessage());
        }
    }

    /**
     * Handle API response and return data or throw an exception.
     *
     * @param int $statusCode
     * @param mixed $responseData
     * @return mixed
     * @throws \Exception
     */
    public function tratarResposta($statusCode, $responseData)
    {
        if ($statusCode == 200 || $statusCode == 201) {
            return $responseData;
        }

        if ($statusCode == 400 || $statusCode == 401) {
            throw new \Exception($responseData['errorMessage'] ?? 'Error in request');
        }

        throw new \Exception('Unknown server response');
    }
}