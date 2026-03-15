<?php

namespace App\Services;

use App\Models\Modalidade;
use App\Models\Servicos;

class ServicoService
{
    public function criarServicoAutomatico(int $empresa_id, int $modalidade_id, string $tipo_agendamento = 'dia')
    {
        $modalidade = Modalidade::find($modalidade_id);

        if (!$modalidade) {
            return null;
        }

        $servicosPadrao = [
            'Surf' => [
                'titulo' => 'Aula de Surf',
                'descricao' => 'Aula básica de Surf',
                'preco' => 100,
                'tempo_de_aula' => 60,
                'imagem' => 'imagens/surf_padrao.jpg',
            ],
            'BodyBoard' => [
                'titulo' => 'Aula de BodyBoard',
                'descricao' => 'Aula básica de BodyBoard',
                'preco' => 80,
                'tempo_de_aula' => 60,
                'imagem' => 'imagens/bodyboard_padrao.jpg',
            ],
            'Passeios' => [
                'titulo' => 'Passeio Guiado',
                'descricao' => 'Passeio turístico guiado',
                'preco' => 120,
                'tempo_de_aula' => 120,
                'imagem' => 'imagens/passeio_padrao.jpg',
            ],
            'Corrida' => [
                'titulo' => 'Treino de Corrida',
                'descricao' => 'Treino básico de corrida',
                'preco' => 60,
                'tempo_de_aula' => 45,
                'imagem' => 'imagens/corrida_padrao.jpg',
            ],
            'Futevôlei' => [
                'titulo' => 'Aula de Futevôlei',
                'descricao' => 'Aula básica de Futevôlei',
                'preco' => 90,
                'tempo_de_aula' => 60,
                'imagem' => 'imagens/futevolei_padrao.jpg',
            ],
        ];

        $servico = $servicosPadrao[$modalidade->nome] ?? null;

        if ($servico) {
            return Servicos::create([
                'empresa_id' => $empresa_id,
                'imagem' => $servico['imagem'],
                'titulo' => $servico['titulo'],
                'descricao' => $servico['descricao'],
                'preco' => $servico['preco'],
                'tempo_de_aula' => $servico['tempo_de_aula'],
                'tipo_agendamento' => $tipo_agendamento,
            ]);
        }

        return null;
    }
}
