<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Empresa extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $table = 'empresa';

    protected $fillable = [
        'user_id',
        'avatar',
        'nome',
        'descricao',
        'telefone',
        'cnpj',
        'valor_aula_de',
        'valor_aula_ate',
        'modalidade_id',
        'banners',
        'data_vencimento',
        'status'
    ];

    protected $dates = [
        'data_vencimento'
    ];

    protected $casts = [
        'data_vencimento' => 'date'
    ];

    // Relacionamentos existentes
    public function site()
    {
        return $this->hasOne(EmpresaSite::class, 'empresa_id', 'id');
    }

    public function endereco()
    {
        return $this->hasOne(EmpresaEndereco::class, 'empresa_id', 'id');
    }

    public function paymentGateways()
    {
        return $this->hasMany(PagamentoGateway::class, 'empresa_id');
    }

    public function modalidade()
    {
        return $this->belongsTo(Modalidade::class, 'modalidade_id');
    }

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id');
    }

    public function galeria()
    {
        return $this->hasMany(EmpresaGaleria::class, 'empresa_id', 'id');
    }

    public function avaliacao()
    {
        return $this->hasMany(EmpresaAvaliacao::class, 'empresa_id', 'id');
    }

    public function servicos()
    {
        return $this->hasMany(Servicos::class, 'empresa_id', 'id');
    }

    public function professores()
    {
        return $this->hasMany(Professor::class, 'empresa_id', 'id');
    }
}
