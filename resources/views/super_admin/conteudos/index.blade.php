@extends('adminlte::page')

@section('title', 'Conteúdo & Artigos')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0"><i class="fas fa-pen-nib text-primary mr-2"></i>Conteúdo & Artigos</h1>
            <small class="text-muted">Gere artigos, posts e roteiros com IA para divulgar o sistema</small>
        </div>
        <a href="{{ route('super.admin.conteudos.create') }}" class="btn btn-primary">
            <i class="fas fa-magic mr-1"></i> Gerar Novo Conteúdo
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- Filtros --}}
    <div class="card card-outline card-primary mb-3">
        <div class="card-body py-2">
            <form method="GET" class="form-inline flex-wrap gap-2">
                <select name="nicho" class="form-control form-control-sm mr-2 mb-1">
                    <option value="">Todos os nichos</option>
                    @foreach($nichos as $n)
                        <option value="{{ $n->nicho }}" @selected($nicho === $n->nicho)>{{ $n->nome }}</option>
                    @endforeach
                </select>
                <select name="formato" class="form-control form-control-sm mr-2 mb-1">
                    <option value="">Todos os formatos</option>
                    <option value="artigo"         @selected($formato === 'artigo')>Artigo</option>
                    <option value="post_instagram" @selected($formato === 'post_instagram')>Post Instagram</option>
                    <option value="post_tiktok"    @selected($formato === 'post_tiktok')>Post TikTok</option>
                    <option value="legenda_video"  @selected($formato === 'legenda_video')>Legenda de Vídeo</option>
                </select>
                <select name="status" class="form-control form-control-sm mr-2 mb-1">
                    <option value="">Todos os status</option>
                    <option value="rascunho"  @selected($status === 'rascunho')>Rascunho</option>
                    <option value="revisado"  @selected($status === 'revisado')>Revisado</option>
                    <option value="publicado" @selected($status === 'publicado')>Publicado</option>
                </select>
                <button class="btn btn-sm btn-secondary mb-1 mr-1"><i class="fas fa-filter mr-1"></i>Filtrar</button>
                <a href="{{ route('super.admin.conteudos') }}" class="btn btn-sm btn-light mb-1">Limpar</a>
            </form>
        </div>
    </div>

    {{-- Cards de conteúdo --}}
    @if($conteudos->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-pen-nib fa-3x mb-3 d-block"></i>
            <p>Nenhum conteúdo encontrado. <a href="{{ route('super.admin.conteudos.create') }}">Gerar o primeiro agora!</a></p>
        </div>
    @else
        <div class="row">
            @foreach($conteudos as $item)
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    {{-- Capa --}}
                    @if($item->imagem_capa)
                        <img src="{{ $item->imagem_capa_url }}" class="card-img-top" style="height:160px;object-fit:cover;" alt="">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-gradient-{{ ['primary','success','warning','info','danger'][($item->id % 5)] }}"
                             style="height:160px;border-radius:.25rem .25rem 0 0;">
                            <i class="fas fa-{{ match($item->formato) { 'artigo' => 'newspaper', 'post_instagram' => 'instagram', 'post_tiktok' => 'video', default => 'align-left' } }} fa-3x text-white opacity-50"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        {{-- Badges --}}
                        <div class="mb-2">
                            <span class="badge badge-{{ match($item->formato) { 'artigo' => 'primary', 'post_instagram' => 'danger', 'post_tiktok' => 'dark', 'legenda_video' => 'warning', default => 'secondary' } }}">
                                {{ $item->formato_label }}
                            </span>
                            <span class="badge badge-{{ $item->status_badge }} ml-1">{{ $item->status_label }}</span>
                            @if($item->nicho)
                                <span class="badge badge-light border ml-1">{{ $item->nicho }}</span>
                            @endif
                        </div>

                        <h5 class="card-title font-weight-bold mb-1" style="font-size:.95rem;line-height:1.3;">
                            {{ Str::limit($item->titulo, 70) }}
                        </h5>

                        @if($item->legenda)
                            <p class="text-muted small mb-2" style="font-size:.82rem;">
                                {{ Str::limit($item->legenda, 120) }}
                            </p>
                        @endif

                        @if($item->palavras_count)
                            <small class="text-muted"><i class="fas fa-align-left mr-1"></i>{{ number_format($item->palavras_count) }} palavras</small>
                        @endif

                        <div class="mt-auto pt-3 d-flex align-items-center justify-content-between border-top">
                            <small class="text-muted">{{ $item->created_at->format('d/m/Y') }}</small>
                            <div>
                                <a href="{{ route('super.admin.conteudos.edit', $item) }}" class="btn btn-sm btn-outline-primary mr-1" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-success mr-1 btn-copiar"
                                        data-corpo="{{ htmlspecialchars(strip_tags($item->corpo ?? ''), ENT_QUOTES) }}"
                                        title="Copiar texto">
                                    <i class="fas fa-copy"></i>
                                </button>
                                @if($item->formato === 'post_instagram' || $item->formato === 'artigo')
                                <a href="https://www.instagram.com/" target="_blank"
                                   class="btn btn-sm btn-outline-danger mr-1" title="Abrir Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                @endif
                                @if($item->formato === 'post_tiktok')
                                <a href="https://www.tiktok.com/" target="_blank"
                                   class="btn btn-sm btn-outline-dark mr-1" title="Abrir TikTok">
                                    <i class="fab fa-tiktok"></i>
                                </a>
                                @endif
                                <form method="POST" action="{{ route('super.admin.conteudos.destroy', $item) }}"
                                      class="d-inline" onsubmit="return confirm('Remover este conteúdo?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-secondary" title="Remover">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Rodapé: publicado em --}}
                    @if($item->publicado_instagram_em || $item->publicado_tiktok_em)
                    <div class="card-footer py-1 bg-light">
                        <small class="text-muted">
                            @if($item->publicado_instagram_em)
                                <i class="fab fa-instagram text-danger mr-1"></i>{{ $item->publicado_instagram_em->format('d/m H:i') }}
                            @endif
                            @if($item->publicado_tiktok_em)
                                <i class="fab fa-tiktok ml-2 mr-1"></i>{{ $item->publicado_tiktok_em->format('d/m H:i') }}
                            @endif
                        </small>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-2">{{ $conteudos->withQueryString()->links() }}</div>
    @endif

</div>
@endsection

@section('js')
<script>
document.querySelectorAll('.btn-copiar').forEach(btn => {
    btn.addEventListener('click', function () {
        const texto = this.dataset.corpo;
        navigator.clipboard.writeText(texto).then(() => {
            const orig = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i>';
            this.classList.replace('btn-outline-success', 'btn-success');
            setTimeout(() => {
                this.innerHTML = orig;
                this.classList.replace('btn-success', 'btn-outline-success');
            }, 1800);
        });
    });
});
</script>
@endsection
