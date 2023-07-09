<div class="card search-filter">
    <div class="card-header">
        <h4 class="card-title mb-0">Filtro</h4>
    </div>
    <div class="card-body">
        <div class="filter-widget">
            <div class="input-icon">
                <i class="fas fa-building"></i> <!-- Ícone de empresa do Font Awesome -->
                <input type="text" class="form-control" id="nome_empresa" placeholder="Buscar Escola">
            </div>			
        </div>
    <div class="filter-widget">
        <h4>Modalidade</h4>      
        @php 
$modalidade = ['Surf', 'Bodyboard', 'Vôlei de Praia', 'Futebol de Areia', 'Frescobol', 'Kitesurf'];

        @endphp
        @isset($modalidade)
            @foreach ($modalidade as $value )          
                <div>
                    <label class="custom_check">
                        <input type="checkbox" name="gender_type" data-type="{{$value->nome}} " class="filter_empresa">
                        <span class="checkmark"></span> {{$value->nome}} 
                    </label>
            </div>
            @endforeach
        @endisset
    

    </div>
   
        <div class="btn-search">
            <button type="button" class="btn w-100 buscar_empresa" id="buscar_empresa">Buscar</button>
        </div>	
    </div>
</div>
<!-- /Search Filter -->