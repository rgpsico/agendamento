<?php

/*
 * Redirecionamento permanente (301) de domínio antigo para domínio novo.
 * O caminho e os parâmetros do link são mantidos.
 *
 * 'dominio-antigo.com.br' => 'dominio-novo.com.br'
 *
 * O domínio antigo precisa continuar apontando para o servidor e cadastrado no
 * Nginx Proxy Manager (com SSL) para o redirecionamento funcionar.
 */
return [
    'dominios' => [
        'favelabyjeff.rjpasseios.com.br' => 'riobyjeff.com.br', // site do Jeff
    ],
];
