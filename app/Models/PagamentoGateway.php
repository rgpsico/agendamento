<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagamentoGateway extends Model
{
    use HasFactory;
    protected $table = 'payment_gateways';
    protected $fillable = [
        'name',
        'api_key',
        'mode', // sandbox ou production
        'methods', // Métodos de pagamento (ex.: ['pix', 'boleto', 'card'])
        'split_account', // Conta do dono do SaaS para split
        'tariff_type', // fixed ou percentage
        'tariff_value', // Valor da tarifa
        'empresa_id',
        'status',
    ];

    protected $casts = [
        'methods' => 'array', // Converte o campo methods para array
        'status' => 'boolean', // Converte status para booleano
    ];
    
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
