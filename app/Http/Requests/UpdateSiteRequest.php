<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Aqui você pode implementar lógica de permissão
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'cores' => 'required|array',
            'cores.primaria' => 'required|string',
            'cores.secundaria' => 'required|string',
            'sobre_titulo' => 'nullable|string|max:255',
            'sobre_descricao' => 'nullable|string',
            'sobre_itens' => 'nullable|array',
            'sobre_itens.*.icone' => 'nullable|string|max:255',
            'sobre_itens.*.titulo' => 'nullable|string|max:255',
            'sobre_itens.*.descricao' => 'nullable|string',
            'whatsapp' => 'nullable|string|max:20',
            'template_id' => 'required|exists:site_templates,id',

            // Serviços
            'servicos' => 'nullable|array',
            'servicos.*.titulo' => 'required|string|max:255',
            'servicos.*.descricao' => 'nullable|string',
            'servicos.*.preco' => 'nullable|numeric',
            'servicos.*.imagem' => 'nullable|image|max:2048',

            // Depoimentos
            'depoimentos' => 'nullable|array',
            'depoimentos.*.nome' => 'required|string|max:255',
            'depoimentos.*.nota' => 'nullable|numeric|min:0|max:5',
            'depoimentos.*.comentario' => 'nullable|string',
            'depoimentos.*.foto' => 'nullable|image|max:2048',

            // Tracking codes
            'tracking_codes' => 'nullable|array',
            'tracking_codes.*.name' => 'required|string|max:100',
            'tracking_codes.*.provider' => 'required|string|max:50',
            'tracking_codes.*.code' => 'required|string|max:255',
            'tracking_codes.*.type' => 'required|in:analytics,ads,pixel,other',
            'tracking_codes.*.script' => 'nullable|string',
            'tracking_codes.*.status' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O título do site é obrigatório.',
            'cores.primaria.required' => 'A cor primária é obrigatória.',
            'cores.secundaria.required' => 'A cor secundária é obrigatória.',
            'servicos.*.titulo.required' => 'Cada serviço precisa ter um título.',
            'depoimentos.*.nome.required' => 'Cada depoimento precisa ter um nome.',
            'tracking_codes.*.name.required' => 'Cada código de rastreamento precisa ter um nome.',
        ];
    }
}
