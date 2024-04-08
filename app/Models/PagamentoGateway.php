<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagamentoGateway extends Model
{
    use HasFactory;
    protected $table = 'payment_gateways';
    protected $fillable = ['name', 'api_key', 'addional_config', 'empresa_id'];
}
