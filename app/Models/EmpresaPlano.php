<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmpresaPlano extends Model
{
    use HasFactory;

    protected $table = 'empresa_plano'; // nome da tabela

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'empresa_id',
        'plano_id',
        'status',        // ativo, cancelado, trial, etc
        'data_inicio',
        'data_fim',
    ];

    // Relacionamentos
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class);
    }

    // Exemplo: verificar se o plano está ativo
    public function isAtivo()
    {
        return $this->status === 'ativo' && now()->between($this->data_inicio, $this->data_fim);
    }
}
