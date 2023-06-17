<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaGaleria extends Model
{
    use HasFactory;

    public $table = 'empresa_galeria';
    protected $fillable = ['empresa_id', 'image'];


    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
