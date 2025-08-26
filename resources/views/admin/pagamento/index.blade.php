<x-admin.layout title="Configurações de Pagamento">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">
            <!-- Page Header -->
            <x-header.titulo pageTitle="{{ $pageTitle }}" />
            <!-- /Page Header -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Gateway</th>
                                            <th>Chave API</th>
                                            <th>Métodos</th>
                                            <th>Tarifa</th>
                                            <th>Conta Split</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($model as $value)
                                        <tr>
                                            <td>{{ $value->id }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle"
                                                             src="{{ asset('admin/img/gateways/' . strtolower($value->name) . '.png') }}"
                                                             alt="Gateway Image">
                                                    </a>
                                                    <a href="#">{{ ucfirst($value->name) }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ Str::limit($value->api_key, 10, '...') }}</td>
                                            <td>{{ implode(', ', $value->methods ?? []) }}</td>
                                            <td>
                                                {{ $value->tariff_type == 'percentage' ? $value->tariff_value . '%' : 'R$ ' . number_format($value->tariff_value, 2, ',', '.') }}
                                            </td>
                                            <td>{{ $value->split_account }}</td>
                                            <td class="text-center">
                                                @if($value->status)
                                                    <span class="badge rounded-pill bg-success inv-badge">Ativo</span>
                                                @else
                                                    <span class="badge rounded-pill bg-danger inv-badge">Inativo</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <a class="btn btn-sm bg-success-light" href="{{ route('empresa.pagamento.edit', $value->id) }}">
                                                        <i class="fe fe-pencil"></i> Editar
                                                    </a>
                                                    <a href="javascript:void(0);" class="btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="#delete_modal_{{ $value->id }}">
                                                        <i class="fe fe-trash"></i> Excluir
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
        </div>
    </div>
</x-admin.layout>