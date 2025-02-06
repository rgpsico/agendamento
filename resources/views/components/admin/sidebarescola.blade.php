<head>
    <!-- Outros links e scripts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

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

                @isset(Auth::user()->professor->id)
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
                        <li> 
                            <a href="{{ route('empresa.pagamento.store') }}">
                               <i class="fe fe-credit-card"></i> <span> Tipos de Pagamento</span>
                            </a>
                        </li>
                        @endisset
                    </ul>
                </li>

                <!-- Pagamentos -->
                <li class="submenu">
                    <a href="#">
                        <i class="fas fa-credit-card" style="font-size: 18px;"></i> 
                        <span>Pagamentos</span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('pagamento.index') }}">Listar</a></li>
                        <li><a href="{{ route('empresa.pagamento.create') }}">Cadastrar</a></li>
                    </ul>
                </li>

                @if(Auth::user()->isAdmin)
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
                        <li><a href="{{ route('configuracoes.usuarios') }}">Usuários</a></li>
                        <li><a href="{{ route('configuracoes.index') }}">Sistema</a></li>
                        @if(Auth::user()->isAdmin)
                        <li><a href="{{ route('configuracoes.indexAdmin') }}">Sistema Geral</a></li>
                        @endif
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
