<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bairros extends Model
{
    protected $table = 'loc_bairros';
    protected $fillable = ['nome', 'cidade_id', 'zona_id'];

    public function empresas()
    {
        return $this->belongsToMany(
            Empresa::class,
            'loc_empresa_bairro', // <-- corrigido para o nome correto da tabela pivot
            'bairro_id',
            'empresa_id'
        );
    }
}
