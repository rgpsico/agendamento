<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Despesas extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'valor',
        'status',
        'data_vencimento',
        'empresa_id',  // Adicionado
        'usuario_id',  // Adicionado
        'categoria_id',   // Substituindo 'categoria'
    ];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',  // Isso força float com 2 casas decimais
            'data_vencimento' => 'date',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(DespesaCategoria::class, 'categoria_id');
    }

    // Relacionamentos
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);  // Ajuste o namespace se necessário
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);  // Assumindo modelo Usuario
    }

    // Scope para despesas pendentes
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    // Accessor para valor formatado (opcional, para views)
    protected function valor(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value, 2, ',', '.'),
        );
    }

    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class, 'pagamento_id'); // ajuste o nome da FK
    }
}
