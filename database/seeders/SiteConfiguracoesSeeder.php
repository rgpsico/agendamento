<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmpresaSite;
use App\Models\SiteConfiguracao;

class SiteConfiguracoesSeeder extends Seeder
{
    public function run()
    {
        // Para cada site existente
        $sites = EmpresaSite::all();

        foreach ($sites as $site) {
            $configs = [
                ['chave' => 'exibir_sobre', 'valor' => true],
                ['chave' => 'exibir_servicos', 'valor' => true],
                ['chave' => 'exibir_depoimentos', 'valor' => true],
                ['chave' => 'exibir_contatos', 'valor' => true],
                ['chave' => 'exibir_whatsapp', 'valor' => true],
                ['chave' => 'exibir_blog', 'valor' => false],
                ['chave' => 'layout_tipo', 'valor' => 'padrao'],
                ['chave' => 'cor_personalizada', 'valor' => [
                    'principal' => '#FF0000',
                    'secundaria' => '#00FF00',
                    'fundo' => '#FFFFFF'
                ]],
                ['chave' => 'scripts_extra', 'valor' => null],
            ];

            foreach ($configs as $config) {
                SiteConfiguracao::updateOrCreate(
                    ['site_id' => $site->id, 'chave' => $config['chave']],
                    ['valor' => $config['valor']]
                );
            }
        }
    }
}
