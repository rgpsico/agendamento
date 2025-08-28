<div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-4 col-xl-3 theiaStickySidebar">
                    <!-- Search Filter -->
              <div class="card search-filter">
    <div class="card-header">
        <h4 class="card-title mb-0" style="color:#fff;">
            <i class="fas fa-filter me-2"></i>Filtros de Busca
        </h4>
    </div>
    <div class="card-body">
        <!-- Filtro por Nome da Escola -->
        <div class="filter-widget mb-4">
            <label class="form-label fw-bold">
                <i class="fas fa-building text-primary me-2"></i>Nome da Escola
            </label>
            <div class="input-icon position-relative">
                <i class="fas fa-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                <input 
                    type="text" 
                    class="form-control ps-5" 
                    id="nome_empresa" 
                    placeholder="Digite o nome da escola..."
                    style="border-radius: 8px; border: 1px solid #e0e0e0;">
            </div>
        </div>

        <!-- Filtro por Localização/Bairro -->
        <div class="filter-widget mb-4">
        <label class="form-label fw-bold">
            <i class="fas fa-map-marker-alt text-success me-2"></i>Localização
        </label>
        <div class="input-icon position-relative">
            <i class="fas fa-location-dot position-absolute" 
            style="left: 12px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
            <select class="form-select ps-5" id="bairro_filtro" style="border-radius: 8px; border: 1px solid #e0e0e0;">
                <option value="">Todos os bairros</option>
                @foreach($bairros as $bairro)
                    <option value="{{ $bairro->id }}">{{ $bairro->name }}</option>
                @endforeach
            </select>
        </div>
    </div>


        <!-- Filtro por Modalidade -->
        <div class="filter-widget mb-4">
            <label class="form-label fw-bold">
                <i class="fas fa-swimming-pool text-info me-2"></i>Modalidades
            </label>
            <div class="modalidades-container" style="max-height: 200px; overflow-y: auto; border: 1px solid #e0e0e0; border-radius: 8px; padding: 10px;">
                @if(isset($modalidade))
                    @foreach ($modalidade as $value)          
                        <div class="mb-2">
                            <label class="custom_check d-flex align-items-center" style="cursor: pointer;">
                                <input 
                                    type="checkbox" 
                                    name="modalidade_type" 
                                    value="{{$value->id}}" 
                                    data-type="{{$value->id}}" 
                                    class="filter_modalidade me-2"
                                    style="width: 18px; height: 18px;">
                                <span class="checkmark"></span> 
                                <span class="ms-2">{{$value->nome}}</span>
                            </label>
                        </div>
                    @endforeach
                @else
                    <div class="text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        Nenhuma modalidade disponível
                    </div>
                @endif
            </div>
        </div>

        <!-- Filtros Adicionais -->
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label fw-bold">
                    <i class="fas fa-star text-warning me-2"></i>Avaliação Mínima
                </label>
                <select class="form-select" id="avaliacao_filtro" style="border-radius: 8px; border: 1px solid #e0e0e0;">
                    <option value="">Qualquer avaliação</option>
                    <option value="5">⭐⭐⭐⭐⭐ (5 estrelas)</option>
                    <option value="4">⭐⭐⭐⭐ (4+ estrelas)</option>
                    <option value="3">⭐⭐⭐ (3+ estrelas)</option>
                    <option value="2">⭐⭐ (2+ estrelas)</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">
                    <i class="fas fa-dollar-sign text-success me-2"></i>Faixa de Preço
                </label>
                <select class="form-select" id="preco_filtro" style="border-radius: 8px; border: 1px solid #e0e0e0;">
                    <option value="">Qualquer preço</option>
                    <option value="0-100">Até R$ 100</option>
                    <option value="100-200">R$ 100 - R$ 200</option>
                    <option value="200-300">R$ 200 - R$ 300</option>
                    <option value="300-500">R$ 300 - R$ 500</option>
                    <option value="500+">Acima de R$ 500</option>
                </select>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary flex-fill" id="buscar_empresa">
                <i class="fas fa-search me-2"></i>Buscar Escolas
            </button>
            <button type="button" class="btn btn-outline-secondary" id="limpar_filtros">
                <i class="fas fa-eraser me-2"></i>Limpar
            </button>
        </div>
    </div>
</div>
@include('public.home._partials.stylefilter')
                    <!-- /Search Filter -->
               
            
                
                