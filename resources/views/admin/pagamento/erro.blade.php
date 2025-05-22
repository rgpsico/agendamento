
<x-admin.layout title="Erro no Pagamento">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <x-header.titulo pageTitle="{{ $pageTitle }}" />
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-danger">Erro ao processar o pagamento</h4>
                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @else
                                <p>Ocorreu um erro desconhecido. Tente novamente.</p>
                            @endif
                            <a href="{{ route('empresa.pagamento.index') }}" class="btn btn-outline-secondary">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
```