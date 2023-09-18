<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreinoStripeRequest extends FormRequest
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
    public function rules()
    {
        return [
            'nome_cartao' => 'required|string',
            'numero_cartao' => 'required|string',
            'mes_vencimento' => 'required|digits:2',
            'ano_vencimento' => 'required|digits:2',
            'cvv' => 'required|digits_between:2,4',
            'nome' => 'required|string',
            //'email' => 'required|string|email|max:255|unique:usuarios,email',
            'telefone' => 'required|string',
            'servicos' => 'required|array',
            'servicos.*.id' => 'required|integer',
            'servicos.*.titulo' => 'required|string',
            'servicos.*.preco' => 'required|numeric',
        ];
    }
}
