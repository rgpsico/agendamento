<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "usuarios";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'email',
        'password',
        'tipo_usuario',
        'data_nascimento',
        'telefone'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'data_nascimento',
    ];





    /**
     * Scope a query to only include students (alunos).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAlunos($query)
    {
        return $query->where('tipo_usuario', 'aluno');
    }

    public function scopeProfessores($query)
    {
        return $query->where('tipo_usuario', 'professor');
    }



    public static function getTotalRevenue()
    {
        return self::professores()->sum('revenue');
    }

    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'user_id', 'id');
    }

    public function professor()
    {
        return $this->hasOne(Professor::class, 'usuario_id', 'id');
    }



    public function disponibilidades()
    {
        return $this->hasMany(Disponibilidade::class, 'id_professor');
    }


    public function aluno()
    {
        return $this->hasOne(Alunos::class, 'usuario_id', 'id');
    }
}
