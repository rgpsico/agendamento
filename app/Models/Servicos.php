<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicos extends Model
{
    use HasFactory;

    protected $table = 'servicos';

    protected $fillable = ['empresa_id', 'titulo', 'descricao', 'preco', 'tempo_de_aula'];
}
