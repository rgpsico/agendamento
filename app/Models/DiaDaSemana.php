<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaDaSemana extends Model
{
    use HasFactory;
    protected $table = "dias_da_semana";

    protected $fillable = ['dia', 'nome_dia'];

    public $timestamps = false;

    public function disponibilidades()
    {
        return $this->hasMany(Disponibilidade::class, 'id_dia');
    }
}
