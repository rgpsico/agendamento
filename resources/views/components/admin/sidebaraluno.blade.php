
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Menu Pet</span>
                </li>
            
                <li> 
                    <a href="{{route('cliente.dashboard')}}">
                        <i class="fe fe-home"></i> <span>Clínica Pet</span>
                    </a>
                </li>
            
                <li class="submenu">
                    <a href="" class="">
                        <i class="fe fe-activity"></i>
                        <span> Serviços Pet</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul style="display: none;">
                        <li><a href="{{route('admin.servico.index')}}">Listar Serviços Pet</a></li>
                        <li><a href="{{route('admin.servico.create')}}">Cadastrar Serviço Pet</a></li>
                    </ul>
                </li>
            
                <li> 
                    <a href="{{route('alunos.index')}}">
                        <i class="fe fe-users"></i><span>Pets & Tutores</span>
                    </a>
                </li>
                <li> 
                    <a href="{{route('agenda.index')}}">
                        <i class="fe fe-calendar"></i><span>Agenda Pet</span>
                    </a>
                </li>    
            
                <li> 
                    <a href="{{route('empresa.fotos',['userId' => Auth::user()->id])}}">
                        <i class="fe fe-camera"></i>
                        <span>Galeria Pet</span>
                    </a>
                </li>
                
                <li> 
                    <a href="{{route('empresa.configuracao',['userId' => Auth::user()->id])}}">
                        <i class="fe fe-vector"></i>
                        <span>Configuração Pet</span>
                    </a>
                </li>  
            
                <li class="submenu">
                    <a href="" class="">
                        <i class="fe fe-file"></i>
                        <span> Dados da Clínica Pet</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul style="display: none;">
                        <li> 
                            <a href="{{route('empresa.configuracao',['userId' => Auth::user()->id])}}">
                                <i class="fe fe-briefcase"></i><span> Clínica</span>
                            </a>
                        </li>  
                        <li> 
                            <a href="{{route('empresa.endereco',['userId' => Auth::user()->id])}}">
                               <i class="fe fe-map-pin"></i><span> Endereço da Clínica</span>
                            </a>
                        </li>
                    </div>
    </div>
</div>