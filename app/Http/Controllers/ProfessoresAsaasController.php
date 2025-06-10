<?php

// app/Http/Controllers/ProfessoresAsaasController.php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubaccountRequest;
use App\Services\AsaasService;
use App\Models\Professor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;


class ProfessoresAsaasController extends Controller
{
    protected $asaasService;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
    }




    public function createSubaccount(Request $request)
    {
        // Buscar o professor do usuário logado
        $professor = Auth::user()->professor;

        if (!$professor) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não é um professor'
            ], 403);
        }

        // Verificar se já possui wallet
        if ($professor->asaas_wallet_id) {
            return response()->json([
                'success' => false,
                'message' => 'Professor já possui subconta no Asaas'
            ], 409);
        }

        // Validação dos dados
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'cpfCnpj' => 'required|string|size:11',
            'phone' => 'required|string',
            'mobilePhone' => 'required|string',
            'birthDate' => 'required|date',
            'incomeValue' => 'required|numeric|min:0',
            'address' => 'required|string',
            'addressNumber' => 'required|string',
            'complement' => 'nullable|string',
            'province' => 'required|string',
            'postalCode' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // Dados para criar subconta no Asaas
            $subaccountData = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'cpfCnpj' => $validatedData['cpfCnpj'],
                'companyType' => 'cpf', // Como no seu exemplo
                'phone' => $validatedData['phone'],
                'mobilePhone' => $validatedData['mobilePhone'],
                'birthDate' => $validatedData['birthDate'],
                'incomeValue' => $validatedData['incomeValue'],
                'address' => $validatedData['address'],
                'addressNumber' => $validatedData['addressNumber'],
                'complement' => $validatedData['complement'] ?? '',
                'province' => $validatedData['province'],
                'postalCode' => $validatedData['postalCode'],
                'personType' => 'FISICA', // Assumindo pessoa física
                'notificationDisabled' => false,
                'walletId' => env('ASAAS_WALLET_ID')
            ];


            // Fazer requisição para API do Asaas
            $response = Http::withHeaders([
                'access_token' => env('ASAAS_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://sandbox.asaas.com/api/v3/accounts', $subaccountData);

            // Verificar se a requisição foi bem-sucedida
            if (!$response->successful()) {
                throw new \Exception('Erro na API do Asaas: ' . $response->body());
            }

            $asaasResponse = $response->json();

            // Verificar se a resposta contém os dados necessários
            if (!isset($asaasResponse['id'])) {
                throw new \Exception('Resposta inválida da API do Asaas');
            }

            $clienteResponse = $this->asaasService->criarClienteAsaas([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'cpfCnpj' => $validatedData['cpfCnpj'],
                'phone' => $validatedData['phone'],
                'postalCode' => $validatedData['postalCode'],
                'address' => $validatedData['address'],
                'addressNumber' => $validatedData['addressNumber'],
                'province' => $validatedData['province'],
            ]);

            if (!isset($clienteResponse['id'])) {
                throw new \Exception('Erro ao criar cliente no Asaas');
            }

            // Atualizar o professor com wallet_id e customer_id
            $professor->update([
                'asaas_wallet_id' => $asaasResponse['walletId'] ?? $asaasResponse['id'],
                'asaas_customer_id' => $clienteResponse['id'],
            ]);

            DB::commit();

            // Log de sucesso
            Log::info('Subconta criada com sucesso', [
                'professor_id' => $professor->id,
                'asaas_wallet_id' => $asaasResponse['walletId'] ?? $asaasResponse['id'],
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subconta criada com sucesso!',
                'data' => [
                    'professor' => $professor->fresh(),
                    'asaas_account' => $asaasResponse
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log do erro
            Log::error('Erro ao criar subconta', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'professor_id' => $professor->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => config('app.debug') ? $e->getMessage() : 'Erro ao processar solicitação'
            ], 500);
        }
    }

    public function getSubaccountStatus($id)
    {
        try {
            $professor = Professor::findOrFail($id);

            if (!$professor->asaas_wallet_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Professor não possui subconta no Asaas'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'professor' => $professor,
                    'asaas_wallet_id' => $professor->asaas_wallet_id
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao consultar status da subconta', [
                'error' => $e->getMessage(),
                'professor_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao consultar status'
            ], 500);
        }
    }

    public function listSubaccounts()
    {
        try {
            $professors = Professor::with('usuario')
                ->whereNotNull('asaas_wallet_id')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $professors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar subcontas'
            ], 500);
        }
    }
}
