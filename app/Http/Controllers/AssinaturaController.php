<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\LeadQualificacao;
use App\Services\AsaasSaasService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/** "Assinar agora": leva a empresa pro link de pagamento (PIX, boleto ou cartão) do Asaas. */
class AssinaturaController extends Controller
{
    public function assinar(AsaasSaasService $asaas)
    {
        $user = Auth::user();
        $q = LeadQualificacao::where('usuario_id', $user->id)->whereIn('status', ['trial', 'cliente'])->latest()->first();

        if (! $q) {
            return view('validador.assinar', ['estado' => 'sem_registro']);
        }
        if ($q->status === 'cliente') {
            return view('validador.assinar', ['estado' => 'ja_cliente']);
        }
        if (! $asaas->disponivel()) {
            $this->avisar("Alguém quis assinar mas o Asaas não está configurado: {$q->negocio_nome}");

            return view('validador.assinar', ['estado' => 'indisponivel', 'q' => $q]);
        }

        try {
            $empresa = Empresa::where('user_id', $user->id)->first();

            return redirect()->away($asaas->criarAssinatura($q, $user, $empresa));
        } catch (\Throwable $e) {
            Log::error('Assinatura: falha no Asaas', ['q' => $q->id, 'erro' => $e->getMessage()]);
            $this->avisar("Falha ao gerar a assinatura de {$q->negocio_nome}: " . $e->getMessage());

            return view('validador.assinar', ['estado' => 'erro', 'q' => $q]);
        }
    }

    private function avisar(string $mensagem): void
    {
        if ($para = config('funil.notificar_email')) {
            try {
                Mail::raw($mensagem, fn ($m) => $m->to($para)->subject('[Funil] ' . $mensagem));
            } catch (\Throwable $e) {
                Log::warning('Assinatura: falha ao avisar', ['erro' => $e->getMessage()]);
            }
        }
    }
}
