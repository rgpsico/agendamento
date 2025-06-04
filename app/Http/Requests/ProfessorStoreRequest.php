<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfessorStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true; // Permitir que qualquer usuário envie esta requisição
    }

    public function rules()
    {
        return [
            'avatar' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:2048',
            'nome' => 'required|max:255',
            'descricao' => 'required',
            'telefone' => 'required',
            'cnpj' => 'required|unique:empresa,cnpj',
            'valor_aula_de' => 'required',
            'valor_aula_ate' => 'required',
            'modalidade_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'descricao.required' => 'A descrição é obrigatória.',
            'telefone.required' => 'O telefone é obrigatório.',
            'cnpj.required' => 'O CNPJ é obrigatório.',
            'cnpj.unique' => 'Já existe uma empresa com este CNPJ.',
            'valor_aula_de.required' => 'O valor inicial da aula é obrigatório.',
            'valor_aula_ate.required' => 'O valor final da aula é obrigatório.',
            'modalidade_id.required' => 'A modalidade é obrigatória.',
            'avatar.image' => 'O avatar deve ser uma imagem.',
            'avatar.max' => 'O avatar não pode ser maior que 2MB.',
            'banner.image' => 'O banner deve ser uma imagem.',
            'banner.max' => 'O banner não pode ser maior que 2MB.',
        ];
    }
}
