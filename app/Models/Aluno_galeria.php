<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno_Galeria extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'alunos_galeria';

    public function aluno()
    {
        return $this->belongsTo(Alunos::class, 'usuario_id');
    }
}
