<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDespesaRequest extends FormRequest
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
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'categoria_id' => 'required|integer|exists:despesas_categorias,id',
            'status' => 'required|in:PENDING,PAID',
            'data_vencimento' => 'nullable|date',  // contas já vencidas/pagas também são lançadas
            'empresa_id' => 'nullable|exists:empresa,id',  // o model troca pela empresa do usuário logado
            'usuario_id' => 'nullable|exists:usuarios,id',
        ];
    }
}
