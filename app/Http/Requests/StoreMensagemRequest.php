<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMensagemRequest extends FormRequest
{
    public function authorize()
    {
        // Defina true se qualquer usuário pode fazer essa requisição
        return true;
    }

    public function rules()
    {
        return [
            'mensagem' => 'required|string',
            'conversation_id' => 'nullable|integer|exists:conversations,id',
            'phone' => 'nullable|string',
            'professor_id' => 'nullable|exists:usuarios,id',
        ];
    }

    public function messages()
    {
        return [
            'mensagem.required' => 'A mensagem é obrigatória.',
            'conversation_id.exists' => 'A conversa informada não existe.',
            'professor_id.exists' => 'O professor informado não existe.',
        ];
    }
}
