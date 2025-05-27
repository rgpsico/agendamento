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
        'avatar',
        'asaas_customer_id', 
        'asaas_wallet_id',
        'asaas_pix_key'
    ];

    public $timestamps = false;


    public function professores()
    {
        // Busca os professores cujo usuario_id está vinculado a um usuário do tipo 'Professor'
        return $this->hasManyThrough(
            Professor::class, // Modelo alvo
            Usuario::class,   // Modelo intermediário
            'id',             // Chave estrangeira no modelo intermediário (usuarios.id)
            'usuario_id',     // Chave estrangeira no modelo alvo (professores.usuario_id)
            'user_id',        // Chave local em Empresa (empresa.user_id)
            'id'              // Chave local em Usuario (usuarios.id)
        )->where('tipo_usuario', 'Professor');
    }


    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
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

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }


    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->{$model->getKeyName()} = (string) Str::uuid();
    //     });
    // }
}
