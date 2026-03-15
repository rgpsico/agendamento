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

    protected $fillable = ['empresa_id', 'endereco', 'cidade', 'estado', 'cep', 'uf', 'pais'];

    public function Empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }

    public static function createOrUpdateEndereco(int $empresaId, array $data)
    {
        return self::updateOrCreate(
            ['empresa_id' => $empresaId],
            [
                'cep'      => $data['cep'],
                'endereco' => $data['endereco'],
                // 'numero'  => $data['numero'] ?? null,
                // 'bairro'  => $data['bairro'] ?? null,
                'cidade'   => $data['cidade'],
                'estado'   => $data['estado'],
                'uf'       => $data['uf'],
                'pais'     => $data['pais'],
            ]
        );
    }
}
