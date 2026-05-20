<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modalidade extends Model
{
    use HasFactory;

    protected $table = 'modalidade';

    protected $fillable = [
        'nome',
        'nicho',
    ];

    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'modalidade_id');
    }

    /**
     * Scope para filtrar pelo nicho do domínio atual.
     * Se não houver nicho detectado, retorna todas.
     */
    public function scopeDoNichoAtual($query)
    {
        $nicho = app()->has('currentNicho') ? app('currentNicho')->nicho : null;

        if ($nicho) {
            return $query->where('nicho', $nicho);
        }

        return $query;
    }

    /**
     * Scope para filtrar por nicho específico.
     */
    public function scopeDoNicho($query, string $nicho)
    {
        return $query->where('nicho', $nicho);
    }
}
