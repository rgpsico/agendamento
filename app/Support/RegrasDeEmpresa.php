<?php

namespace App\Support;

use Illuminate\Routing\Router;

/**
 * Liga o middleware "dono" (ApenasDaPropriaEmpresa) as rotas do painel web que recebem o id de um registro.
 * Fica num so lugar em vez de espalhado pelas ~130 rotas: padrao da URI => tipo[,tabela].
 * Tipos: ver ApenasDaPropriaEmpresa. Aplica a todos os metodos (leitura tambem: tela de edicao mostra os dados).
 */
class RegrasDeEmpresa
{
    public const REGRAS = [
        // usuarios
        '#^admin/usuarios/\{(user|id)\}(/edit)?$#' => 'usuarios',
        '#^admin/usuarios/\{user\}/permissions$#' => 'master',
        '#^users/\{user\}(/edit)?$#' => 'usuarios',

        // area do cliente: {id} = empresa, {userId} = usuario
        '#^cliente/empresa/\{id\}/(show|edit|restore|destroy|agenda/update)$#' => 'empresas',
        '#^cliente/empresa/update/\{id\}$#' => 'empresas',
        '#^cliente/empresa/tipopagamento/\{empresaId\}$#' => 'empresas',
        '#^cliente/empresa/\{userId\}/(config|profile|endereco_empresa)$#' => 'usuarios',
        '#^cliente/empresa/pagamento/\{id\}/(edit|update)$#' => 'pagamento',
        '#^empresa/pagamento/\{id\}(/edit)?$#' => 'pagamento',
        '#^cliente/empresa/(configurar|salvar)-horarios/\{idServico\}$#' => 'servicos',
        '#^cliente/servicos/\{id\}/#' => 'servicos',
        '#^cliente/empresa/agenda/\{id\}/#' => 'agendamento',
        '#^cliente/empresa/alunos/\{id\}/#' => 'aluno',
        '#^cliente/empresa/modalidade/\{id\}/#' => 'master', // modalidade e global (compartilhada entre empresas)
        '#^admin/empresas/\{empresa\}/planos$#' => 'empresas',

        // financeiro
        '#^financeiro/despesas/\{id\}#' => 'direto,despesas',
        '#^financeiro/receitas/\{id\}#' => 'direto,receitas',
        '#^financeiro/despesas_recorrentes/\{despesaRecorrente\}#' => 'direto,despesas_recorrentes',
        '#^financeiro/receitas_recorrentes/\{receitaRecorrente\}#' => 'direto,receitas_recorrentes',

        // site da empresa
        '#^admin/site/(edit/\{idsite\}|\{idsite\}|\{site\}/(dominio|configuracoes)|configuracoes/\{site\}|\{site_id\}/tracking/store)$#' => 'direto,empresa_site',
        '#^admin/site/artigos/\{artigo\}#' => 'viasite,site_artigos',
        '#^admin/social/artigo/\{artigo\}$#' => 'viasite,site_artigos',
        '#^admin/site/contatos/\{contato\}#' => 'viasite,site_contatos',
        '#^admin/site/depoimentos/\{depoimento\}#' => 'viasite,site_depoimentos',
        '#^admin/site/servicos/\{servico\}#' => 'viasite,site_servicos',
        '#^admin/site/servicos/fotos/\{foto\}$#' => 'fotosite',
        '#^admin/tracking/\{id\}#' => 'viasite,tracking_codes',

        // bots
        '#^admin/bot/(edit|update|destroy)/\{id\}$#' => 'direto,bots',
        '#^admin/bot/\{bot\}(/chat)?$#' => 'direto,bots',
        '#^admin/botservice/\{id\}#' => 'viabot',
    ];

    public static function aplicar(Router $router): void
    {
        foreach ($router->getRoutes() as $route) {
            $uri = $route->uri();

            foreach (self::REGRAS as $padrao => $tipo) {
                if (preg_match($padrao, $uri)) {
                    $route->middleware('dono:' . $tipo);
                    break;
                }
            }
        }
    }
}
