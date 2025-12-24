<?php

namespace App\Services;

use App\Models\SiteArtigo;

class ArtigoTesteService
{
    public function normalizarDados(array $dados): array
    {
        if (!isset($dados['status']) || $dados['status'] === null || $dados['status'] === '') {
            $dados['status'] = SiteArtigo::STATUS_RASCUNHO;
        }

        return $dados;
    }

    public function exemploPayload(int $siteId = 1): array
    {
        return [
            'site_id' => $siteId,
            'titulo' => 'Artigo de teste',
            'resumo' => 'Resumo curto do artigo de teste.',
            'conteudo' => 'Conteudo completo do artigo de teste.',
            'status' => SiteArtigo::STATUS_RASCUNHO,
            'imagem_capa' => null,
        ];
    }
}
