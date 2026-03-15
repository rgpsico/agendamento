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
            'categoria_id' => 'required|string|max:255',  // Removi nullable, pois na migração é nullable, mas valide como required se quiser
            'status' => 'required|in:PENDING,PAID',
            'data_vencimento' => 'nullable|date|after_or_equal:today',  // Evita datas passadas
            'empresa_id' => 'required|exists:empresa,id',  // Valida existência
            'usuario_id' => 'required|exists:usuarios,id',
        ];
    }
}
