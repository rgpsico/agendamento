<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PagamentoComCartaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitir requisição
    }

    public function rules(): array
    {
        return [
            // Dados do cartão
            'card_number' => 'required|string',
            'card_holder' => 'required|string',
            'card_expiry_month' => 'required|string',
            'card_expiry_year' => 'required|string',
            'card_ccv' => 'required|string',

            // Dados do comprador
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'cpfCnpj' => 'required|string',

            // Endereço
            'address' => 'required|string',
            'addressNumber' => 'required|string',
            'province' => 'required|string',
            'postalCode' => 'required|string',

            // Informações da cobrança
            'value' => 'required|numeric',
            'payment_method' => 'required|string',
            'status' => 'required|string',

            // Dados do agendamento
            'aluno_id' => 'required|integer',
            'professor_id' => 'required|integer',
            'modalidade_id' => 'required|integer',
            'data_aula' => 'required|date',
            'hora_aula' => 'required',
            'titulo' => 'required|string',
            'usuario_id' => 'required|integer',

            // (Opcional) Dados para o split manual
            // 'split_professor_wallet_id' => 'required|string',
            // 'split_professor_value' => 'required|numeric',
        ];
    }
}
