<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Professor extends Model
{
    use HasFactory;

    protected $table = "professores";

    protected $primaryKey = 'usuario_id';
    public $incrementing = false; // Set to false because we're not using auto-incrementing IDs
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'usuario_id',
        'especialidade'
    ];



    public function aulas()
    {
        return $this->hasMany(Aulas::class, 'professor_id');
    }

    public function usuarios()
    {
        return $this->hasOne(Usuario::class, 'id', 'usuario_id');
    }

    public function endereco()
    {
        return $this->hasOne(ProfessorEndereco::class, 'id', 'professor_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
}
