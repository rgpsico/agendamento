<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use Illuminate\Http\Request;
use App\Http\Requests\CreateClientRequest;
use App\Services\AsaasService;

class AsaasController extends Controller
{
    protected $asaasService, $baseUri;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
        $this->baseUri = env('ASAAS_ENV') == 'production' ? env('ASAAS_URL') : env('ASAAS_SANDBOX_URL');
    }

    public function createClient(CreateClientRequest $request)
    {
        $validated = $request->validated();

        $response = $this->asaasService->criarClienteAsaas($validated);

        return response()->json($response);
    }
}
