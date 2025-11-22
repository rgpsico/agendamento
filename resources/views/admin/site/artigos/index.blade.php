<x-admin.layout title="Blog - Artigos">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Artigos do Blog</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Admin</a></li>
                            <li class="breadcrumb-item active">Blog</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="text-right mb-3">
                <a href="{{ route('admin.site.artigos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Artigo
                </a>
            </div>

            <x-alert />

            <div class="card card-table">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-center mb-0">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Resumo</th>
                                    <th>Status</th>
                                    <th>Publicado em</th>
                                    <th>Atualizado em</th>
                                    <th class="text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($artigos as $artigo)
                                    <tr>
                                        <td>
                                            <strong>{{ $artigo->titulo }}</strong>
                                            <div class="text-muted small">Slug: {{ $artigo->slug }}</div>
                                        </td>
                                        <td>{{ \Illuminate\Support\Str::limit($artigo->resumo ?? '', 80) }}</td>
                                        <td>
                                            @if ($artigo->status === \App\Models\SiteArtigo::STATUS_PUBLICADO)
                                                <span class="badge badge-success">Publicado</span>
                                            @else
                                                <span class="badge badge-secondary">Rascunho</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $artigo->publicado_em ? $artigo->publicado_em->format('d/m/Y H:i') : '—' }}
                                        </td>
                                        <td>{{ $artigo->updated_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.site.artigos.edit', $artigo) }}" class="btn btn-sm btn-warning mr-1">
                                                Editar
                                            </a>
                                            <form action="{{ route('admin.site.artigos.destroy', $artigo) }}" method="POST"
                                                style="display: inline-block;"
                                                onsubmit="return confirm('Deseja remover este artigo?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Nenhum artigo cadastrado até o momento.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $artigos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
