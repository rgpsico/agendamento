<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bairro extends Model
{
    protected $table = 'bairros';
    protected $fillable = ['nome'];

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_bairro', 'bairro_id', 'empresa_id');
    }
}
