<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    use HasFactory;

    protected $table = 'receitas'; // Ajuste se necessário

    protected $fillable = [
        'descricao',
        'pagamento_id',
        'valor',
        'data',
        'categoria_id',
        'usuario_id',
        'status',
        'empresa_id'
    ];

    protected $casts = [
        'data' => 'datetime',
    ];

    // Relacionamentos necessários
    public function categoria()
    {
        return $this->belongsTo(FinanceiroCategoria::class, 'categoria_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class); // Assumindo que Receita tem 'pagamento_id' ou similar
    }
}