<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDespesaRequest extends FormRequest
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
            'descricao' => 'sometimes|required|string|max:255',
            'valor' => 'sometimes|required|numeric|min:0',
            'categoria' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:PENDING,PAID',
            'data_vencimento' => 'sometimes|nullable|date|after_or_equal:today',
            // Não valide empresa_id/usuario_id no update, pois não devem mudar
        ];
    }
}
