<?php

return [
    /*
     * Link de avaliação do Google Meu Negócio, por id do site (empresa_site.id).
     * Aparece como "Avalie no Google" no rodapé do modelo de passeios.
     * Para pegar o link: Google Meu Negócio > Pedir avaliações.
     */
    'avaliacao_google' => [
        11 => 'https://g.page/r/CVgozP3aw1IcEBM/review', // Rio by Jeff
    ],

    /*
     * Nome que aparece como remetente e no título do e-mail de contato, por id do site.
     * Sem entrada aqui, usa o título do site.
     */
    'marca_email' => [
        11 => 'RJ Passeios', // Rio by Jeff
    ],

    /*
     * Cópia do aviso do formulário de contato, por id do site (empresa_site.id).
     * Além do dono da empresa, esses e-mails também recebem cada contato do site.
     * Os endereços ficam no .env (fora do git), separados por vírgula.
     */
    'copia_contato' => [
        11 => array_filter(array_map('trim', explode(',', (string) env('SITE_RIOBYJEFF_COPIA_CONTATO', '')))), // Rio by Jeff
    ],
];
