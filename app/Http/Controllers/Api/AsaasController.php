<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use Illuminate\Http\Request;
use App\Services\AsaasService;

class AsaasController extends Controller
{
    protected $asaasService, $baseUri;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
        $this->baseUri = env('ASAAS_ENV') == 'production' ? env('ASAAS_URL') : env('ASAAS_SANDBOX_URL');
    }

    public function createClient(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'cpfCnpj' => 'required|string|max:14',
            'phone' => 'nullable|string',
            'mobilePhone' => 'required|string',
            'address' => 'required|string',
            'addressNumber' => 'required|string',
            'complement' => 'nullable|string',
            'province' => 'required|string',
            'postalCode' => 'required|string',
            'externalReference' => 'nullable|string',
            'notificationDisabled' => 'boolean',
            'additionalEmails' => 'nullable|string',
            'municipalInscription' => 'nullable|string',
            'stateInscription' => 'nullable|string',
            'observations' => 'nullable|string',
            'groupName' => 'nullable|string',
        ]);

        $response = $this->asaasService->criarClienteAsaas($validated);

        return response()->json($response);
    }
}
