<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CriarPagamentoPresencialRequest extends FormRequest
{
    public function authorize()
    {
        // Se quiser que qualquer usuário logado possa fazer
        return true;
    }

    public function rules()
    {
       
        return [
            // 'aluno_id' => 'required|exists:alunos,id',
            
            // 'professor_id' => 'required|exists:professores,id',
            // 'modalidade_id' => 'required|exists:modalidade,id',
            // 'data_aula' => 'required|date_format:Y-m-d',
            // 'hora_aula' => 'required|date_format:H:i',
            // 'valor_aula' => 'required|numeric|min:0',
            // 'status' => 'required|in:PENDING,RECEIVED',
            'titulo' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'aluno_id.required' => 'O aluno é obrigatório.',
            'professor_id.required' => 'O professor é obrigatório.',
            'modalidade_id.required' => 'A modalidade é obrigatória.',
            // Você pode adicionar mensagens customizadas para os outros campos
        ];
    }
}
