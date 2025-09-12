<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DespesaCategoria extends Model
{
    use HasFactory;

    protected $table = "despesas_categorias";

    protected $fillable = ['nome', 'descricao'];

    // Relacionamento com despesas
    public function despesas()
    {
        return $this->hasMany(Despesas::class, 'categoria_id');
    }
}
