<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'pix_enabled',
        'cartao_enabled',
        'presencial_enabled',
        'pix_config',
        'cartao_config',
        'presencial_config'
    ];

    protected $casts = [
        'pix_enabled' => 'boolean',
        'cartao_enabled' => 'boolean',
        'presencial_enabled' => 'boolean',
        'pix_config' => 'array',
        'cartao_config' => 'array',
        'presencial_config' => 'array',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function getEnabledPaymentMethods()
    {
        $methods = [];

        if ($this->pix_enabled) {
            $methods[] = 'pix';
        }

        if ($this->cartao_enabled) {
            $methods[] = 'cartao';
        }

        if ($this->presencial_enabled) {
            $methods[] = 'presencial';
        }

        return $methods;
    }

    public static function getForEmpresa($empresaId)
    {
        return self::firstOrCreate(
            ['empresa_id' => $empresaId],
            [
                'pix_enabled' => true,
                'cartao_enabled' => true,
                'presencial_enabled' => true,
                'pix_config' => [
                    'expiration_minutes' => 30,
                    'show_qr_code' => true,
                    'auto_generate' => false
                ],
                'cartao_config' => [
                    'installments_enabled' => true,
                    'max_installments' => 12,
                    'min_installment_amount' => 30.00
                ],
                'presencial_config' => [
                    'cancellation_hours' => 24,
                    'accepted_methods' => ['dinheiro', 'pix', 'cartao'],
                    'require_confirmation' => true
                ]
            ]
        );
    }
}
