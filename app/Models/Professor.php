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


    // public function endereco()
    // {
    //     return $this->hasOne(ProfessorEndereco::class, 'id', 'professor_id');
    // }

    public function disponibilidades()
    {
        return $this->hasOne(Disponibilidade::class, 'id', 'professor_id');
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }

    public function avaliacoes()
    {
        return $this->hasMany(ProfessorAvaliacao::class, 'professor_id');
    }

    // Método para obter a média das avaliações
    public function getMediaAvaliacaoAttribute()
    {
        return $this->avaliacoes()->avg('nota') ?? 0;
    }

    // Método para contar quantas avaliações existem
    public function getTotalAvaliacoesAttribute()
    {
        return $this->avaliacoes()->count();
    }


    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->{$model->getKeyName()} = (string) Str::uuid();
    //     });
    // }
}
