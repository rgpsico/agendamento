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

    protected $fillable = ['user_id', 'avatar', 'nome', 'descricao', 'telefone', 'cnpj', 'valor_aula_de', 'valor_aula_ate'];

    public function endereco()
    {
        return $this->hasOne(EmpresaEndereco::class, 'empresa_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id');
    }

    public function galeria()
    {
        return $this->hasMany(EmpresaGaleria::class, 'empresa_id', 'id');
    }
}
