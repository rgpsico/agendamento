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
                    <span>Menu</span>
                </li>
            
                <li> 
                    <a href="">
                        <i class="fe fe-home"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li class="submenu">
                    <a href="" class="">
                        <i class="fe bbicon"></i>
                        <span> Modalidade</span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <ul style="display: none;">
                        <li><a href="">Cadastrar</a></li>
                  
                    </ul>
                </li>
            
                <li class="submenu">
                    <a href="" class="">
                        <i class="fe fe-activity"></i>
                        <span> Configuração</span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <ul style="display: none;">
                        <li><a href="">Sistema</a></li>
                    </ul>
                </li>

            </ul>
                
                
    </div>
</div>

