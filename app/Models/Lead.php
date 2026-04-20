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
}
