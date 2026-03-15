<?php

return [
    'Surf' => [
        'site' => [
            'template_id' => 1,
            'titulo' => 'Escola de Surf',
            'slug' => 'surf',
            'logo' => 'imagens/logo_surf.png',
            'capa' => 'imagens/capa_surf.jpg',
            'cores' => ['primaria' => '#1E90FF', 'secundaria' => '#FFD700'],
            'descricao' => 'A melhor escola de surf da cidade!',
            'ativo' => true,
        ],
        'servicos' => [
            [
                'titulo' => 'Aula de Surf',
                'descricao' => 'Aula básica de Surf',
                'preco' => 100,
                'tempo_de_aula' => 60,
                'imagem' => 'imagens/surf_padrao.jpg',
            ],
        ],
    ],
    'Boxe' => [
        'site' => [
            'template_id' => 2,
            'titulo' => 'Academia de Boxe',
            'slug' => 'boxe',
            'logo' => 'imagens/logo_boxe.png',
            'capa' => 'imagens/capa_boxe.jpg',
            'cores' => ['primaria' => '#FF4500', 'secundaria' => '#000000'],
            'descricao' => 'Treine boxe como um profissional!',
            'ativo' => true,
        ],
        'servicos' => [
            [
                'titulo' => 'Treino de Boxe',
                'descricao' => 'Aula de Boxe para iniciantes',
                'preco' => 120,
                'tempo_de_aula' => 60,
                'imagem' => 'imagens/boxe_padrao.jpg',
            ],
        ],
    ],
    // Adicione outras modalidades aqui
];
