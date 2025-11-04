<x-admin.layout title="{{ $pageTitle }}">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <x-header.titulo pageTitle="{{ $pageTitle }}" />

            <div class="row mb-4">
                <div class="col-lg-3 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Vínculos Ativos</p>
                                    <h4 class="mb-0">{{ $vinculos->where('status', '!=', 'Pendente')->count() }}</h4>
                                </div>
                                <div class="avatar avatar-md bg-success text-white rounded-3 d-flex align-items-center justify-content-center">
                                    <i class="fe fe-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Pagamentos Pendentes</p>
                                    <h4 class="mb-0">{{ $vinculos->where('status', 'Pendente')->count() }}</h4>
                                </div>
                                <div class="avatar avatar-md bg-warning text-white rounded-3 d-flex align-items-center justify-content-center">
                                    <i class="fe fe-alert-triangle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Faturamento Projetado</p>
                                    <h4 class="mb-0">R$ {{ number_format($vinculos->sum('valor'), 2, ',', '.') }}</h4>
                                </div>
                                <div class="avatar avatar-md bg-primary text-white rounded-3 d-flex align-items-center justify-content-center">
                                    <i class="fe fe-credit-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Próximas Faturas</p>
                                    <h4 class="mb-0">{{ $vinculos->count() }}</h4>
                                </div>
                                <div class="avatar avatar-md bg-info text-white rounded-3 d-flex align-items-center justify-content-center">
                                    <i class="fe fe-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fe fe-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar aluno, plano ou status" aria-label="Buscar vínculos">
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('admin.financeiro.vinculos.create') }}" class="btn btn-primary">
                        <i class="fe fe-plus"></i> Novo Pagamento / Vínculo
                    </a>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="datatable table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Aluno</th>
                                    <th>Plano</th>
                                    <th>Valor</th>
                                    <th>Método</th>
                                    <th>Status</th>
                                    <th>Pagamento</th>
                                    <th>Próxima Fatura</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vinculos as $vinculo)
                                    <tr>
                                        <td>{{ $vinculo['id'] }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm rounded-circle bg-light text-primary fw-bold me-2">{{ substr($vinculo['aluno'], 0, 1) }}</span>
                                                <div>
                                                    <p class="mb-0 fw-semibold">{{ $vinculo['aluno'] }}</p>
                                                    <small class="text-muted">{{ $vinculo['metodo'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $vinculo['plano'] }}</td>
                                        <td>R$ {{ number_format($vinculo['valor'], 2, ',', '.') }}</td>
                                        <td>{{ $vinculo['metodo'] }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match ($vinculo['status']) {
                                                    'Pago' => 'bg-success',
                                                    'Pendente' => 'bg-warning',
                                                    default => 'bg-info'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} text-uppercase">{{ $vinculo['status'] }}</span>
                                        </td>
                                        <td>{{ $vinculo['data_pagamento'] }}</td>
                                        <td>{{ $vinculo['proxima_fatura'] }}</td>
                                        <td class="text-end">
                                            <div class="actions">
                                                <a href="#" class="btn btn-sm bg-success-light me-1">
                                                    <i class="fe fe-eye"></i> Detalhes
                                                </a>
                                                <a href="#" class="btn btn-sm bg-info-light me-1">
                                                    <i class="fe fe-refresh-ccw"></i> Renovar
                                                </a>
                                                <a href="#" class="btn btn-sm bg-danger-light">
                                                    <i class="fe fe-slash"></i> Suspender
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
