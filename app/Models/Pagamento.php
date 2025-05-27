<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $table = 'pagamentos';

    protected $fillable = [
    'agendamento_id',
    'aluno_id',
    'pagamento_gateway_id',
    'asaas_payment_id',
    'status',
    'valor',
    'metodo_pagamento',
    'data_vencimento',
    'url_boleto',
    'qr_code_pix',
    'resposta_api',
];

    protected $casts = [
        'data_vencimento' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class);
    }

    public function aluno()
    {
        return $this->belongsTo(Alunos::class);
    }

    public function pagamentoGateway()
    {
        return $this->belongsTo(PagamentoGateway::class, 'pagamento_gateway_id');
    }
}