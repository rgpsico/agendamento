<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Http\Request;
use App\Services\AsaasService;
class AsaasWalletController extends Controller
{
    protected $asaasService;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
    }

    /**
     * Obtém o walletId do professor ou cria uma subconta no Asaas.
     *
     * @param int $professorId ID do professor no sistema
     * @param string $apiKey Chave da API do Asaas
     * @param string $mode Modo da API (sandbox ou production)
     * @return array Retorna um array com 'wallet_id' ou 'error'
     */
    public function getProfessorWallet($professorId)
    {
        $apiKey = env('ASAAS_SANDBOX_URL');
         $mode = 'sandbox';
        $professor = Professor::with('usuario')->find($professorId);

        if (!$professor || !$professor->usuario) {
            return [
                'error' => 'Professor não encontrado ou sem dados de usuário.',
                'status' => 404
            ];
        }

        if ($professor->asaas_wallet_id) {
            return [
                'wallet_id' => $professor->asaas_wallet_id,
                'status' => 200
            ];
        }

        // Criar subconta para o professor
        $walletData = [
            'name' => $professor->usuario->nome,
            'email' => $professor->usuario->email,
            'cpfCnpj' => $professor->usuario->cpf ?? '12345678909',
            // Outros campos necessários, conforme a documentação do Asaas
            'companyType' => 'INDIVIDUAL', // Exemplo: ajustar conforme o tipo de pessoa
        ];

        $subconta = $this->createAsaasSubAccount($walletData, $apiKey, $mode);

        if (isset($subconta['error'])) {
            return [
                'error' => 'Erro ao criar subconta no Asaas: ' . ($subconta['errorMessage'] ?? 'Desconhecido'),
                'status' => 500
            ];
        }

        // Atualizar o professor com o walletId
        $professor->asaas_wallet_id = $subconta['walletId'];
        $professor->save();

        return [
            'wallet_id' => $subconta['walletId'],
            'status' => 201
        ];
    }

    /**
     * Cria uma subconta no Asaas para o professor.
     *
     * @param array $walletData Dados da subconta (nome, e-mail, CPF/CNPJ, etc.)
     * @param string $apiKey Chave da API do Asaas
     * @param string $mode Modo da API (sandbox ou production)
     * @return array Retorna o 'walletId' ou 'error'
     */
    protected function createAsaasSubAccount($walletData, $apiKey, $mode)
    {
        // Endpoint para criar subconta: POST /v3/accounts
        $subconta = $this->asaasService->createSubAccount($walletData, $apiKey, $mode);

        if (isset($subconta['error']) || !isset($subconta['walletId'])) {
            return [
                'error' => 'Erro ao criar subconta no Asaas: ' . ($subconta['errorMessage'] ?? 'Desconhecido'),
                'status' => 500
            ];
        }

        return [
            'walletId' => $subconta['walletId'],
            'status' => 201
        ];
    }
}