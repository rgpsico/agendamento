<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'sobre_nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'telefone' => 'required|string|max:20',
            'nome_cartao' => 'required|string|max:255',
            'numero_cartao' => 'required|string|max:19',
            'mes_vencimento' => 'required|string|max:2',
            'ano_vencimento' => 'required|string|min:2',
            'cvv' => 'required|string|max:3',
            'terms_accept' => 'required|accepted',
        ];
    }
}
