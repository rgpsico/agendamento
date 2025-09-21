<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceitaRecorrente extends Model
{
    use HasFactory;

    protected $table = 'receitas_recorrentes';

    protected $fillable = [
        'descricao',
        'valor',
        'frequencia',
        'data_inicio',
        'data_fim',
        'empresa_id',
        'usuario_id',
        'categoria_id',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_inicio' => 'date',   // garante Carbon
        'data_fim' => 'date',      // garante Carbon ou null
    ];

    // Em DespesaRecorrente ou ReceitaRecorrente
    public function categoria()
    {
        return $this->belongsTo(FinanceiroCategoria::class, 'categoria_id');
    }


    // Relacionamentos
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
