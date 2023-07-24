<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlunoProfessor extends Model
{
    use HasFactory;

    protected $fillable = ['aluno_id', 'professor_id'];


    protected $table = "aluno_professor";

    public function aluno()
    {
        return $this->belongsTo(Alunos::class, 'aluno_id');
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id');
    }
}
