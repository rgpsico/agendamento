<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title"> 
                    <span>Menu</span>
                </li>            
                <li> 
                    <a href="{{route('alunos.dashboard', ['id' => 1])}}">
                        <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
                    </a>
                </li>  
                <li> 
                    <a href="{{route('alunos.aulas',['id' => 1])}}">
                        <i class="fas fa-book"></i> <span>Aulas</span>
                    </a>
                </li> 
                <li> 
                    <a href="{{route('alunos.fotos', ['id' => 1])}}">
                        <i class="fas fa-camera"></i> <span>Fotos</span>
                    </a>
                </li> 
                <li> 
                    <a href="{{route('alunos.perfil', ['id' => 1])}}">
                        <i class="fas fa-user"></i> <span>Perfil</span>
                    </a>
                </li>
                  
                        
            </div>
          </div>
    </div> 
</div>

