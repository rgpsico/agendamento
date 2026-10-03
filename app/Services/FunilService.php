<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\Lead;
use App\Models\LeadQualificacao;
use App\Models\Modalidade;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Funil automático: respondeu o formulário → abre teste → vira cliente.
 * Mantém o lead do CRM sincronizado e avisa o dono nos pontos que pedem ação humana.
 */
class FunilService
{
    private const ORDEM_PIPELINE = ['novo_lead' => 1, 'em_contato' => 2, 'aula_experimental' => 3, 'matriculado' => 4, 'recorrente' => 5];

    /** Chamado logo depois que o lead termina as perguntas. */
    public function aoResponder(LeadQualificacao $q): void
    {
        $this->sincronizarLead($q, 'novo_lead');

        $this->avisar(
            ($q->precisaOnboarding() ? '[Onboarding] ' : '') . "Novo lead respondeu: {$q->nome}",
            $this->resumo($q)
                . "\n\n" . ($q->precisaOnboarding()
                    ? 'Perfil que costuma travar sozinho: vale uma ligação para ajudar no onboarding.'
                    : 'Perfil independente: tende a começar sozinho.')
                . "\n\nVer no painel: " . route('super.admin.validador.show', $q)
        );
    }

    /** Cria usuário + professor, abre o teste e atualiza CRM/avisos. */
    public function iniciarTeste(LeadQualificacao $q, string $negocio, string $email, string $senha): Usuario
    {
        $user = DB::transaction(function () use ($q, $negocio, $email, $senha) {
            $user = Usuario::create([
                'nome'         => $q->nome ?: $negocio,
                'email'        => $email,
                'password'     => Hash::make($senha),
                'tipo_usuario' => 'Professor',
            ]);

            Professor::create([
                'usuario_id' => $user->id,
                'sobre'      => $q->sobre_negocio ?: 'Sobre',
                'avatar'     => 'avatar/default.png',
            ]);

            $q->forceFill([
                'status'       => 'trial',
                'usuario_id'   => $user->id,
                'negocio_nome' => $negocio,
                'trial_inicio' => now(),
                'trial_fim'    => now()->addDays((int) config('funil.trial_dias', 14)),
            ])->save();

            return $user;
        });

        $this->sincronizarLead($q->fresh(), 'aula_experimental', $email, $user->id);

        $this->avisar(
            ($q->precisaOnboarding() ? '[Onboarding] ' : '') . "Teste aberto: {$negocio}",
            "{$q->nome} abriu o teste grátis de " . config('funil.trial_dias', 14) . " dias.\nE-mail: {$email}\nWhatsApp: " . ($q->whatsapp ?: '-')
                . "\n\n" . $this->resumo($q)
                . "\n\nVer no painel: " . route('super.admin.validador.show', $q)
        );

        return $user;
    }

    /** Pagamento confirmado: estende a vigência e marca como cliente. */
    public function aoPagar(LeadQualificacao $q): void
    {
        $empresa = $q->usuario_id ? Empresa::where('user_id', $q->usuario_id)->first() : null;

        if ($empresa) {
            $base = $empresa->data_vencimento && $empresa->data_vencimento->isFuture() ? $empresa->data_vencimento : now();
            $empresa->update(['status' => 'ativo', 'data_vencimento' => $base->copy()->addDays(30)->toDateString()]);
        }

        $q->forceFill(['status' => 'cliente', 'cliente_desde' => $q->cliente_desde ?: now()])->save();
        $this->sincronizarLead($q->fresh(), 'matriculado');

        $this->avisar("💰 Nova assinatura paga: " . ($q->negocio_nome ?: $q->nome),
            ($q->negocio_nome ?: $q->nome) . " confirmou o pagamento do plano " . config('funil.plano.nome') . '.');
    }

    /** Quando a empresa é criada no onboarding, herda a data do teste. */
    public function vincularEmpresa(Empresa $empresa): void
    {
        $q = LeadQualificacao::where('usuario_id', $empresa->user_id)->whereIn('status', ['trial', 'cliente'])->latest()->first();
        if (! $q) {
            return;
        }

        $q->forceFill(['empresa_id' => $empresa->id])->save();

        if ($q->status === 'trial' && $q->trial_fim) {
            $empresa->update(['status' => 'ativo', 'data_vencimento' => $q->trial_fim->toDateString()]);
        }
    }

    /** Modalidade preferida para pré-preencher o cadastro da empresa. */
    public function modalidadeSugerida(LeadQualificacao $q): ?int
    {
        $nomePreferido = config('funil.modalidade_por_nicho.' . $q->nicho);
        $doNicho = Modalidade::doNichoAtual()->get();

        return ($nomePreferido ? $doNicho->firstWhere('nome', $nomePreferido)?->id : null)
            ?? $doNicho->first()?->id;
    }

    private function sincronizarLead(LeadQualificacao $q, string $pipeline, ?string $email = null, ?int $usuarioId = null): void
    {
        $lead = $q->lead;

        if (! $lead) {
            $nicho = app()->has('currentNicho') ? app('currentNicho')->nicho : $q->nicho;
            $lead = Lead::create([
                'tenant_id'       => null,
                'nome'            => $q->nome ?: 'Lead do formulário',
                'email'           => $email,
                'telefone'        => $q->whatsapp,
                'origem'          => in_array($nicho, ['surf', 'pilates', 'turismo'], true) ? $nicho : 'landing',
                'campanha_origem' => $q->utm_campaign ?: 'validador',
                'status'          => 'novo',
                'pipeline_status' => $pipeline,
                'observacoes'     => $this->resumo($q),
            ]);
            $q->forceFill(['lead_id' => $lead->id])->save();
        }

        $atual = self::ORDEM_PIPELINE[$lead->pipeline_status] ?? 0;
        $novo = self::ORDEM_PIPELINE[$pipeline] ?? 0;

        $dados = [];
        if ($novo > $atual) {
            $dados['pipeline_status'] = $pipeline;
        }
        if ($email && ! $lead->email) {
            $dados['email'] = $email;
        }
        if ($usuarioId) {
            $dados['trial_usuario_id'] = $usuarioId;
        }
        if ($dados) {
            $lead->update($dados);
        }
    }

    private function resumo(LeadQualificacao $q): string
    {
        return sprintf(
            "Nome: %s\nDe onde: %s\nNicho: %s\nGoogle Meu Negócio: %s\nCampanha: %s\nNível digital: %s\nSobre o negócio: %s\nOrigem: %s",
            $q->nome, $q->cidade, $q->nichoRotulo(),
            LeadQualificacao::GOOGLE[$q->tem_google_meu_negocio] ?? '-',
            LeadQualificacao::CAMPANHA[$q->ja_fez_campanha] ?? '-',
            LeadQualificacao::NIVEL[$q->nivel_digital] ?? '-',
            $q->sobre_negocio ?: '-',
            $q->utm_campaign ?: ($q->utm_source ?: 'direto')
        );
    }

    private function avisar(string $assunto, string $corpo): void
    {
        $para = config('funil.notificar_email');
        if (! $para) {
            return;
        }

        try {
            Mail::raw($corpo, fn ($m) => $m->to($para)->subject($assunto));
        } catch (\Throwable $e) {
            Log::warning('Funil: falha ao enviar aviso', ['assunto' => $assunto, 'erro' => $e->getMessage()]);
        }
    }
}
