<?php

namespace App\Http\Controllers;

use App\Models\AsaasWebhookLog;
use App\Models\Empresa;
use App\Models\Professor;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoletoController extends Controller
{
    protected $client;
    protected $apiKey;
    protected $baseUri;

    public function __construct()
    {
        $this->apiKey = env('ASAAS_KEY');
        $this->baseUri = env('ASAAS_ENV') === 'production' ? env('ASAAS_URL') : 'https://api-sandbox.asaas.com';
        $this->client = new Client(['base_uri' => $this->baseUri]);
    }

    public function handleAsaasWebhook(Request $request)
    {


        Log::warning('Asaas webhook recebido', ['payload' => $request->all()]);
        $payload = $request->all();

        // Validar o token do Asaas
        $asaasToken = $request->header('asaas-access-token');

        if ($asaasToken !== '123456@') {
            Log::warning('Asaas Webhook: Token inválido', ['token' => $asaasToken]);
            AsaasWebhookLog::create([
                'event' => $payload['event'] ?? null,
                'payload' => $payload,
                'status' => 'invalid_token',
                'message' => 'Token inválido: ' . $asaasToken,
                'payment_id' => null,
            ]);
            return response()->json(['error' => 'Token inválido'], 401);
        }

        // Validar o payload
        if (!isset($payload['event'], $payload['payment'])) {
            Log::warning('Payload inválido', ['payload' => $payload]);
            return response()->json(['error' => 'Payload inválido'], 400);
        }

        // Processar apenas pagamentos recebidos de boleto
        if ($payload['event'] === 'PAYMENT_RECEIVED' && $payload['payment']['billingType'] === 'BOLETO') {
            $payment = $payload['payment'];
            $customerId = $payment['customer']; // Ex.: cus_000006746814
            $paymentId = $payment['id']; // Ex.: pay_ybk2slp8gh48i0iy

            // Buscar o professor pelo asaas_customer_id
            $professor = Professor::where('asaas_customer_id', $customerId)->first();

            if (!$professor) {
                Log::warning('Professor não encontrado para customer_id', ['customer_id' => $customerId]);
                return response()->json(['error' => 'Professor não encontrado'], 404);
            }

            // Buscar a empresa associada ao professor
            $empresa = Empresa::find($professor->empresa_id);

            if (!$empresa) {
                Log::warning('Empresa não encontrada para professor', ['professor_id' => $professor->id, 'empresa_id' => $professor->empresa_id]);
                return response()->json(['error' => 'Empresa não encontrada'], 404);
            }




            // Atualizar o status da empresa e a data de vencimento
            $novaVencimento = Carbon::now()->addDays(30);

            $empresa->update([
                'status' => 'ativo',
                'data_vencimento' => $novaVencimento->format('Y-m-d'),
            ]);


            AsaasWebhookLog::create([
                'event' => $payload['event'] ?? null,
                'payload' => $payload,
                'status' => 'BOLETO PAGO',
                'message' => 'Pagamento processado com sucesso BOLETO',
                'payment_id' => $payment['id'],
                'empresa_id' => $professor->empresa_id
            ]);

            Log::info('Empresa ativada via boleto', [
                'empresa_id' => $empresa->id,
                'customer_id' => $customerId,
                'nova_data_vencimento' => $novaVencimento->format('Y-m-d'),
            ]);

            return response()->json(['received' => true], 200);
        }

        Log::info('Evento ignorado', ['event' => $payload['event'], 'billingType' => $payload['payment']['billingType'] ?? 'N/A']);
        return response()->json(['received' => true], 200);
    }


    public function boleto(Request $request)
    {

        $empresaId = Auth::user()->empresa->id;


        $boleto = [
            'valor' => 100.00, // Substitua pela lógica real
            'data_vencimento' => now()->addDays(7),
            'link' => 'https://exemplo.com/boleto.pdf', // Substitua pelo link real
        ];

        return view('admin.empresas.boleto', ['boleto' => $boleto, 'pageTitle' => 'Pagamento de Boleto']);
    }


    public function downloadBoleto($boletoId)
    {
        try {



            $url = $this->baseUri . "/v3/payments/{$boletoId}/pdf";

            $headers = [
                'accept' => 'application/json',
                'access_token' => env('ASAAS_KEY'),
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
        $clientes = Empresa::with('user')->where('status', 'inativo')->get();

        // foreach ($clientes as $cliente) {
        //     echo $cliente->user->email . PHP_EOL;
        // }

        // URL correta
        $url = $this->baseUri . "/v3/payments";


        $headers = [
            'accept' => 'application/json',
            'access_token' => env('ASAAS_KEY'),
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
                'access_token' => env('ASAAS_KEY'),
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

    public function sendBoletoToClient(Request $request)
    {
        // Dados do cliente (recebidos via request ou já do banco)
        $customerId = $request->customer_id;
        $dueDate = $request->dueDate ?? now()->addDays(5)->format('Y-m-d');
        $value = $request->value ?? 129.90;

        // Criar o boleto
        $url = $this->baseUri . '/v3/payments';
        $headers = [
            'accept' => 'application/json',
            'access_token' => env('ASAAS_KEY'),
            'content-type' => 'application/json',
        ];
        $body = [
            'billingType' => 'BOLETO',
            'customer' => $customerId,
            'value' => $value,
            'dueDate' => $dueDate,
        ];

        $response = Http::withHeaders($headers)->post($url, $body);

        if ($response->successful()) {
            $boletoData = $response->json();

            $link = $boletoData['bankSlipUrl'];
            $nomeCliente = $boletoData['customer'];
            $vencimento = $boletoData['dueDate'];

            // Aqui você pode enviar por e-mail, WhatsApp, etc. Vamos só simular por enquanto:
            return response()->json([
                'status' => 'success',
                'mensagem' => "Boleto enviado com sucesso!",
                'link_boleto' => $link,
                'vencimento' => $vencimento,
                'valor' => $value,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'mensagem' => 'Erro ao gerar boleto',
                'resposta' => $response->json(),
            ], $response->status());
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
