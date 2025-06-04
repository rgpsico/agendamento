<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BoletoController extends Controller
{
    protected $client;
    protected $apiKey;
    protected $baseUri;

    public function __construct()
    {
        $this->apiKey = env('ASAAS_API_KEY');
        $this->baseUri = env('ASAAS_ENV') === 'production' ? env('ASAAS_URL') : env('ASAAS_SANDBOX_URL');
        $this->client = new Client(['base_uri' => $this->baseUri]);
    }

    public function handleWebhook(Request $request)
    {
        try {
            $data = $request->all();
            if ($data['event'] === 'PAYMENT_CONFIRMED') {
                Log::info('Boleto pago: ' . $data['payment']['id']);
                // Atualize o status no banco de dados, se necessário
            }
            return response()->json(['status' => 'received']);
        } catch (\Exception $e) {
            Log::error('Erro no webhook: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function downloadBoleto($boletoId)
    {
        try {
            $url = $this->baseUri . "/v3/payments/{$boletoId}/pdf";

            $headers = [
                'accept' => 'application/json',
                'access_token' => env('ASAAS_API_KEY'),
                'content-type' => 'application/json',
            ];

            $response = Http::withHeaders($headers)->get($url);

            if ($response->successful()) {
                return response($response->body(), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="boleto.pdf"',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao baixar o boleto.'
            ], $response->status());
        } catch (\Exception $e) {
            // \Log::error('Erro ao baixar boleto: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Erro interno ao baixar o boleto.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


    public function gerarBoleto(Request $request)
    {
        $url = $this->baseUri . '/v3/payments';

        $headers = [
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_KEY'),
            'content-type' => 'application/json',
        ];

        $body = [
            'billingType' => 'BOLETO',
            'customer' => $request->customer_id,
            'value' => $request->value,
            'dueDate' => $request->dueDate,
        ];


        $response = Http::withHeaders($headers)->post($url, $body);

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'data' => $response->json()
            ]);
        }



        return response()->json([
            'status' => 'error',
            'message' => $response->body()
        ], $response->status());
    }

    // Método auxiliar para verificar se o customer existe
    public function customerExistsInAsaas($customerId)
    {
        try {
            $url = $this->baseUri . "/v3/customers/{$customerId}";

            $headers = [
                'accept' => 'application/json',
                'access_token' => env('ASAAS_API_KEY'),
                'content-type' => 'application/json',
            ];

            $response = Http::withHeaders($headers)->get($url);


            return $response->successful();
        } catch (\Exception $e) {
            // Loga o erro e retorna false em caso de exceção inesperada
            //  \Log::error('Erro ao verificar cliente na Asaas: ' . $e->getMessage());
            return false;
        }
    }



    // Método público para verificar customer (mantido para compatibilidade)
    public function checkCustomer($customerId)
    {
        try {
            $response = $this->client->get("/api/v3/customers/{$customerId}", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'access_token' => $this->apiKey,
                ],
            ]);

            return response()->json([
                'exists' => true,
                'data' => json_decode($response->getBody()->getContents(), true)
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->getCode() === 404) {
                return response()->json([
                    'exists' => false,
                    'message' => 'Cliente não encontrado'
                ], 404);
            }

            Log::error('Erro ao verificar cliente: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'response' => $e->getResponse()->getBody()->getContents(),
            ], $e->getCode());
        } catch (\Exception $e) {
            Log::error('Erro geral ao verificar cliente: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Método para criar um cliente se não existir
    public function createCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'cpfCnpj' => 'required|string',
            'phone' => 'nullable|string',
            'mobilePhone' => 'nullable|string',
            'address' => 'nullable|string',
            'addressNumber' => 'nullable|string',
            'complement' => 'nullable|string',
            'province' => 'nullable|string',
            'postalCode' => 'nullable|string',
        ]);

        try {
            $payload = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'cpfCnpj' => $request->input('cpfCnpj'),
            ];

            // Campos opcionais
            $optionalFields = ['phone', 'mobilePhone', 'address', 'addressNumber', 'complement', 'province', 'postalCode'];
            foreach ($optionalFields as $field) {
                if ($request->has($field) && $request->input($field)) {
                    $payload[$field] = $request->input($field);
                }
            }

            $response = $this->client->post('/api/v3/customers', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'access_token' => $this->apiKey,
                ],
                'json' => $payload,
            ]);

            $customerData = json_decode($response->getBody()->getContents(), true);

            return response()->json([
                'success' => true,
                'customer_id' => $customerData['id'],
                'data' => $customerData
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            Log::error('Erro ao criar cliente: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao criar cliente',
                'message' => $e->getMessage(),
                'api_response' => json_decode($responseBody, true)
            ], $e->getCode());
        }
    }
}
