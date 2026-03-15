<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    protected $fillable = [
        'nome', 'valor', 'periodicidade', 'descricao'
    ];


        public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_plano')
                    ->withPivot(['data_inicio', 'data_fim'])
                    ->withTimestamps();
    }
}
