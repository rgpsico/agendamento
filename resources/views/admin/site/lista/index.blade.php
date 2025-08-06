<x-admin.layout title="Sites da Empresa">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Meus Sites</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Admin</a></li>
                            <li class="breadcrumb-item active">Sites</li>
                        </ul>
                    </div>
                </div>
            </div>

                 @if($sites->count() < 3)
                    <div class="text-right mb-3">
                        <a href="{{ route('admin.site.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Novo Site
                        </a>
                    </div>
                @endif

            <x-alert/>

            <div class="card card-table">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-center mb-0">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Slug</th>
                                    <th>Criado em</th>
                                    <th>Visualizações</th>
                                    <th>Cliques WhatsApp</th>
                                    <th>Visitantes</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sites as $site)
                                    <tr>
                                        <td>{{ $site->titulo }}</td>
                                        <td>{{ $site->slug }}</td>
                                        <td>{{ $site->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $site->visualizacoes_count ?? 0 }}</td>
                                        <td>{{ $site->cliques_whatsapp_count ?? 0 }}</td>
                                        <td>{{ $site->visitantes_count ?? 0 }}</td>
                                        <td>
                                            <a href="{{ route('admin.site.edit', $site->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                            <a href="{{ route('site.publico', $site->slug) }}" target="_blank" class="btn btn-sm btn-success">Ver Site</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhum site cadastrado ainda.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $sites->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
