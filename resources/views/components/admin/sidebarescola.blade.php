<head>
    <!-- Outros links e scripts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  </head>


<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title"> 
                    <span>Menu</span>
                </li>
            
                <li> 
                    <a href="{{route('cliente.dashboard')}}">
                        <i class="fe fe-home"></i> <span>Dashboard



                        </span>
                    </a>
                </li>
            
                @isset(Auth::user()->empresa->user_id)
                <li class="submenu">
                    <a href="" class="">
                        <i class="fe fe-activity"></i>
                        <span> Serviços</span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <ul style="display: none;">
                        <li><a href="{{route('admin.servico.index')}}">Listar Serviços</a></li>
                        <li><a href="{{route('admin.servico.create')}}">Cadastrar Serviços</a></li>
                    </ul>
                </li>
              
                @isset(Auth::user()->professor->id)
        
    <li class="submenu">
        <a href="" class="">
            <i class="fas fa-clock"></i>
            <span>Horários</span>
            <span class="menu-arrow"></span>
             {{-- Ícone do FontAwesome --}}
        </a>
        <ul style="display: none;">
            <li><a href="{{ route('empresa.disponibilidade') }}">Únicos</a></li>
            <li><a href="{{ route('empresa.disponibilidadePersonalizada') }}">Personalizado</a></li>
        </ul>
    </li>

                @endisset
                @endisset
                @isset(Auth::user()->professor->id)
                <li> 
                    <a href="{{route('alunos.index')}}">
                        <i class="fe fe-users"></i><span>Alunos</span>
                    </a>
                </li>
                @endisset

                
               

                <li class="submenu">
                    <a href="" class="">
                        <i class="fe fe-calendar"></i>
                        <span>Agenda</span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <ul style="display: none;">
                        <li><a href="{{route('agenda.index')}}">Listar Aulas</a></li>
                        <li><a href="{{route('agenda.calendario')}}">Calendario</a></li>
                    </ul>
                </li>
            
                @isset(Auth::user()->empresa->id)
                <li> 
                    <a href="{{route('empresa.fotos',['userId' => Auth::user()->id])}}">
                        <i class="fe fe-camera"></i>
                        <span>Fotos</span>
                    </a>
                </li>
                @endisset

                {{-- <li> 
                    <a href="{{route('treino')}}">
                        <i class="fe fe-camera"></i>
                        <span>Treino</span>
                    </a>
                </li> --}}
                
             

                @if(Auth::user()->isAdmin)
                    <li class="submenu">
                        <a href="" class="">
                            <i class="fas fa-swimmer mr-1" style=""></i> 
                            <span>  Esportes</span> 
                            <span class="menu-arrow"></span>
                        </a>
                        <ul style="display: none;">
                            <li><a href="{{route('modalidade.index')}}">Listar</a></li>
                            <li><a href="{{route('modalidade.create')}}">Cadastrar</a></li>
                    
                        </ul>
                    </li>
                @endif
            
                <li class="submenu">
                    <a href="" class="">
                        <i class="fe fe-file"></i>
                        <span> Dados Cadastrais</span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <ul style="display: none;">
                        <li> 
                            <a href="{{route('empresa.configuracao',['userId' => Auth::user()->id])}}">
                                <i class="fe fe-briefcase"></i><span> Empresa</span>
                            </a>
                        </li>  
                        @isset(Auth::user()->empresa->user_id)
                        <li> 
                            <a href="{{route('empresa.endereco',['userId' => Auth::user()->id])}}">
                               <i class="fe fe-map-pin"></i><span> Endereço</span>
                            </a>
                        </li>
                        <li> 
                            <a href="{{route('empresa.pagamento.store')}}">
                               <i class="fe fe-map-pin"></i><span>Tipos de Pagamento</span>
                            </a>
                        </li>
                    </ul>
                </li>
                        @endisset

                        <li class="submenu">
                            <a href="" class="">
                                <i class="fas fa-credit-card mr-2" style=""></i> 
                                <span>Pagamento</span> 
                                <span class="menu-arrow"></span>
                            </a>
                            <ul style="display: none;">
                                <li><a href="{{route('pagamento.index')}}">Listar</a></li>
                                <li><a href="{{route('empresa.pagamento.create')}}">Cadastrar</a></li>
                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="#">
                                <i class="fas fa-cogs mr-2"></i> 
                                <span> Configurações</span> 
                                <span class="menu-arrow"></span>
                            </a>
                            <ul style="display: none;">
                                <li><a href="{{ route('configuracoes.permissoes') }}">Permissões</a></li>
                                <li><a href="{{ route('configuracoes.pagamentos') }}">Pagamentos</a></li>
                                <li><a href="{{ route('configuracoes.empresa') }}">Empresa</a></li>
                                <li><a href="{{ route('configuracoes.usuarios') }}">Usuários</a></li>
                                <li><a href="{{ route('configuracoes.sistema') }}">Sistema</a></li>
                            </ul>
                        </li>
                        
                        
                    </div>
    </div>
</div> 

