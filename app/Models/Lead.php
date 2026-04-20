<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'empresa',
        'origem',
        'status',
        'interesse',
        'observacoes',
        'responsavel_id',
        'trial_usuario_id',
        'email_enviado_em',
        'token',
        'morno_em',
        'interessado_em',
        'whatsapp_confirmado',
    ];

    protected $casts = [
        'email_enviado_em' => 'datetime',
        'morno_em'         => 'datetime',
        'interessado_em'   => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Lead $lead) {
            if (empty($lead->token)) {
                $lead->token = \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function getIsLeadQuenteAttribute(): bool
    {
        return !is_null($this->interessado_em);
    }

    public function getIsLeadMornoAttribute(): bool
    {
        return !is_null($this->morno_em) && is_null($this->interessado_em);
    }

    // frio | morno | quente
    public function getTemperaturaAttribute(): string
    {
        if ($this->interessado_em) return 'quente';
        if ($this->morno_em)      return 'morno';
        return 'frio';
    }

    public static array $temperaturaConfig = [
        'frio'   => ['label' => 'Frio',   'color' => 'secondary', 'icon' => '🧊'],
        'morno'  => ['label' => 'Morno',  'color' => 'warning',   'icon' => '🌡️'],
        'quente' => ['label' => 'Quente', 'color' => 'danger',    'icon' => '🔥'],
    ];

    public static array $origens = [
        'manual'       => 'Manual',
        'site'         => 'Site',
        'whatsapp'     => 'WhatsApp',
        'indicacao'    => 'Indicação',
        'redes_sociais' => 'Redes Sociais',
        'outro'        => 'Outro',
    ];

    public static array $statusList = [
        'novo'              => 'Novo',
        'em_contato'        => 'Em Contato',
        'qualificado'       => 'Qualificado',
        'proposta_enviada'  => 'Proposta Enviada',
        'convertido'        => 'Convertido',
        'perdido'           => 'Perdido',
    ];

    public static array $statusColors = [
        'novo'             => 'primary',
        'em_contato'       => 'info',
        'qualificado'      => 'warning',
        'proposta_enviada' => 'secondary',
        'convertido'       => 'success',
        'perdido'          => 'danger',
    ];

    public function responsavel()
    {
        return $this->belongsTo(Usuario::class, 'responsavel_id');
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

    public function getWhatsappUrlAttribute(): ?string
    {
        if (empty($this->telefone)) return null;

        $numero = preg_replace('/\D/', '', $this->telefone);

        // Adiciona DDI 55 (Brasil) se não tiver
        if (strlen($numero) <= 11) {
            $numero = '55' . $numero;
        }

        return 'https://wa.me/' . $numero;
    }
}
