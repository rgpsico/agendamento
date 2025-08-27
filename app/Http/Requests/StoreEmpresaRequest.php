<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permite a validação
    }

    public function rules(): array
    {
        return [
            // Empresa
            'nome' => 'required|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email',
            'descricao' => 'required',
            'telefone' => 'required|max:20',
            'cnpj' => 'required|max:18',
            'valor_aula_de' => 'required|numeric|min:0',
            'valor_aula_ate' => 'required|numeric|min:0',
            'modalidade_id' => 'required|exists:modalidade,id',
            'user_id' => 'required|exists:usuarios,id',
            'avatar' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:2048',
            
            // Endereço
            'cep' => 'required',
            'endereco' => 'required',
            // 'numero' => 'required',
            // 'bairro' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'uf' => 'required',
            'pais' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este e-mail já está em uso.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Digite um e-mail válido.',
            'nome.required' => 'O nome da empresa é obrigatório.',
            'cnpj.required' => 'O CNPJ/CPF é obrigatório.',
        ];
    }
}
