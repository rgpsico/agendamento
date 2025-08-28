<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bairro extends Model{
    
protected $table = 'neighborhoods';
protected $fillable = ['name', 'city_id', 'zone_id'];

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_bairro', 'bairro_id', 'empresa_id');
    }
}
