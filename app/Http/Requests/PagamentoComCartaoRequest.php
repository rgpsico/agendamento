<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PagamentoComCartaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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

    public function messages(): array
    {
        return [
            // Dados do cartão
            'card_number.required' => 'O número do cartão é obrigatório.',
            'card_holder.required' => 'O nome do titular do cartão é obrigatório.',
            'card_expiry_month.required' => 'O mês de validade do cartão é obrigatório.',
            'card_expiry_year.required' => 'O ano de validade do cartão é obrigatório.',
            'card_ccv.required' => 'O código de segurança (CVV) é obrigatório.',

            // Dados do comprador
            'name.required' => 'O nome do comprador é obrigatório.',
            'email.required' => 'O e-mail do comprador é obrigatório.',
            'email.email' => 'O e-mail informado não é válido.',
            'phone.required' => 'O telefone do comprador é obrigatório.',
            'cpfCnpj.required' => 'O CPF ou CNPJ do comprador é obrigatório.',

            // Endereço
            'address.required' => 'O endereço é obrigatório.',
            'addressNumber.required' => 'O número do endereço é obrigatório.',
            'province.required' => 'O bairro é obrigatório.',
            'postalCode.required' => 'O CEP é obrigatório.',

            // Informações da cobrança
            'value.required' => 'O valor da cobrança é obrigatório.',
            'value.numeric' => 'O valor da cobrança deve ser numérico.',
            'payment_method.required' => 'O método de pagamento é obrigatório.',
            'status.required' => 'O status do pagamento é obrigatório.',

            // Dados do agendamento
            'aluno_id.required' => 'O ID do aluno é obrigatório.',
            'aluno_id.integer' => 'O ID do aluno deve ser um número inteiro.',
            'professor_id.required' => 'O ID do professor é obrigatório.',
            'professor_id.integer' => 'O ID do professor deve ser um número inteiro.',
            'modalidade_id.required' => 'A modalidade da aula é obrigatória.',
            'modalidade_id.integer' => 'A modalidade deve ser um número inteiro.',
            'data_aula.required' => 'A data da aula é obrigatória.',
            'data_aula.date' => 'A data da aula deve estar em um formato válido.',
            'hora_aula.required' => 'O horário da aula é obrigatório.',
            'titulo.required' => 'O título da aula é obrigatório.',
            'usuario_id.required' => 'O ID do usuário é obrigatório.',
            'usuario_id.integer' => 'O ID do usuário deve ser um número inteiro.',

            // (se usar split manual futuramente)
            // 'split_professor_wallet_id.required' => 'O ID da carteira do professor é obrigatório.',
            // 'split_professor_value.required' => 'O valor da divisão para o professor é obrigatório.',
            // 'split_professor_value.numeric' => 'O valor da divisão para o professor deve ser numérico.',
        ];
    }
}
