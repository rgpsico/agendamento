<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanoAluno extends Model
{
    use HasFactory;

    protected $table = 'planos_alunos';

    protected $fillable = [
        'nome',
        'descricao',
        'valor',
        'duracao_dias',
    ];

    // Relacionamento com alunos
    public function alunos()
    {
        return $this->belongsToMany(Alunos::class, 'aluno_planos', 'plano_id', 'aluno_id')
            ->withPivot('data_inicio', 'data_fim', 'status', 'valor_pago', 'forma_pagamento')
            ->withTimestamps();
    }
}
