<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receitas extends Model
{
    use HasFactory;

    protected $table = 'receitas';

    protected $fillable = [
        'pagamento_id',       // vínculo direto com pagamento
        'categoria_id',       // vínculo com categoria financeira
        'descricao',
        'valor',
        'status',             // RECEBIDA, PENDENTE, CANCELADA
        'data_recebimento',
        'empresa_id',
        'usuario_id',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_recebimento' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relacionamentos
    |--------------------------------------------------------------------------
    */
    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class);
    }

    public function categoria()
    {
        return $this->belongsTo(FinanceiroCategoria::class, 'categoria_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
