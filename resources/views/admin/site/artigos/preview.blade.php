<x-admin.layout title="Visualizar Artigo">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Visualizar Artigo</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.site.artigos.index') }}">Blog</a></li>
                            <li class="breadcrumb-item active">Visualizar</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mb-3 d-flex flex-wrap gap-2">
                <a href="{{ route('admin.site.artigos.edit', $artigo) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('admin.site.artigos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <h2 class="mb-2">{{ $artigo->titulo }}</h2>
                    <div class="text-muted small mb-3">
                        Slug: {{ $artigo->slug }}
                    </div>

                    <div class="mb-3">
                        @if ($artigo->status === \App\Models\SiteArtigo::STATUS_PUBLICADO)
                            <span class="badge badge-success">Publicado</span>
                        @else
                            <span class="badge badge-secondary">Rascunho</span>
                        @endif
                        <span class="text-muted small ml-2">
                            Publicado em: {{ $artigo->publicado_em ? $artigo->publicado_em->format('d/m/Y H:i') : '—' }}
                        </span>
                        <span class="text-muted small ml-2">
                            Atualizado em: {{ $artigo->updated_at->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    @if (!empty($artigo->resumo))
                        <div class="mb-4">
                            <h5>Resumo</h5>
                            <p class="mb-0">{{ $artigo->resumo }}</p>
                        </div>
                    @endif

                    @if ($artigo->imagem_capa)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $artigo->imagem_capa) }}" alt="Imagem de capa" class="img-fluid rounded">
                        </div>
                    @endif

                    <div class="mb-0">
                        <h5>Conteúdo</h5>
                        <div class="border rounded p-3">
                            {!! $artigo->conteudo !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
