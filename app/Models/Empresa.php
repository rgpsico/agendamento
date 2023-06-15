<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';

    use HasFactory;

    protected $primaryKey = 'user_id';

    public $incrementing = true;

    protected $keyType = 'string';

    protected $fillable = ['user_id', 'avatar', 'nome', 'descricao', 'telefone', 'cnpj'];

    public function endereco()
    {
        return $this->hasOne(EmpresaEndereco::class, 'empresa_id', 'id');
    }
}
