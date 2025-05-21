<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AsaasService
{
    private $headers;
    private $url;

    public function __construct()
    {
        $this->headers = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ];

        $this->url = env('APP_ENV') === 'production' ? env('ASAAS_URL', 'https://api.asaas.com') : env('ASAAS_SANDBOX_URL', 'https://sandbox.asaas.com');
    }

    public function testConnection($apiKey)
    {
        $client = new Client();
        $testUrl = rtrim($this->url, '/') . '/api/v3/customers'; // Endpoint correto

        try {
            \Log::info('Testando conexão com URL: ' . $testUrl . ' e chave: ' . $apiKey);
            $response = $client->request('GET', $testUrl, [
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
                'query' => ['limit' => 1],
            ]);

            \Log::info('Resposta da conexão: ' . $response->getBody());
            return $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            \Log::error('Erro ao testar conexão: ' . $e->getMessage());
            throw new \Exception('Erro ao testar conexão: ' . $e->getMessage());
        }
    }

    public function createCustomer($data, $apiKey, $mode)
    {
        $client = new Client();
        $url = rtrim($this->url, '/') . '/api/v3/customers';

        \Log::info('Dados enviados para criar cliente: ' . json_encode($data));

        try {
            $response = $client->request('POST', $url, [
                'body' => json_encode($data),
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);

            return $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            \Log::error('Erro ao criar cliente: ' . $e->getMessage());
            throw new \Exception('Erro ao criar cliente: ' . $e->getMessage());
        }
    }

    // Adicione este método ao seu AsaasService

    public function updateCustomer($customerId, $data, $apiKey, $mode)
    {
        $client = new Client();
        $url = rtrim($this->url, '/') . '/api/v3/customers/' . $customerId;

        \Log::info('Dados enviados para atualizar cliente: ' . json_encode($data));

        try {
            $response = $client->request('POST', $url, [
                'body' => json_encode($data),
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);

            return $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar cliente: ' . $e->getMessage() . ' - Dados: ' . json_encode($data));
            throw new \Exception('Erro ao atualizar cliente: ' . $e->getMessage());
        }
    }

    public function cobranca($data, $apiKey, $mode)
    {
      
        $client = new Client();
        $url = rtrim($this->url, '/') . '/api/v3/payments';
     
        \Log::info('Dados enviados para criar cobrança: ' . json_encode($data));

        try {
            $response = $client->request('POST', $url, [
                'body' => json_encode($data),
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);

            return $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            \Log::error('Erro ao criar cobrança: ' . $e->getMessage() . ' - Dados: ' . json_encode($data));
            throw new \Exception('Erro ao criar cobrança: ' . $e->getMessage());
        }
    }

    public function getClients($apiKey, $mode)
    {
        $client = new Client();
        $url = rtrim($this->url, '/') . '/api/v3/customers';

        try {
            $response = $client->request('GET', $url, [
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);

            return $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            throw new \Exception('Erro ao listar clientes: ' . $e->getMessage());
        }
    }

    public function tratarResposta($statusCode, $responseData)
    {
        if ($statusCode == 200 || $statusCode == 201) {
            return $responseData;
        }

        if ($statusCode == 400 || $statusCode == 401) {
            throw new \Exception($responseData['errorMessage'] ?? 'Erro na solicitação');
        }

        throw new \Exception('Resposta desconhecida do servidor');
    }


    public function getCustomerWallet($customerId, $apiKey, $mode)
    {
        $client = new Client();
        $url = rtrim($this->url, '/') . "/api/v3/customers/{$customerId}";

        try {
            $response = $client->request('GET', $url, [
                'headers' => array_merge($this->headers, ['access_token' => $apiKey]),
            ]);

            $customer = $this->tratarResposta($response->getStatusCode(), json_decode($response->getBody(), true));
            return ['walletId' => $customer['walletId'] ?? null];
        } catch (\Exception $e) {
            \Log::error('Erro ao obter wallet: ' . $e->getMessage());
            throw $e;
        }
    }
}