<head>
    <!-- Outros links e scripts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .sidebar-disabled {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
                <ul>
                    <!-- Seção: Menu -->
                    <li class="menu-title"><span>Menu</span></li>

                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('cliente.dashboard') }}">
                            <i class="fe fe-home"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    @isset(Auth::user()->empresa->user_id)
                        <li class="submenu">
                            <a href="#">
                                <i class="fas fa-globe" style="font-size: 18px;"></i>
                                <span>Site</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('admin.site.configuracoes') }}">Configurações do Site</a></li>
                                <li><a href="{{ route('admin.site.servicos.index') }}">Serviços</a></li>
                                <li><a href="{{ route('admin.site.depoimentos.index') }}">Depoimentos</a></li>
                                <li><a href="{{ route('admin.site.menu.index') }}">Menu</a></li>
                                <li><a href="{{ route('admin.site.contatos.index') }}">Contatos</a></li>
                                <li><a href="{{ route('admin.site.dominios.index') }}">Domínios e SSL</a></li>
                            </ul>
                        </li>
                    @endisset

                    @isset(Auth::user()->empresa->user_id)
                        <!-- Serviços -->
                        <li class="submenu">
                            <a href="#">
                                <i class="fe fe-activity"></i>
                                <span>Serviços</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('admin.servico.index') }}">Listar Serviços</a></li>
                                <li><a href="{{ route('admin.servico.create') }}">Cadastrar Serviço</a></li>
                            </ul>
                        </li>
                    @endisset

                    @isset(Auth::user()->empresa->user_id)
                        <!-- Horários -->
                        <li class="submenu">
                            <a href="#">
                                <i class="fas fa-clock" style="font-size: 18px;"></i>
                                <span>Horários</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('empresa.disponibilidade') }}">Únicos</a></li>
                                <li><a href="{{ route('empresa.disponibilidadePersonalizada') }}">Personalizado</a></li>
                                <li><a href="{{ route('empresa.horarios.auto') }}">Automaticos</a></li>
                            </ul>
                        </li>

                        <!-- Alunos -->
                        <li>
                            <a href="{{ route('alunos.index') }}">
                                <i class="fe fe-users"></i><span>Alunos</span>
                            </a>
                        </li>
                    @endisset

                    <!-- Agenda -->
                    <li class="submenu">
                        <a href="#">
                            <i class="fe fe-calendar"></i>
                            <span>Agenda</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('agenda.index') }}">Listar Aulas</a></li>
                            <li><a href="{{ route('agenda.calendario') }}">Calendário</a></li>
                        </ul>
                    </li>

                    @isset(Auth::user()->empresa->id)
                        <!-- Fotos -->
                        <li>
                            <a href="{{ route('empresa.fotos', ['userId' => Auth::user()->id]) }}">
                                <i class="fe fe-camera"></i>
                                <span>Fotos</span>
                            </a>
                        </li>
                    @endisset

                    <!-- Dados Cadastrais -->
                    <li class="submenu">
                        <a href="#">
                            <i class="fe fe-file"></i>
                            <span>Dados Cadastrais</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ route('empresa.configuracao', ['userId' => Auth::user()->id]) }}">
                                    <i class="fe fe-briefcase"></i> <span> Empresa</span>
                                </a>
                            </li>
                            @isset(Auth::user()->empresa->user_id)
                                <li>
                                    <a href="{{ route('empresa.endereco', ['userId' => Auth::user()->id]) }}">
                                        <i class="fe fe-map-pin"></i> <span> Endereço</span>
                                    </a>
                                </li>
                                <li><a href="{{ route('configuracoes.indexAdmin') }}">Sistema Geral</a></li>
                            @endisset
                        </ul>
                    </li>

                    <!-- Pagamentos -->
                    @isset(Auth::user()->empresa->user_id)
                        <li class="submenu">
                            <a href="#">
                                <i class="fas fa-credit-card" style="font-size: 18px;"></i>
                                <span>Pagamentos</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('pagamento.index') }}">Listar</a></li>
                                <li><a href="{{ route('empresa.pagamento.create') }}">Cadastrar</a></li>
                                <li><a href="{{ route('empresa.pagamento.config.index') }}">Configurações</a></li>
                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="#">
                                <i class="fas fa-credit-card" style="font-size: 18px;"></i>
                                <span>Integrações</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('integracao.assas.escola') }}">Asaas</a></li>
                                <li><a href="{{ route('integracao.assas.pix') }}">PIX</a></li>
                                <li><a href="{{ route('integracoes.stripe') }}">Stripe</a></li>
                                <li><a href="{{ route('integracoes.mercadopago') }}">Mercado Pago</a></li>
                                <li><a href="{{ route('integracoes.configuracoes') }}">Configurações Gerais</a></li>
                                <li><a href="{{ route('integracoes.relatorios') }}">Relatórios de Pagamentos</a></li>
                            </ul>
                        </li>
                    @endisset
                    @if (Auth::user()->isAdmin)
                        <!-- Gestão de Empresas -->
                        <li class="submenu">
                            <a href="#">
                                <i class="fas fa-building"></i>
                                <span>Empresas</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('empresa.index') }}">Listar</a></li>
                                <li><a href="#">Cadastrar</a></li>
                            </ul>
                        </li>

                        <!-- Modalidades -->
                        <li class="submenu">
                            <a href="#">
                                <i class="fas fa-swimmer"></i>
                                <span>Modalidades</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('modalidade.index') }}">Listar</a></li>
                                <li><a href="{{ route('modalidade.create') }}">Cadastrar</a></li>
                            </ul>
                        </li>

                        <!-- Configurações -->
                        <li class="submenu">
                            <a href="#">
                                <i class="fas fa-cogs" style="font-size: 18px;"></i>
                                <span>Configurações</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('configuracoes.permissoes') }}">Permissões</a></li>
                                <li><a href="{{ route('configuracoes.pagamentos') }}">Pagamentos</a></li>
                                <li><a href="{{ route('configuracoes.empresa') }}">Empresa</a></li>
                                <li><a href="{{ route('admin.usuarios.index') }}">Usuários</a></li>
                                <li><a href="{{ route('configuracoes.index') }}">Sistema</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Modal de Empresa Inativa -->


    <!-- Script para verificar o status da empresa e bloquear a sidebar -->

</body>
