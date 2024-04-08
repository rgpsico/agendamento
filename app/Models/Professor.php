<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Professor extends Model
{
    use HasFactory;

    protected $table = "professores";


    protected $fillable = [
        'usuario_id',
        'especialidade',
        'sobre',
        'avatar'
    ];

    public $timestamps = false;

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id', 'usuario_id');
    }


    public function alunos()
    {
        return $this->belongsToMany(Alunos::class, 'aluno_professor', 'professor_id', 'aluno_id');
    }

    public function aulas()
    {
        return $this->hasMany(Aulas::class, 'professor_id');
    }


    public function endereco()
    {
        return $this->hasOne(ProfessorEndereco::class, 'id', 'professor_id');
    }

    public function disponibilidades()
    {
        return $this->hasOne(Disponibilidade::class, 'id', 'professor_id');
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }


    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->{$model->getKeyName()} = (string) Str::uuid();
    //     });
    // }
}
