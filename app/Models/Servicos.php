<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicos extends Model
{
    use HasFactory;

    protected $table = 'servicos';

    protected $fillable = ['empresa_id', 'imagem', 'titulo', 'descricao', 'preco', 'tempo_de_aula', 'tipo_agendamento'];

    public function servicos()
    {
        return $this->belongsTo(Servicos::class, 'empresa_id', 'id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }
}
