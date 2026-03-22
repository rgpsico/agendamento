<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteTemplate;

class SiteTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'titulo' => 'Público',
                'slug' => 'publico',
                'descricao' => 'Template padrão público',
                'preview_image' => 'templates/publico.png',
                'path_view' => 'site.publico',
            ],
            [
                'titulo' => 'Centralizado',
                'slug' => 'centralizado',
                'descricao' => 'Template centralizado',
                'preview_image' => 'templates/centralizado.png',
                'path_view' => 'site.centralizado',
            ],
            [
                'titulo' => 'Vendas',
                'slug' => 'vendas',
                'descricao' => 'Template focado em vendas',
                'preview_image' => 'templates/vendas.png',
                'path_view' => 'site.vendas',
            ],
            [
                'titulo' => 'Boxe',
                'slug' => 'boxe',
                'descricao' => 'Template esportivo de boxe',
                'preview_image' => 'templates/boxe.png',
                'path_view' => 'site.boxe',
            ],
            [
                'titulo' => 'Minimalista',
                'slug' => 'minimalista',
                'descricao' => 'Template escuro e sofisticado focado em conversão',
                'preview_image' => 'templates/minimalista.png',
                'path_view' => 'site.minimalista',
            ],
            [
                'titulo' => 'Novo Preview',
                'slug' => 'novo-preview',
                'descricao' => 'Template de preview moderno com animações GSAP',
                'preview_image' => 'templates/novo-preview.png',
                'path_view' => 'site.novo_preview',
            ],
        ];

        foreach ($templates as $template) {
            SiteTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
