<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feriado extends Model
{
    // Nome da tabela (se não seguir o padrão plural)
    protected $table = 'feriados';

    // Campos que podem ser preenchidos via create ou fill
    protected $fillable = [
        'data',
        'descricao',
    ];

    // Desativa timestamps automáticos se não usar created_at/updated_at
    public $timestamps = false;

    // Se quiser, pode colocar cast para 'data' ser tratado como date
    protected $casts = [
        'data' => 'date',
    ];
}
