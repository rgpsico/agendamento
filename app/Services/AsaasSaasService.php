<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\LeadQualificacao;
use App\Models\Usuario;
use Illuminate\Support\Facades\Http;

/** Cobra as empresas que assinam o sistema, na conta Asaas do próprio SaaS. */
class AsaasSaasService
{
    public function disponivel(): bool
    {
        return filled(config('funil.asaas.key'));
    }

    /**
     * Cria o cliente e a assinatura mensal (primeira cobrança no fim do teste) e devolve
     * o link da cobrança, onde a pessoa escolhe PIX, boleto ou cartão.
     */
    public function criarAssinatura(LeadQualificacao $q, Usuario $usuario, ?Empresa $empresa): string
    {
        if (! $q->asaas_customer_id) {
            $cliente = $this->http()->post('/customers', array_filter([
                'name'              => $q->negocio_nome ?: $usuario->nome,
                'email'             => $usuario->email,
                'mobilePhone'       => preg_replace('/\D/', '', (string) $q->whatsapp) ?: null,
                'cpfCnpj'           => $this->documento($empresa),
                'externalReference' => 'usuario:' . $usuario->id,
            ]))->throw()->json();

            $q->forceFill(['asaas_customer_id' => $cliente['id']])->save();
        }

        if (! $q->asaas_subscription_id) {
            $primeiraCobranca = $q->trial_fim && $q->trial_fim->isFuture() ? $q->trial_fim : now();

            $assinatura = $this->http()->post('/subscriptions', [
                'customer'          => $q->asaas_customer_id,
                'billingType'       => 'UNDEFINED',
                'value'             => (float) config('funil.plano.valor'),
                'nextDueDate'       => $primeiraCobranca->format('Y-m-d'),
                'cycle'             => config('funil.plano.ciclo', 'MONTHLY'),
                'description'       => 'Plano ' . config('funil.plano.nome') . ' — ' . ($q->negocio_nome ?: $usuario->nome),
                'externalReference' => 'qualificacao:' . $q->id,
            ])->throw()->json();

            $q->forceFill(['asaas_subscription_id' => $assinatura['id']])->save();
        }

        $cobrancas = $this->http()->get("/subscriptions/{$q->asaas_subscription_id}/payments")->throw()->json();
        $link = $cobrancas['data'][0]['invoiceUrl'] ?? null;

        if (! $link) {
            throw new \RuntimeException('O Asaas não devolveu o link da cobrança.');
        }

        return $link;
    }

    private function http()
    {
        return Http::baseUrl(rtrim(config('funil.asaas.url'), '/'))
            ->withHeaders(['access_token' => config('funil.asaas.key')])
            ->acceptJson()
            ->timeout(20);
    }

    private function documento(?Empresa $empresa): ?string
    {
        $digitos = preg_replace('/\D/', '', (string) ($empresa->cnpj ?? ''));

        return in_array(strlen($digitos), [11, 14], true) ? $digitos : null;
    }
}
