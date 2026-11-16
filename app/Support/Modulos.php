<?php

namespace App\Support;

use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Http\Request;

/**
 * Áreas do painel que o dono do estúdio libera (ou não) para cada funcionário.
 *
 * - Master (config/master_users.php) e dono de empresa acessam tudo.
 * - Funcionário (professor de uma empresa que não é dono) acessa só as áreas liberadas,
 *   guardadas como permissões Spatie "modulo.<chave>" no usuário.
 * - Rotas fora de qualquer área (perfil próprio, sair, páginas públicas...) continuam liberadas.
 */
class Modulos
{
    /** Liberadas por padrão para quem já era funcionário antes desta regra existir. */
    public const PADRAO_FUNCIONARIO = ['agenda', 'clientes'];

    /**
     * chave => [rótulo, descrição, prefixos de nome de rota, prefixos de URI da API]
     */
    public const AREAS = [
        'agenda' => [
            'Agenda e horários',
            'Ver e marcar aulas/sessões, calendário e horários disponíveis.',
            ['agenda.', 'agendamento.', 'aulas.', 'dias.', 'disponibilidade.', 'empresa.agenda.', 'empresa.disponibilidade',
                'empresa.horarios.', 'configurar.horarios', 'salvar.horarios', 'horario.gerar', 'admin.agendamentos.'],
            [],
        ],
        'clientes' => [
            'Clientes',
            'Lista de alunos/pacientes, cadastro e ficha.',
            ['alunos.index', 'alunos.create', 'alunos.store', 'alunos.show', 'alunos.update', 'alunos.destroy'],
            [],
        ],
        'financeiro' => [
            'Financeiro',
            'Receitas, despesas, dashboard financeiro, relatórios, planos e pagamentos.',
            ['financeiro.', 'admin.financeiro.', 'despesas.', 'alunos.planos.', 'planos-alunos.', 'pagamento.index',
                'empresa.pagamento.'],
            ['api/receitas', 'api/receita/', 'api/despesas', 'api/despesas-recorrente', 'api/receitas-recorren', 'api/financeiro-categorias'],
        ],
        'servicos' => [
            'Serviços',
            'Cadastrar e editar serviços (Pilates, Fisioterapia...) e preços.',
            ['admin.servico.', 'servicos.'],
            [],
        ],
        'site' => [
            'Site e redes sociais',
            'Site do estúdio, artigos, depoimentos, domínio e redes sociais.',
            ['admin.site.', 'virtualhosts.', 'siteservicos.', 'admin.social.', 'admin.gerarSSL', 'site-templates.'],
            [],
        ],
        'crm' => [
            'CRM e leads',
            'Leads, funil de vendas, campanhas e formulários.',
            ['crm.', 'admin.leads.'],
            [],
        ],
        'ia' => [
            'Atendimento com IA',
            'Configurar o bot e ver as conversas.',
            ['admin.bot.', 'admin.botservice.', 'admin.conversations.'],
            [],
        ],
        'configuracoes' => [
            'Dados da empresa e integrações',
            'Dados cadastrais, endereço, fotos, plano da empresa e integrações de pagamento (Asaas, Pix, Stripe, Mercado Pago).',
            ['empresa.configuracao', 'empresa.endereco', 'empresa.update', 'empresa.fotos', 'empresa.upload', 'empresa.edit',
                'empresa.profile', 'configuracoes.empresa', 'configuracoes.index', 'configuracoes.salvar', 'admin.empresas.planos',
                'integracoes.', 'integracao.', 'configuracoes.pagamentos', 'empresa.tipopagamento.', 'empresa.pagamento.config'],
            [],
        ],
        'usuarios' => [
            'Usuários e permissões',
            'Cadastrar funcionários e definir o que cada um acessa.',
            ['admin.usuarios.', 'users.', 'professor.'],
            [],
        ],
    ];

    /** @var array<int, bool> */
    private static array $acessoTotal = [];

    public static function permissao(string $modulo): string
    {
        return 'modulo.' . $modulo;
    }

    /** Dono de empresa ou master: acessa todas as áreas. */
    public static function acessoTotal(Usuario $usuario): bool
    {
        return self::$acessoTotal[$usuario->id] ??= $usuario->isMasterUser()
            || Empresa::where('user_id', $usuario->id)->exists();
    }

    /** Funcionário: professor de alguma empresa que não é dono de nenhuma. */
    public static function ehFuncionario(Usuario $usuario): bool
    {
        return ! self::acessoTotal($usuario) && $usuario->professor()->whereNotNull('empresa_id')->exists();
    }

    public static function pode(Usuario $usuario, string $modulo): bool
    {
        if (! self::ehFuncionario($usuario)) {
            return true;
        }


        return $usuario->getAllPermissions()->contains('name', self::permissao($modulo));
    }

    /** Área a que pertence a requisição (pelo nome da rota ou, na API, pela URI); null = fora de qualquer área. */
    public static function daRequisicao(Request $request): ?string
    {
        $nome = $request->route()?->getName() ?? '';
        $uri = ltrim($request->path(), '/');

        // Vale o prefixo mais específico (ex.: 'empresa.pagamento.config' > 'empresa.pagamento.').
        $melhor = null;
        $tamanho = 0;
        foreach (self::AREAS as $chave => [, , $rotas, $apis]) {
            foreach ($rotas as $prefixo) {
                if ($nome !== '' && str_starts_with($nome, $prefixo) && strlen($prefixo) > $tamanho) {
                    [$melhor, $tamanho] = [$chave, strlen($prefixo)];
                }
            }
            foreach ($apis as $prefixo) {
                if (str_starts_with($uri, $prefixo) && strlen($prefixo) > $tamanho) {
                    [$melhor, $tamanho] = [$chave, strlen($prefixo)];
                }
            }
        }

        return $melhor;
    }
}
