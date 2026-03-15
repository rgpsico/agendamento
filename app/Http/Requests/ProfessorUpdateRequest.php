<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfessorUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $empresaId = $this->input('empresa_id');


        return [
            // 'empresa_id' => 'required|exists:empresa,id',
            'avatar' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:2048',
            'nome' => 'required|max:255',
            //  'email' => 'required|email|max:255',
            'site_url' => 'nullable|url|max:255',
            'descricao' => 'required',
            'telefone' => 'required|max:20',
            // 'cnpj' => [
            //     'required',
            //     'max:18',
            //     Rule::unique('empresa', 'cnpj')->ignore($empresaId, 'id')
            // ],
            //  'data_vencimento' => 'required|date',
            'valor_aula_de' => 'required|numeric|min:0',
            'valor_aula_ate' => 'required|numeric|min:0',
            'modalidade_id' => 'required|exists:modalidade,id',
        ];
    }

    public function messages()
    {
        return [
            'empresa_id.required' => 'O ID da empresa é obrigatório.',
            'empresa_id.exists' => 'A empresa selecionada não existe.',
            'nome.required' => 'O nome é obrigatório.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ter um formato válido.',
            'email.max' => 'O email não pode ter mais de 255 caracteres.',
            'site_url.url' => 'A URL do site deve ter um formato válido.',
            'site_url.max' => 'A URL do site não pode ter mais de 255 caracteres.',
            'descricao.required' => 'A descrição é obrigatória.',
            'telefone.required' => 'O telefone é obrigatório.',
            'telefone.max' => 'O telefone não pode ter mais de 20 caracteres.',
            'cnpj.required' => 'O CPF/CNPJ é obrigatório.',
            'cnpj.max' => 'O CPF/CNPJ não pode ter mais de 18 caracteres.',
            'cnpj.unique' => 'Já existe uma empresa com este CPF/CNPJ.',
            'data_vencimento.required' => 'A data de vencimento é obrigatória.',
            'data_vencimento.date' => 'A data de vencimento deve ser uma data válida.',
            'valor_aula_de.required' => 'O valor inicial da aula é obrigatório.',
            'valor_aula_de.numeric' => 'O valor inicial da aula deve ser um número.',
            'valor_aula_de.min' => 'O valor inicial da aula deve ser maior ou igual a zero.',
            'valor_aula_ate.required' => 'O valor final da aula é obrigatório.',
            'valor_aula_ate.numeric' => 'O valor final da aula deve ser um número.',
            'valor_aula_ate.min' => 'O valor final da aula deve ser maior ou igual a zero.',
            'modalidade_id.required' => 'A modalidade é obrigatória.',
            'modalidade_id.exists' => 'A modalidade selecionada não existe.',
            'avatar.image' => 'O avatar deve ser uma imagem.',
            'avatar.max' => 'O avatar não pode ser maior que 2MB.',
            'banner.image' => 'O banner deve ser uma imagem.',
            'banner.max' => 'O banner não pode ser maior que 2MB.',
        ];
    }
}
