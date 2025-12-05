<style>
    .bbicon{
    display: inline-block;
    width: 24px;
    height: 24px;
    background-image: url({{asset('admin/img/bbicon.png')}});
 }
</style>
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Administrativo Pet</span>
                </li>
            
                <li> 
                    <a href="{{route('admin.dashboard')}}">
                        <i class="fe fe-home"></i> 
                        <span>Painel Pet</span>
                    </a>
                </li>

                <li class="submenu">
                    <a href="" class="">
                        <i class="fe bbicon"></i>
                        <span> Especialidades Pet</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul style="display: none;">
                        <li><a href="{{route('modalidade.index')}}">Listar Especialidades</a></li>
                        <li><a href="{{route('modalidade.create')}}">Cadastrar Especialidade</a></li>
                  
                    </ul>
                </li>
            
                <li class="submenu">
                    <a href="" class="">
                        <i class="fe fe-activity"></i>
                        <span> Configuração Petshop</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul style="display: none;">
                        <li><a href="">Sistema Pet</a></li>
                    </ul>
                </li>

            </ul>
                
                
    </div>
</div>

