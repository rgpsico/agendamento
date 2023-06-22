<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicos extends Model
{
    use HasFactory;

    protected $table = 'servicos';

    protected $fillable = ['empresa_id', 'titulo', 'descricao', 'preco', 'tempo_de_aula'];

    public function servicos()
    {
        return $this->belongsTo(Servicos::class, 'empresa_id', 'id');
    }
}
