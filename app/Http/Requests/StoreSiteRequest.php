<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Se você quer permitir apenas usuários logados, pode colocar uma verificação aqui
        return true;
    }

    public function rules(): array
    {
        dd($this->all());
        return [
            'template_id' => 'required|exists:site_templates,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'capa' => 'nullable|image|max:2048',
            'cores.primaria' => 'required|string',
            'cores.secundaria' => 'required|string',
            'sobre_titulo' => 'nullable|string|max:255',
            'sobre_descricao' => 'nullable|string',
            'whatsapp' => 'nullable|string|max:20',
            'autoatendimento_ia' => 'boolean',
            'sobre_imagem' => 'nullable|image|max:2048',
            'sobre_itens.*.icone' => 'nullable|string|max:255',
            'sobre_itens.*.titulo' => 'nullable|string|max:255',
            'sobre_itens.*.descricao' => 'nullable|string',
            'servicos.*.titulo' => 'nullable|string|max:255',
            'servicos.*.descricao' => 'nullable|string',
            'servicos.*.preco' => 'nullable|numeric|min:0',
            'servicos.*.imagem' => 'nullable|image|max:2048',
            'depoimentos.*.nome' => 'nullable|string|max:255',
            'depoimentos.*.nota' => 'nullable|integer|min:0|max:5',
            'depoimentos.*.comentario' => 'nullable|string',
            'depoimentos.*.foto' => 'nullable|image|max:2048',
            'tracking_codes.*.name' => 'nullable|string|max:255',
            'tracking_codes.*.provider' => 'nullable|string|max:255',
            'tracking_codes.*.code' => 'nullable|string|max:255',
            'tracking_codes.*.type' => 'nullable|in:analytics,ads,pixel,other',
            'tracking_codes.*.script' => 'nullable|string',
            'tracking_codes.*.status' => 'boolean',
            'dominio_personalizado' => 'nullable|string|max:255',
            'gerar_vhost' => 'boolean',
        ];
    }
}
