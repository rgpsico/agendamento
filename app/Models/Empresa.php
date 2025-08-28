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


      public function bairros()
    {
        return $this->belongsToMany(
           Bairros::class,
            'loc_empresa_bairro', // nome da tabela pivot correta
            'empresa_id',         // FK desta tabela para empresas
            'bairro_id'           // FK desta tabela para bairros
        );
    }

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

     public static function createEmpresa(array $data)
    {
        return self::create([
            'nome'          => $data['nome'],
            'descricao'     => $data['descricao'],
            'telefone'      => $data['telefone'],
            'cnpj'          => $data['cnpj'],
            'valor_aula_de' => $data['valor_aula_de'],
            'valor_aula_ate'=> $data['valor_aula_ate'],
            'modalidade_id' => $data['modalidade_id'],
            'user_id'       => $data['user_id'],
            'avatar'        => $data['avatar'] ?? 'avatar/default.png',
            'banners'       => $data['banners'] ?? 'banner/default.jpg',
        ]);
    }
}
