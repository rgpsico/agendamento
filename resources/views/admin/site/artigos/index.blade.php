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

            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('admin.social.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fab fa-facebook me-1"></i><i class="fab fa-instagram me-1"></i> Redes Sociais
                </a>
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
                                    <th style="width:56px">Thumb</th>
                                    <th>Título</th>
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
                                            @if($artigo->imagem_capa)
                                                <img src="{{ $artigo->imagem_capa }}"
                                                     alt="{{ $artigo->titulo }}"
                                                     style="width:48px;height:36px;object-fit:cover;border-radius:6px;">
                                            @else
                                                <div style="width:48px;height:36px;border-radius:6px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $artigo->titulo }}</strong>
                                        </td>
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
                                            <a href="{{ route('admin.site.artigos.preview', $artigo) }}" class="btn btn-sm btn-info mr-1">
                                                Ver
                                            </a>
                                            <a href="{{ route('admin.site.artigos.edit', $artigo) }}" class="btn btn-sm btn-warning mr-1">
                                                Editar
                                            </a>
                                            @if($artigo->status === \App\Models\SiteArtigo::STATUS_PUBLICADO)
                                                <button type="button" class="btn btn-sm btn-primary mr-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalCompartilhar"
                                                    data-artigo-id="{{ $artigo->id }}"
                                                    data-artigo-titulo="{{ $artigo->titulo }}">
                                                    <i class="fas fa-share-alt"></i>
                                                </button>
                                            @endif
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

    {{-- Modal Compartilhar nas Redes Sociais --}}
    <div class="modal fade" id="modalCompartilhar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-share-alt me-2 text-primary"></i> Compartilhar Artigo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formCompartilhar" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-3" id="modalArtigoTitulo"></p>

                        <label class="form-label fw-semibold">Compartilhar em:</label>

                        <div class="d-flex flex-column gap-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="redes[]" value="facebook" id="checkFacebook" checked>
                                <label class="form-check-label" for="checkFacebook">
                                    <i class="fab fa-facebook text-primary me-1"></i> Facebook
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="redes[]" value="instagram" id="checkInstagram" checked>
                                <label class="form-check-label" for="checkInstagram">
                                    <i class="fab fa-instagram text-danger me-1"></i> Instagram
                                    <small class="text-muted">(requer imagem no artigo)</small>
                                </label>
                            </div>
                        </div>

                        @php $social = \App\Models\SocialConnection::where('empresa_id', auth()->user()->empresa_id ?? 0)->first(); @endphp
                        @if(!$social)
                            <div class="alert alert-warning mt-3 mb-0 py-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Contas não conectadas.
                                <a href="{{ route('admin.social.index') }}">Conectar agora</a>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm" {{ !$social ? 'disabled' : '' }}>
                            <i class="fas fa-paper-plane me-1"></i> Publicar nas redes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('modalCompartilhar').addEventListener('show.bs.modal', function (e) {
        const btn     = e.relatedTarget;
        const id      = btn.dataset.artigoId;
        const titulo  = btn.dataset.artigoTitulo;
        document.getElementById('modalArtigoTitulo').textContent = '"' + titulo + '"';
        document.getElementById('formCompartilhar').action = '/admin/social/artigo/' + id;
    });
    </script>
</x-admin.layout>
