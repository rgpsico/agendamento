<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    protected $table = 'configuracoes';
    protected $fillable = ['empresa_id', 'chave', 'valor', 'tipo'];
    public $timestamps = true;

    public static function get($empresaId, $chave, $default = null)
    {
        $config = self::where('empresa_id', $empresaId)->where('chave', $chave)->first();
        return $config ? self::castValue($config->valor, $config->tipo) : $default;
    }

    public static function set($empresaId, $chave, $valor)
    {
        $tipo = gettype($valor);
        if ($tipo === 'array' || $tipo === 'object') {
            $valor = json_encode($valor);
            $tipo = 'json';
        }

        return self::updateOrCreate(
            ['empresa_id' => $empresaId, 'chave' => $chave],
            ['valor' => $valor, 'tipo' => $tipo]
        );
    }

    private static function castValue($valor, $tipo)
    {
        return match ($tipo) {
            'boolean' => filter_var($valor, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $valor,
            'json'    => json_decode($valor, true),
            default   => $valor,
        };
    }
}
