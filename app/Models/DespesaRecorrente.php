<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DespesaRecorrente extends Model
{
    use HasFactory;

    protected $table = 'despesas_recorrentes';

    protected $fillable = [
        'descricao',
        'valor',
        'categoria_id',
        'status',
        'frequencia',
        'data_inicio',
        'data_fim',
        'empresa_id',
        'usuario_id',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    // Relacionamento com categoria
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(DespesaCategoria::class, 'categoria_id');
    }

    // Relacionamento com empresa
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    // Relacionamento com usuário
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Scope para despesas ativas
    public function scopeAtivas($query)
    {
        return $query->where('status', 'ACTIVE');
    }
}
