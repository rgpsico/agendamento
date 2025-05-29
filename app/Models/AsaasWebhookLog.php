<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsaasWebhookLog extends Model
{
    protected $fillable = [
        'event',
        'payload',
        'status',
        'message',
        'payment_id',
    ];

    protected $casts = [
        'payload' => 'array', // Converte o campo JSON para array automaticamente
    ];
}