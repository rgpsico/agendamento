
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title"> 
                    <span>Menu Admin</span>
                </li>
            
                <li> 
                    <a href="{{route('escola.dashboard')}}">
                        <i class="fe fe-home"></i> 
                        <span>Escola</span>
                    </a>
                </li>
            
                <li class="submenu">
                    <a href="" class="">
                        <i class="fe fe-activity"></i>
                        <span> sss</span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <ul style="display: none;">
                        <li><a href="{{route('admin.servico.index')}}">Listar Serviços</a></li>
                        <li><a href="{{route('admin.servico.create')}}">Cadastrar Serviços</a></li>
                    </ul>
                </li>
            
                <li> 
                    <a href="{{route('alunos.index')}}">
                        <i class="fe fe-users"></i><span>Alunos</span>
                    </a>
                </li>
                <li> 
                    <a href="{{route('agenda.index')}}">
                        <i class="fe fe-calendar"></i><span>Agenda</span>
                    </a>
                </li>    
            
                <li> 
                    <a href="{{route('empresa.fotos',['userId' => Auth::user()->id])}}">
                        <i class="fe fe-camera"></i>
                        <span>Fotos</span>
                    </a>
                </li>
                
                <li> 
                    <a href="{{route('empresa.configuracao',['userId' => Auth::user()->id])}}">
                        <i class="fe fe-vector"></i>
                        <span>Configuração</span>
                    </a>
                </li>  
            
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
                        <li> 
                            <a href="{{route('empresa.endereco',['userId' => Auth::user()->id])}}">
                               <i class="fe fe-map-pin"></i><span> Endereço</span>
                            </a>
                        </li>
                    </div>
    </div>
</div>