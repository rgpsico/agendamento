<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;
    protected $table = "Agendamentos";

    protected $fillable = ['aluno_id', 'aula_id', 'professor_id', 'data_agendamento'];

    protected $dates = ['data_da_aula'];

    public function disponibilidades()
    {
        return $this->hasMany(Disponibilidade::class, 'id_dia');
    }

    public function aluno()
    {
        return $this->belongsTo(Alunos::class);
    }

    public function aula()
    {
        return $this->belongsTo(Aulas::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id', 'id');
    }

    public function modalidade()
    {
        return $this->belongsTo(Modalidade::class, 'modalidade_id', 'id');
    }
}
