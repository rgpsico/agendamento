<x-admin.layout title="Serviços">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <x-header.titulo pageTitle="Serviços"/>
            <x-alert/>

            <div class="row">
                @foreach($servicos as $servico)
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $servico->nome }}</h5>
                                <p class="text-muted">{{ $servico->descricao }}</p>
                                <a href="{{ route('configurar.horarios', $servico->id) }}" class="btn btn-primary">
                                    Definir Horários
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>          
    </div>
</x-admin.layout>
