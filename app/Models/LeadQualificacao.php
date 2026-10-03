<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LeadQualificacao extends Model
{
    protected $table = 'lead_qualificacoes';

    protected $fillable = [
        'lead_id', 'token', 'status',
        'nome', 'whatsapp', 'cidade', 'nicho', 'nicho_outro',
        'tem_google_meu_negocio', 'ja_fez_campanha', 'nivel_digital', 'sobre_negocio', 'observacao',
        'respondido_em', 'ip', 'user_agent',
        'utm_source', 'utm_medium', 'utm_campaign', 'host',
        'usuario_id', 'empresa_id', 'negocio_nome',
        'trial_inicio', 'trial_fim', 'asaas_subscription_id', 'asaas_customer_id', 'cliente_desde',
    ];

    protected $casts = [
        'respondido_em' => 'datetime',
        'trial_inicio'  => 'datetime',
        'trial_fim'     => 'datetime',
        'cliente_desde' => 'datetime',
    ];

    // pendente → respondido → trial → cliente
    public const STATUS = [
        'pendente'   => 'Aguardando',
        'respondido' => 'Respondido',
        'trial'      => 'Em teste',
        'cliente'    => 'Cliente',
    ];

    public const NICHOS = [
        'turismo'   => ['rotulo' => 'Turismo e passeios', 'emoji' => '🌴'],
        'beleza'    => ['rotulo' => 'Beleza e estética', 'emoji' => '💅'],
        'surf'      => ['rotulo' => 'Surf', 'emoji' => '🏄'],
        'bodyboard' => ['rotulo' => 'Bodyboard', 'emoji' => '🌊'],
        'pilates'   => ['rotulo' => 'Pilates', 'emoji' => '🧘'],
        'outro'     => ['rotulo' => 'Outro', 'emoji' => '✨'],
    ];

    public const GOOGLE = [
        'sim'     => 'Sim, já tem',
        'nao'     => 'Não tem',
        'nao_sei' => 'Não sabe o que é',
    ];

    public const CAMPANHA = [
        'nunca'   => 'Nunca fez campanha',
        'sozinho' => 'Já fez por conta própria',
        'agencia' => 'Já contratou alguém',
        'sempre'  => 'Faz campanha sempre',
    ];

    public const NIVEL = [
        'basico'        => 'Básico',
        'intermediario' => 'Intermediário',
        'avancado'      => 'Avançado',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $q) {
            $q->token = $q->token ?: Str::random(32);
        });
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function respondido(): bool
    {
        return $this->status === 'respondido';
    }

    /** Já passou do formulário (respondido, em teste ou cliente). */
    public function concluiuFormulario(): bool
    {
        return in_array($this->status, ['respondido', 'trial', 'cliente'], true);
    }

    /** Perfil que costuma travar sozinho: vale uma ligação no onboarding. */
    public function precisaOnboarding(): bool
    {
        return $this->nivel_digital === 'basico' || $this->tem_google_meu_negocio !== 'sim';
    }

    public function diasDeTeste(): ?int
    {
        if ($this->status !== 'trial' || ! $this->trial_fim) {
            return null;
        }

        return max(0, (int) ceil(now()->floatDiffInDays($this->trial_fim, false)));
    }

    public function nichoRotulo(): ?string
    {
        if (! $this->nicho) {
            return null;
        }
        if ($this->nicho === 'outro' && $this->nicho_outro) {
            return 'Outro: ' . $this->nicho_outro;
        }

        return self::NICHOS[$this->nicho]['rotulo'] ?? $this->nicho;
    }

    public function nichoEmoji(): string
    {
        return self::NICHOS[$this->nicho]['emoji'] ?? '•';
    }

    /** Leitura rápida do perfil pro painel: o que ele já tem e o que falta. */
    public function leitura(): array
    {
        if (! $this->respondido()) {
            return [];
        }

        $itens = [];
        $itens[] = match ($this->nivel_digital) {
            'avancado'      => ['ok', 'Muito ativo digitalmente: onboarding rápido, pode usar tudo.'],
            'intermediario' => ['neutro', 'Digital intermediário: precisa de um pouco de orientação.'],
            default         => ['alerta', 'Digital básico: oferecer implantação assistida e acompanhar de perto.'],
        };
        $itens[] = match ($this->tem_google_meu_negocio) {
            'sim'   => ['ok', 'Já tem Google Meu Negócio.'],
            'nao'   => ['alerta', 'Sem Google Meu Negócio: oportunidade de criar junto.'],
            default => ['alerta', 'Não sabe o que é Google Meu Negócio: explicar e oferecer criar.'],
        };
        $itens[] = match ($this->ja_fez_campanha) {
            'sempre'  => ['ok', 'Faz campanha sempre: já entende tráfego pago.'],
            'agencia' => ['ok', 'Já contratou campanha: entende o valor.'],
            'sozinho' => ['neutro', 'Já fez campanha por conta própria.'],
            default   => ['alerta', 'Nunca fez campanha: educar antes de falar de anúncio.'],
        };

        return $itens;
    }
}
