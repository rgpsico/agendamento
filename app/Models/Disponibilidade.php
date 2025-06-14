<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibilidade extends Model
{
    use HasFactory;

    protected $table = "disponibilidade"; // Ajuste para a tabela correta
    protected $fillable = ['id_professor', 'id_dia', 'hora_inicio', 'hora_fim', 'id_servico', 'data'];

    public function diaDaSemana()
    {
        return $this->belongsTo(DiaDaSemana::class, 'id_dia');
    }
}
