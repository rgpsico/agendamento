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

    public static function criarPagamentoPresencial($agendamento, $dados)
    {
        return self::create([
            'agendamento_id' => $agendamento->id,
            'aluno_id' => $dados['aluno_id'],
            'pagamento_gateway_id' => null, // Não usado para pagamento presencial
            'asaas_payment_id' => null, // Não usado para pagamento presencial
            'status' => $dados['status'] ?? 'PENDING', // PENDING ou RECEIVED
            'valor' => $dados['valor_aula'],
            'metodo_pagamento' => 'PRESENCIAL',
            'data_vencimento' => null,
            'url_boleto' => null,
            'qr_code_pix' => null,
            'resposta_api' => null,
        ]);
    }


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