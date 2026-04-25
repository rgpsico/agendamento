<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lead extends Model
{
    protected $fillable = [
        'tenant_id',
        'nome',
        'email',
        'telefone',
        'empresa',
        'origem',
        'status',
        'pipeline_status',
        'interesse',
        'valor_estimado',
        'observacoes',
        'responsavel_id',
        'campanha_id',
        'trial_usuario_id',
        'email_enviado_em',
        'whatsapp_enviado_em',
        'token',
        'morno_em',
        'interessado_em',
        'whatsapp_confirmado',
    ];

    protected $casts = [
        'email_enviado_em' => 'datetime',
        'whatsapp_enviado_em' => 'datetime',
        'morno_em' => 'datetime',
        'interessado_em' => 'datetime',
        'valor_estimado' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Lead $lead) {
            if (empty($lead->token)) {
                $lead->token = Str::uuid();
            }

            if (empty($lead->pipeline_status)) {
                $lead->pipeline_status = 'novo_lead';
            }

            if (empty($lead->status)) {
                $lead->status = 'novo';
            }
        });
    }

    public static array $temperaturaConfig = [
        'frio' => ['label' => 'Frio', 'color' => 'secondary', 'icon' => 'Frio'],
        'morno' => ['label' => 'Morno', 'color' => 'warning', 'icon' => 'Morno'],
        'quente' => ['label' => 'Quente', 'color' => 'danger', 'icon' => 'Quente'],
    ];

    public static array $origens = [
        'manual' => 'Manual',
        'site' => 'Site',
        'whatsapp' => 'WhatsApp',
        'instagram' => 'Instagram',
        'google' => 'Google',
        'indicacao' => 'Indicacao',
        'redes_sociais' => 'Redes Sociais',
        'outro' => 'Outro',
    ];

    public static array $statusList = [
        'novo' => 'Novo',
        'em_contato' => 'Em Contato',
        'qualificado' => 'Qualificado',
        'proposta_enviada' => 'Proposta Enviada',
        'convertido' => 'Convertido',
        'perdido' => 'Perdido',
    ];

    public static array $statusColors = [
        'novo' => 'primary',
        'em_contato' => 'info',
        'qualificado' => 'warning',
        'proposta_enviada' => 'secondary',
        'convertido' => 'success',
        'perdido' => 'danger',
    ];

    public static array $pipelineStatus = [
        'novo_lead' => 'Novo Lead',
        'em_contato' => 'Em Contato',
        'aula_experimental' => 'Aula Experimental',
        'matriculado' => 'Matriculado',
        'recorrente' => 'Recorrente',
    ];

    public static array $prioridades = [
        'novo_lead' => 'Media',
        'em_contato' => 'Alta',
        'aula_experimental' => 'Alta',
        'matriculado' => 'Media',
        'recorrente' => 'Baixa',
    ];

    public function tenant()
    {
        return $this->belongsTo(Empresa::class, 'tenant_id');
    }

    public function responsavel()
    {
        return $this->belongsTo(Usuario::class, 'responsavel_id');
    }

    public function campanha()
    {
        return $this->belongsTo(Campanha::class, 'campanha_id');
    }

    public function historicos()
    {
        return $this->hasMany(LeadHistorico::class, 'lead_id')->latest();
    }

    public function tarefas()
    {
        return $this->hasMany(Tarefa::class, 'lead_id')->latest();
    }

    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function getIsLeadQuenteAttribute(): bool
    {
        return ! is_null($this->interessado_em);
    }

    public function getIsLeadMornoAttribute(): bool
    {
        return ! is_null($this->morno_em) && is_null($this->interessado_em);
    }

    public function getTemperaturaAttribute(): string
    {
        if ($this->interessado_em) {
            return 'quente';
        }

        if ($this->morno_em) {
            return 'morno';
        }

        return 'frio';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::$statusList[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::$statusColors[$this->status] ?? 'secondary';
    }

    public function getOrigemLabelAttribute(): string
    {
        return self::$origens[$this->origem] ?? $this->origem;
    }

    public function getPipelineStatusLabelAttribute(): string
    {
        return self::$pipelineStatus[$this->pipeline_status] ?? $this->pipeline_status ?? 'Novo Lead';
    }

    public function getPrioridadeAttribute(): string
    {
        if ((float) $this->valor_estimado >= 500) {
            return 'Alta';
        }

        return self::$prioridades[$this->pipeline_status] ?? 'Media';
    }

    public function getTempoNoEstagioAttribute(): string
    {
        $ultimoHistorico = $this->relationLoaded('historicos') ? $this->historicos->first() : $this->historicos()->first();
        $referencia = $ultimoHistorico?->created_at ?? $this->created_at;

        return $referencia ? $referencia->diffForHumans(null, true) : '-';
    }

    public function getWhatsappMensagemPadraoAttribute(): string
    {
        $primeiroNome = trim(explode(' ', trim($this->nome))[0] ?? $this->nome);
        $nome = $primeiroNome !== '' ? $primeiroNome : $this->nome;

        return "Bom dia, {$nome}! Tudo bem? 😊\n\n"
            . "Vi que você trabalha com Pilates e entrei em contato porque tenho um sistema que pode facilitar bastante sua rotina.\n\n"
            . "Ele ajuda a organizar seus horários, automatiza agendamentos e ainda conta com um robô que responde seus alunos e um site pronto pra você divulgar seu trabalho.\n\n"
            . "Quero te liberar um acesso de teste já configurado, pra você só entrar e usar.";
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        return $this->whatsapp_url_com_mensagem;
    }

    public function getWhatsappUrlComMensagemAttribute(): ?string
    {
        if (empty($this->telefone)) {
            return null;
        }

        $numero = preg_replace('/\D/', '', $this->telefone);

        if (strlen($numero) <= 11) {
            $numero = '55' . $numero;
        }

        return 'https://wa.me/' . $numero . '?text=' . rawurlencode($this->whatsapp_mensagem_padrao);
    }
}
