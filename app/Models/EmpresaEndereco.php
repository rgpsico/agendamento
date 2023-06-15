<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaEndereco extends Model
{
    protected $table = 'empresa_endereco';

    public $timestamps = false;
    use HasFactory;

    protected $primaryKey = 'empresa_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'empresa_id', 'endereco', 'cidade', 'estado', 'cep', 'uf', 'pais'];

    public function Empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }
}
