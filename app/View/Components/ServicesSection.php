<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

class ServicesSection extends Component
{
    public $site;
    public $modalidade;
    public $servicos;

    public function __construct($site, $modalidade = null)
    {
        $this->site = $site;
        $this->modalidade = strtolower($modalidade ?? 'geral');

        // Se existir serviços no site
        if ($site->servicos && $site->servicos->count()) {
            $this->servicos = $site->servicos;
        } else {
            $this->servicos = collect($this->getFakeServices($this->modalidade));
        }
    }

    private function getFakeServices($modalidade): array
    {
        $base = [
            'surf' => [
                [
                    'titulo' => 'Aula de Surf',
                    'descricao' => 'Aprenda as técnicas básicas e avançadas do surf.',
                    'preco' => 120.00,
                    'icone' => 'fa-water',
                    'features' => ['Prancha incluída', 'Instrutor certificado', '2h de aula'],
                    'destaque' => true,
                    'imagem' => 'https://picsum.photos/500/300?random=11'
                ],
                [
                    'titulo' => 'Treino de Ondas',
                    'descricao' => 'Sessão focada em performance e leitura do mar.',
                    'preco' => 180.00,
                    'icone' => 'fa-wave-square',
                    'features' => ['Filmagem', 'Análise técnica', 'Feedback personalizado'],
                    'destaque' => false,
                    'imagem' => 'https://picsum.photos/500/300?random=12'
                ],
                [
                    'titulo' => 'Treino de Ondas',
                    'descricao' => 'Sessão focada em performance e leitura do mar.',
                    'preco' => 180.00,
                    'icone' => 'fa-wave-square',
                    'features' => ['Filmagem', 'Análise técnica', 'Feedback personalizado'],
                    'destaque' => false,
                    'imagem' => 'https://picsum.photos/500/300?random=12'
                ]
            ],
            'kitesurf' => [
                [
                    'titulo' => 'Aula de Kitesurf',
                    'descricao' => 'Controle da pipa e navegação sobre a água.',
                    'preco' => 200.00,
                    'icone' => 'fa-flag',
                    'features' => ['Equipamento incluso', '3h de aula', 'Instrutor experiente'],
                    'destaque' => true,
                    'imagem' => 'https://picsum.photos/500/300?random=21'
                ]
            ],
            'geral' => [
                [
                    'titulo' => 'Aula Individual',
                    'descricao' => 'Instrutor dedicado exclusivamente para você.',
                    'preco' => 150.00,
                    'icone' => 'fa-user',
                    'features' => ['Equipamento incluído', '2 horas', 'Teoria e prática'],
                    'destaque' => false,
                    'imagem' => 'https://picsum.photos/500/300?random=1'
                ],
                [
                    'titulo' => 'Aula em Grupo',
                    'descricao' => 'Aprenda junto de outros iniciantes.',
                    'preco' => 80.00,
                    'icone' => 'fa-users',
                    'features' => ['Equipamento incluído', 'Máx 6 pessoas', '2 horas'],
                    'destaque' => false,
                    'imagem' => 'https://picsum.photos/500/300?random=2'
                ]
            ]
        ];

        return $base[$modalidade] ?? $base['geral'];
    }

    public function render()
    {
        return view('components.services-section');
    }
}
