<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title"> 
                    <span>Menu</span>
                </li>

                <li> 
                    <a href="{{route('dashboard')}}">
                        <i class="fe fe-home"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li> 
                    <a href="{{route('empresa.index')}}">
                        <i class="fe fe-layout"></i><span>Escolas</span>
                    </a>
                </li>
                

                <li> 
                    <a href="{{route('empresa.configuracao',['userId' => Auth::user()->id])}}">
                        <i class="fe fe-layout"></i><span>Configuração</span>
                    </a>
                </li>
               
                
            </ul>
        </div>
    </div>
</div>