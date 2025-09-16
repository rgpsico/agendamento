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


        public function plano()
    {
        return $this->belongsTo(Plano::class);
    }

    public function planos()
    {
        return $this->belongsToMany(Plano::class, 'empresa_plano')
                    ->withPivot(['data_inicio', 'data_fim'])
                    ->withTimestamps();
    }

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


   

        /**
         * Atualiza os dados principais da empresa.
         *
         * @param array $data
         * @return void
         */
        public function atualizarDados(array $data): void
        {
            $this->update([
                'nome' => $data['nome'],
                'descricao' => $data['descricao'],
                'telefone' => $data['telefone'],
                'cnpj' => $data['cnpj'],
                'data_vencimento' => $data['data_vencimento'] ?? today()->format('Y-m-d'),
                'valor_aula_de' => $data['valor_aula_de'],
                'valor_aula_ate' => $data['valor_aula_ate'],
                'modalidade_id' => $data['modalidade_id'],
                'site_url' => $data['site_url'] ?? $this->site_url,
                'avatar' => $data['avatar'] ?? $this->avatar,
                'banner' => $data['banner'] ?? $this->banner,
            ]);
        }


        /**
     * Atualiza ou cria o endereço da empresa.
     *
     * @param array $data
     * @return EmpresaEndereco
     */
    public function atualizarEndereco(array $data): EmpresaEndereco
    {
        return $this->endereco()->updateOrCreate(
            ['empresa_id' => $this->id],
            [
                'cep' => $data['cep'],
                'endereco' => $data['endereco'],
                'cidade' => $data['cidade'],
                'estado' => $data['estado'],
                'uf' => $data['uf'],
                'pais' => $data['pais'],
            ]
        );
    }

    /**
     * Sincroniza os bairros da empresa.
     *
     * @param array|null $bairros
     * @return void
     */
    public function atualizarBairros(?array $bairros): void
    {
        if (!empty($bairros)) {
            $this->bairros()->sync($bairros);
        } else {
            $this->bairros()->detach();
        }
    }
}
