@if(1 = 2 )
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title"> 
                    <span>Menu</span>
                </li>

                <li> 
                    <a href="{{route('cliente.dashboard')}}">
                        <i class="fe fe-home"></i> 
                        <span>Dashboard</span>
                    </a>
                </li>

                <li> 
                    <a href="{{route('cliente.dashboard')}}">
                        <i class="fe fe-home"></i> 
                        <span>Escolas de Surf</span>
                    </a>
                </li>

                <li> 
                    <a href="{{route('cliente.dashboard')}}">
                        <i class="fe fe-home"></i> 
                        <span>Modalidades</span>
                    </a>
                </li>

                <li> 
                    <a href="{{route('cliente.dashboard')}}">
                        <i class="fe fe-home"></i> 
                        <span>Configuração Sistema</span>
                    </a>
                </li>



                
                
            </ul>
        </div>
    </div>
</div>
@endif

<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title"> 
                    <span>Menu</span>
                </li>

                <li> 
                    <a href="{{route('cliente.dashboard')}}">
                        <i class="fe fe-home"></i> <span>Dashboard</span>
                    </a>
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
                    <a href="">
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