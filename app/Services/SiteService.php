<?php

namespace App\Services;

use App\Models\Modalidade;
use App\Models\EmpresaSite;
use App\Models\SiteServico;
use App\Models\SiteTemplate;
use Illuminate\Support\Str;

class SiteService
{
    public function criarSiteAutomatico(int $empresa_id, int $modalidade_id, string $tipo_agendamento = 'dia')
    {
        $modalidade = Modalidade::find($modalidade_id);
        if (!$modalidade) {
            return null;
        }

        // 1️⃣ Gerar slug único
        $slug = Str::slug($modalidade->nome);
        $contador = 1;
        $slugOriginal = $slug;

        while (EmpresaSite::where('slug', $slug)->exists()) {
            $slug = $slugOriginal . '-' . $contador;
            $contador++;
        }

        // 2️⃣ Pegar os padrões do config/site_padrao.php
        $config = config('site_padrao');
        $padrao = $config[$modalidade->nome] ?? null;
        if (!$padrao) {
            return null;
        }

        // 3️⃣ Pegar primeiro template disponível
        $template = SiteTemplate::first();

        // 4️⃣ Criar o site da empresa
        $site = EmpresaSite::create(array_merge($padrao['site'], [
            'empresa_id'  => $empresa_id,
            'template_id' => $template->id ?? null,
            'slug'        => $slug,  // garantir slug único
        ]));

        // 5️⃣ Criar serviços vinculados ao site
        foreach ($padrao['servicos'] as $servicoPadrao) {
            SiteServico::create([
                'site_id'   => $site->id,
                'titulo'    => $servicoPadrao['titulo'] ?? 'Serviço Padrão',
                'descricao' => $servicoPadrao['descricao'] ?? null,
                'preco'     => $servicoPadrao['preco'] ?? 0,
                'imagem'    => $servicoPadrao['imagem'] ?? 'imagens/default.jpg',
            ]);
        }

        return $site;
    }
}
