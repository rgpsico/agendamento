<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PagamentoGateway;
use App\Models\PaymentConfiguration;
use App\Models\Professor;
use App\Models\Empresa;
use Illuminate\Http\JsonResponse;

class CheckoutControllerApi extends Controller
{
    public function checkoutAuth(int $userId): JsonResponse
    {
        $empresa = Empresa::with('paymentGateways')->where('user_id', $userId)->first();

        if (!$empresa) {
            return response()->json(['message' => 'Empresa nao encontrada.'], 404);
        }

        $professor = Professor::with('usuario')->where('usuario_id', $userId)->first();

        try {
            $paymentConfig = PaymentConfiguration::getForEmpresa($userId);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }

        $formasPagamento = $paymentConfig->getEnabledPaymentMethods();

        $tokenGateway = PagamentoGateway::where('empresa_id', $empresa->id)
            ->where('status', 1)
            ->value('api_key');

        return response()->json([
            'pageTitle' => 'Aluno',
            'token_gateway' => $tokenGateway,
            'route' => 'alunos',
            'model' => $empresa,
            'professor' => $professor,
            'user_id' => $userId,
            'view' => 'public.home',
            'formasPagamento' => $formasPagamento,
            'paymentConfig' => $paymentConfig,
        ]);
    }
}
