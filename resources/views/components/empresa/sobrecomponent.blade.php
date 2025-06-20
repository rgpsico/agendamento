<div class="tab-pane fade show active" id="per_details_tab">
    <!-- Personal Details -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-building me-2 text-primary"></i>Informações da Empresa
                        </h5>
                        <a class="btn btn-sm btn-primary" data-bs-toggle="modal" href="#edit_personal_details">
                            <i class="fa fa-edit me-1"></i>Editar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3 text-center mb-4 mb-md-0">
                            @if (!empty($empresa->avatar))
                                <img src="{{ asset('/avatar/' . $empresa->avatar) }}" class="img-fluid rounded-circle"
                                    style="width: 150px; height: 150px; object-fit: cover;" alt="Perfil da Empresa">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                    style="width: 150px; height: 150px; margin: 0 auto;">
                                    <i class="fa fa-building text-secondary" style="font-size: 60px;"></i>
                                </div>
                            @endif
                            <h5 class="mt-3">{{ $empresa->nome }}</h5>
                            <span class="badge bg-success">Ativa</span>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="info-group">
                                        <label class="text-muted small">Nome da Empresa</label>
                                        <p class="mb-0 fw-bold">{{ $empresa->nome ?? 'Não informado' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-group">
                                        <label class="text-muted small">CNPJ</label>
                                        <p class="mb-0 fw-bold">{{ $empresa->cnpj ?? 'Não informado' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-group">
                                        <label class="text-muted small">Data de Cadastro</label>
                                        <p class="mb-0 fw-bold">{{ $empresa->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-group">
                                        <label class="text-muted small">E-mail</label>
                                        <p class="mb-0 fw-bold">{{ $empresa->email ?? 'Não informado' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-group">
                                        <label class="text-muted small">Telefone</label>
                                        <p class="mb-0 fw-bold">{{ $empresa->telefone ?? 'Não informado' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-group">
                                        <label class="text-muted small">Modalidade</label>
                                        <p class="mb-0 fw-bold">{{ $empresa->modalidade->nome ?? 'Não informada' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Descrição -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold">
                                <i class="fa fa-info-circle me-2 text-primary"></i>Descrição
                            </h6>
                            <p>{{ $empresa->descricao ?? 'Nenhuma descrição disponível.' }}</p>
                        </div>
                    </div>

                    <!-- Valores -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold">
                                <i class="fa fa-money-bill me-2 text-primary"></i>Valores
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <span>Valor de:</span>
                                                <span class="fw-bold">R$
                                                    {{ number_format($empresa->valor_aula_de ?? 0, 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <span>Valor até:</span>
                                                <span class="fw-bold">R$
                                                    {{ number_format($empresa->valor_aula_ate ?? 0, 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Endereço -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold">
                                <i class="fa fa-map-marker-alt me-2 text-primary"></i>Localização
                            </h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="info-group">
                                                <label class="text-muted small">Endereço</label>
                                                <p class="mb-0">
                                                    {{ $empresa->endereco->endereco ?? 'Não informado' }},
                                                    {{ $empresa->endereco->numero ?? 'S/N' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="info-group">
                                                <label class="text-muted small">CEP</label>
                                                <p class="mb-0">{{ $empresa->endereco->cep ?? 'Não informado' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="info-group">
                                                <label class="text-muted small">Cidade/Estado</label>
                                                <p class="mb-0">{{ $empresa->endereco->cidade ?? 'Não informado' }} -
                                                    {{ $empresa->endereco->uf ?? '' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="info-group">
                                                <label class="text-muted small">País</label>
                                                <p class="mb-0">{{ $empresa->endereco->pais ?? 'Brasil' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <<<<<<< HEAD <!-- Mapa (opcional, se você tiver integração com Google Maps) -->
                                        @if (isset($empresa->endereco->latitude) && isset($empresa->endereco->longitude))
                                            <div class="mt-3">
                                                <div id="map" style="height: 200px; width: 100%;" class="rounded">
                                                </div>
                                            </div>
                                            =======

                                            <!-- Mapa baseado no CEP -->
                                            @if (!empty($empresa->endereco->cep))
                                                <div class="mt-3">
                                                    <div id="map" style="height: 200px; width: 100%;"
                                                        class="rounded"></div>
                                                </div>
                                                >>>>>>> e91fe1b373e8595ae1ace06384650af8086cdef2
                                            @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Personal Details -->
</div>

<style>
    .info-group {
        padding: 10px;
        border-radius: 5px;
    }

    .info-group label {
        margin-bottom: 2px;
        display: block;
    }

    .card {
        border-radius: 10px;
        overflow: hidden;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, .125);
        padding: 15px;
    }

    .badge {
        padding: 5px 10px;
        font-weight: normal;
    }
</style>

<<<<<<< HEAD <!-- Script para o mapa (opcional) -->
    @if (isset($empresa->endereco->latitude) && isset($empresa->endereco->longitude))
        <script>
            function initMap() {
                const location = {
                    lat: {{ $empresa->endereco->latitude }},
                    lng: {{ $empresa->endereco->longitude }}
                };
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 15,
                    center: location,
                });
                const marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: "{{ $empresa->nome }}"
                });
            }
        </script>
        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_DRIVE_CLIENT_ID') }}&callback=initMap"></script>

    @endif
