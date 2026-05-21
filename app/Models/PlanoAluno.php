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
        'periodicidade',
        'tipo',          // 'semanal' | 'livre'
        'dias_semana',   // JSON: ["seg","qua","sex"]
        'horario',       // "07:00"
        'aulas_semana',  // int calculado
    ];

    protected $casts = [
        'valor'        => 'float',
        'duracao_dias' => 'integer',
        'aulas_semana' => 'integer',
    ];

    /* ── Accessor: dias_semana como array ── */
    public function getDiasArrayAttribute(): array
    {
        return json_decode($this->dias_semana ?? '[]', true) ?: [];
    }

    public function getDiasLabelAttribute(): string
    {
        $mapa = ['seg'=>'Seg','ter'=>'Ter','qua'=>'Qua','qui'=>'Qui','sex'=>'Sex','sab'=>'Sáb','dom'=>'Dom'];
        return collect($this->dias_array)->map(fn($d) => $mapa[$d] ?? $d)->join(', ');
    }

    /* ── Relacionamentos ── */
    public function alunos()
    {
        return $this->belongsToMany(Alunos::class, 'aluno_planos', 'plano_id', 'aluno_id')
            ->withPivot('data_inicio', 'data_fim', 'status', 'valor_pago', 'forma_pagamento')
            ->withTimestamps();
    }
}
