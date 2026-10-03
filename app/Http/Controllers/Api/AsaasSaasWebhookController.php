<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AsaasWebhookLog;
use App\Models\LeadQualificacao;
use App\Services\FunilService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** Recebe os avisos de pagamento do Asaas da assinatura do SaaS. */
class AsaasSaasWebhookController extends Controller
{
    public function __invoke(Request $request, FunilService $funil): JsonResponse
    {
        $token = config('funil.asaas.webhook_token');

        if (! $token) {
            return response()->json(['message' => 'Webhook não configurado.'], 503);
        }
        if (! hash_equals((string) $token, (string) $request->header('asaas-access-token'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $evento = (string) $request->input('event');
        $pagamento = (array) $request->input('payment', []);

        if (! in_array($evento, ['PAYMENT_CONFIRMED', 'PAYMENT_RECEIVED'], true)) {
            return response()->json(['received' => true]);
        }

        $q = $this->localizar($pagamento);
        if (! $q) {
            return response()->json(['received' => true, 'ignorado' => 'assinatura desconhecida']);
        }

        // O Asaas pode avisar mais de uma vez (CONFIRMED e RECEIVED): só estende uma vez por cobrança.
        $jaProcessado = AsaasWebhookLog::where('payment_id', $pagamento['id'] ?? '-')->where('status', 'SAAS_OK')->exists();
        if (! $jaProcessado) {
            $funil->aoPagar($q);
            AsaasWebhookLog::create([
                'event'      => $evento,
                'payload'    => $request->all(),
                'status'     => 'SAAS_OK',
                'message'    => 'Assinatura do SaaS paga',
                'payment_id' => $pagamento['id'] ?? null,
                'empresa_id' => $q->empresa_id,
            ]);
        }

        return response()->json(['received' => true]);
    }

    private function localizar(array $pagamento): ?LeadQualificacao
    {
        if (! empty($pagamento['subscription'])) {
            $q = LeadQualificacao::where('asaas_subscription_id', $pagamento['subscription'])->first();
            if ($q) {
                return $q;
            }
        }

        $ref = (string) ($pagamento['externalReference'] ?? '');

        return str_starts_with($ref, 'qualificacao:') ? LeadQualificacao::find((int) substr($ref, 13)) : null;
    }
}
