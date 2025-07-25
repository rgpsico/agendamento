<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use Illuminate\Http\Request;
use App\Http\Requests\CreateClientRequest;
use App\Models\Usuario;
use App\Http\Requests\CreateSubaccountRequest;
use App\Http\Requests\PagamentoComCartaoRequest;
use App\Services\AsaasService;
use Illuminate\Support\Facades\Http;
use App\Services\AgendamentoService;


class AsaasController extends Controller
{
    protected $asaasService, $baseUri, $token, $wallet_id, $agendamentoService;

    public function __construct(AsaasService $asaasService,   AgendamentoService $agendamentoService)
    {
        $this->asaasService = $asaasService;
        $this->agendamentoService =  $agendamentoService;
        $this->baseUri = env('ASAAS_ENV') == 'production' ? env('ASAAS_URL') : env('ASAAS_SANDBOX_URL');
        $this->token = env('ASAAS_ENV') == 'production' ? env('ASAAS_KEY') : env('ASAAS_KEY_SANDBOX');
        $this->wallet_id = env('ASAAS_ENV') === 'production' ? env('ASAAS_WALLET_ID') : env('ASAAS_WALLET_ID_SANDBOX');
    }

    public function createClient(CreateClientRequest $request)
    {
        $validated = $request->validated();

        $response = $this->asaasService->criarClienteAsaas($validated);

        return response()->json($response);
    }


    public function criarCustomerAutomatico(Request $request)
    {
        $user_id = $request->user_id;

        // Busca o usuário com seus relacionamentos necessários
        $user = Usuario::with('empresa.endereco', 'professor')->find($user_id);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado.'], 404);
        }

        try {

            // Cria cliente no Asaas
            $res = $this->criarClientePeloUsuario($user);

            // Atualiza o professor com o ID do customer Asaas
            if ($user->professor) {
                $user->professor->update([
                    'asaas_customer_id' => $res['id'],
                ]);
            }

            return response()->json([
                'message' => 'Customer criado!',
                'customer_id' => $res['id'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function criarClientePeloUsuario($user): array
    {

        $empresa  = $user->empresa;
        $endereco = $empresa->endereco;

        if (!$empresa) {
            throw new \Exception('Empresa  não configurada para este usuário.');
        }


        if (!$endereco) {
            throw new \Exception('endereço não configurados para este usuário.');
        }
        // Prepara dados para API do Asaas conforme documentação
        $dados = [
            'name'          => $empresa->nome,
            'email'         => $user->email,
            'cpfCnpj'       => preg_replace('/\D/', '', $empresa->cnpj), // só números
            'phone'         => preg_replace('/\D/', '', $empresa->telefone),
            'postalCode'    => preg_replace('/\D/', '', $endereco->cep),
            'address'       => $endereco->logradouro,
            'addressNumber' => $endereco->numero,
            'province'      => $endereco->bairro,
            // Se quiser, pode completar com 'city', 'state', etc, conforme docs
        ];

        // Chama método que faz requisição HTTP para o Asaas
        $resultado = $this->criarClienteAsaas($dados);

        return $resultado;
    }

    public function criarClienteAsaas(array $dados): array
    {

        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'access_token'  => $this->token,
        ])->post($this->baseUri . '/customers', $dados);



        // Se falhou, lança erro detalhado
        if ($response->failed()) {
            $msg = $response->json('errors.0.description') ??
                $response->json('message') ??
                $response->body() ??
                'Erro desconhecido na API do Asaas.';
            throw new \Exception('Erro ao criar cliente no Asaas: ' . $msg);
        }

        // Retorna sempre array, nunca null
        return $response->json() ?? [];
    }


    public function criarChavePix(Request $request)
    {
        $user_id = $request->user_id;
        $empresa_id = $request->empresa_id;
        // Buscar o professor baseado no usuario_id
        $professor = Professor::where('usuario_id', $user_id)->first();

        if (!$professor) {
            return response()->json([
                'message' => 'Professor não encontrado para o usuário informado.',
            ], 404);
            throw new \Exception('Professor não encontrado para o usuário informado.');
        }

        if (!empty($professor->asaas_pix_key)) {
            return response()->json([
                'message' => 'O professor já possui uma chave Pix cadastrada.',
                'pixKey' => $professor->asaas_pix_key
            ], 200);
        }

        // Criar a chave Pix no Asaas
        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'access_token'  => $this->token,
        ])->post($this->baseUri . '/pix/addressKeys', [
            'type' => 'EVP',
            'walletId' => $professor->asaas_wallet_id
        ]);

        if ($response->failed()) {
            $msg = $response->json('errors.0.description')
                ?? $response->json('message')
                ?? $response->body()
                ?? 'Erro desconhecido ao gerar chave Pix.';
            throw new \Exception('Erro ao criar chave Pix no Asaas: ' . $msg);
        }

        // Armazenar a chave Pix no banco
        $pixKey = $response->json('key');
        $professor->asaas_pix_key = $pixKey;
        $professor->empresa_id = $empresa_id;
        $professor->save();

        return response()->json([
            'message' => 'Chave criada com sucesso',
            'pixKey' => $pixKey,
        ]);
    }



    public function pagarComCartao(PagamentoComCartaoRequest $request)
    {

        // 1. Obter dados do professor com walletId

        $professor = Professor::where('id', $request->professor_id);


        if (!$professor->asaas_wallet_id) {
            return response()->json(['erro' => 'Professor sem carteira digital configurada.'], 400);
        }

        // 2. Criar agendamento

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

        $agendamento = $this->agendamentoService->criarAgendamento($request->all());
        // 6. Resposta de sucesso
        $redirectUrl = route('home.checkoutsucesso', ['id' => $request->professor_id]);



        return response()->json([
            'success' => true,
            'redirect_url' => $redirectUrl,
            'agendamento_id' => $agendamento->id,
            'payment_method' => 'cartao'
        ]);
    }


    public function createSubaccount(CreateSubaccountRequest $request)
    {
        $validated = $request->validated();

        //** */ $response = $this->asaasService->criarSubconta($validated);

        // return response()->json($response);
    }
}
