<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaEndereco extends Model
{
    protected $table = 'empresa_endereco';

    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'empresa_uuid', 'endereco', 'cidade', 'estado', 'cep'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_uuid', 'uuid');
    }
}
