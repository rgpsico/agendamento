<x-admin.layout title="Selecionar Página do Facebook">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding:5%">
            <div class="page-header mb-4">
                <h3 class="page-title">Selecionar Página do Facebook</h3>
                <p class="text-muted">Escolha qual página deseja conectar ao sistema.</p>
            </div>

            <div class="row">
                @foreach($pages as $page)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-1">
                                    <i class="fab fa-facebook text-primary me-1"></i>
                                    {{ $page['name'] }}
                                </h5>
                                <small class="text-muted mb-2">ID: {{ $page['id'] }}</small>

                                @if(!empty($page['instagram_business_account']))
                                    <small class="text-success mb-3">
                                        <i class="fab fa-instagram me-1"></i> Instagram vinculado
                                    </small>
                                @else
                                    <small class="text-muted mb-3">Sem Instagram vinculado</small>
                                @endif

                                <form method="POST" action="{{ route('admin.social.store-page') }}" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="page_id" value="{{ $page['id'] }}">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        Conectar esta página
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                <a href="{{ route('admin.social.index') }}" class="btn btn-outline-secondary btn-sm">Cancelar</a>
            </div>
        </div>
    </div>
</x-admin.layout>
