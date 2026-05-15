<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AutomacaoEtapa extends Model
{
    protected $table = 'automacao_etapas';

    protected $fillable = [
        'sequencia_id',
        'ordem',
        'canal',
        'delay_dias',
        'delay_horas',
        'tipo_mensagem',
        'instrucao_ia',
        'template_mensagem',
        'assunto_email',
    ];

    protected $casts = [
        'ordem'       => 'integer',
        'delay_dias'  => 'integer',
        'delay_horas' => 'integer',
    ];

    public function sequencia(): BelongsTo
    {
        return $this->belongsTo(AutomacaoSequencia::class, 'sequencia_id');
    }

    public function envios(): HasMany
    {
        return $this->hasMany(AutomacaoEnvio::class, 'etapa_id');
    }

    /** Calcula o timestamp de envio com base no delay */
    public function calcularEnvioEm(?\Carbon\Carbon $base = null): \Carbon\Carbon
    {
        $base = $base ?? now();
        return $base->copy()->addDays($this->delay_dias)->addHours($this->delay_horas);
    }

    /** Substitui placeholders no template com dados do lead */
    public function preencherTemplate(Lead $lead): string
    {
        $primeiroNome = trim(explode(' ', trim($lead->nome))[0] ?? $lead->nome);

        $placeholders = [
            '{nome}'         => $lead->nome ?? '',
            '{primeiro_nome}'=> $primeiroNome,
            '{interesse}'    => $lead->interesse ?? '',
            '{bairro}'       => $lead->bairro ?? '',
            '{empresa}'      => $lead->empresa ?? '',
            '{telefone}'     => $lead->telefone ?? '',
            '{email}'        => $lead->email ?? '',
        ];

        return str_replace(
            array_keys($placeholders),
            array_values($placeholders),
            $this->template_mensagem ?? ''
        );
    }

    public function getDelayTextoAttribute(): string
    {
        if ($this->delay_dias === 0 && $this->delay_horas === 0) {
            return 'Imediato';
        }

        $partes = [];
        if ($this->delay_dias > 0) {
            $partes[] = $this->delay_dias . ' dia' . ($this->delay_dias > 1 ? 's' : '');
        }
        if ($this->delay_horas > 0) {
            $partes[] = $this->delay_horas . ' hora' . ($this->delay_horas > 1 ? 's' : '');
        }

        return 'Após ' . implode(' e ', $partes);
    }

    public function getCanalLabelAttribute(): string
    {
        return match ($this->canal) {
            'whatsapp' => 'WhatsApp',
            'email'    => 'E-mail',
            'ambos'    => 'WhatsApp + E-mail',
            default    => $this->canal,
        };
    }
}
