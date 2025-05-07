<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alunos extends Model
{
    use HasFactory;
    protected $fillable = ['usuario_id'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }

    public function professores()
    {
        return $this->belongsToMany(Professor::class, 'aluno_professor', 'aluno_id', 'professor_id');
    }


    public function galeria()
    {
        return $this->hasMany(Aluno_galeria::class, 'usuario_id');
    }

    public function endereco()
    {
        return $this->hasOne(AlunoEndereco::class, 'aluno_id', 'id');
    }

  

    public function alunos()
    {
        return $this->belongsToMany(Alunos::class, 'aluno_professor', 'professor_id', 'aluno_id');
    }
}
