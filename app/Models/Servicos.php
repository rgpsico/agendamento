<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicos extends Model
{
    use HasFactory;

    protected $table = 'servicos';

    protected $fillable = [
        'empresa_id',
        'imagem',
        'titulo',
        'descricao',
        'preco',
        'tempo_de_aula',
        'tipo_agendamento',
        'categoria_id'
    ];

    public function servicos()
    {
        return $this->belongsTo(Servicos::class, 'empresa_id', 'id');
    }

    public function disponibilidades()
    {
        return $this->hasMany(Disponibilidade::class, 'id_servico');
    }

    public function categoria()
    {
        return $this->belongsTo(FinanceiroCategoria::class, 'categoria_id');
    }


    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }
}
