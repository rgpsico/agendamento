<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DespesaCategoria extends Model
{
    use HasFactory, \App\Models\Concerns\DaEmpresaLogada;

    /** Categorias sem empresa (empresa_id nulo) são padrões visíveis para todas. */
    protected static bool $empresaOpcional = true;

    protected $table = "despesas_categorias";

    protected $fillable = ['nome', 'descricao', 'empresa_id'];

    // Relacionamento com despesas
    public function despesas()
    {
        return $this->hasMany(Despesas::class, 'categoria_id');
    }
}
