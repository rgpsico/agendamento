<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use Illuminate\Http\Request;
use App\Http\Requests\CreateClientRequest;
use App\Models\Usuario;
use App\Http\Requests\CreateSubaccountRequest;
use App\Services\AsaasService;
use Illuminate\Support\Facades\Http;

class AsaasController extends Controller
{
    protected $asaasService, $baseUri, $token;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
        $this->baseUri = env('ASAAS_ENV') == 'production' ? env('ASAAS_URL') : env('ASAAS_SANDBOX_URL');
        $this->token = env('ASAAS_ENV') == 'production' ? env('ASAAS_KEY') : env('ASAAS_KEY_SANDBOX');
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

        if (! $empresa) {
            throw new \Exception('Empresa  não configurada para este usuário.');
        }


        if (! $endereco) {
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
        ])->post($this->baseUri . '/api/v3/customers', $dados);



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

    public function createSubaccount(CreateSubaccountRequest $request)
    {
        $validated = $request->validated();

        $response = $this->asaasService->criarSubcontaAsaas($validated);

        return response()->json($response);
    }
}
