<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;
    protected $table = "Agendamentos";

    protected $fillable = ['aluno_id', 'aula_id', 'professor_id', 'data_agendamento'];

    public $timestamps = false;

    public function disponibilidades()
    {
        return $this->hasMany(Disponibilidade::class, 'id_dia');
    }
}
