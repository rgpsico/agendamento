<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Coloque sua lógica de autorização, se quiser checar permissões
        return true;
    }

    public function rules(): array
    {
        return [
            'empresa_id' => 'required|exists:empresa,id',
            'avatar' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:2048',
            'nome' => 'required|max:255',
            'site_url' => 'nullable|url|max:255',
            'descricao' => 'required',
            'telefone' => 'required|max:20',
            'cnpj' => 'required|max:18',
            'valor_aula_de' => 'required|numeric|min:0',
            'valor_aula_ate' => 'required|numeric|min:0',
            'modalidade_id' => 'required|exists:modalidade,id',
            'cep' => 'required',
            'endereco' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'uf' => 'required',
            'pais' => 'required',
            'data_vencimento' => 'nullable|date',
            'bairros' => 'nullable|array|max:5',
            'bairros.*' => 'exists:loc_bairros,id',
        ];
    }
}
