<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title"> 
                    <span>Menu</span>
                </li>

                <li> 
                    <a href="{{route('escola.dashboard')}}">
                        <i class="fe fe-home"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li class="submenu">
                    <a href="" class="">
                        <i class="fe fe-document"></i> 
                        <span> Serviços</span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <ul style="display: none;">
                        <li><a href="{{route('admin.servico.index')}}">Listar Serviços</a></li>
                        <li><a href="{{route('admin.servico.create')}}">Cadastrar Serviços</a></li>
                    </ul>
                </li>

                <li> 
                    <a href="{{route('alunos.index')}}">
                        <i class="fe fe-layout"></i><span>Alunos</span>
                    </a>
                </li>
                <li> 
                    <a href="{{route('agenda.index')}}">
                        <i class="fe fe-layout"></i><span>Agenda</span>
                    </a>
                </li>    

                <li> 
                    <a href="{{route('empresa.fotos',['userId' => Auth::user()->id])}}">
                        <i class="fe fe-layout"></i><span>Fotos</span>
                    </a>
                </li>
                
                <li> 
                    <a href="{{route('empresa.configuracao',['userId' => Auth::user()->id])}}">
                        <i class="fe fe-layout"></i><span>Configuração</span>
                    </a>
                </li>  
                

                {{-- <li> 
                    <a href="{{route('empresa.configuracao')}}">
                        <i class="fe fe-layout"></i><span>Configuração</span>
                    </a>
                </li> --}}
               
                
            </ul>
        </div>
    </div>
</div>