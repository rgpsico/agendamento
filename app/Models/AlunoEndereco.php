<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlunoEndereco extends Model
{
    use HasFactory;

    protected $table = 'alunos_enderecos';

    protected $fillable = ['usuario_id'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }


    public function galeria()
    {
        return $this->hasMany(Aluno_galeria::class, 'usuario_id');
    }

    public function endereco()
    {
        return $this->hasOne(AlunosEndereco::class, 'empresa_id', 'id');
    }
}
