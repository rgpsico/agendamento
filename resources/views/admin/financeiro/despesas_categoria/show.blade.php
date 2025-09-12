<x-admin.layout title="Detalhes da Despesa">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Detalhes da Despesa"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Descrição:</strong> {{ $despesa->descricao }}</p>
                            <p><strong>Valor:</strong> R$ {{ number_format($despesa->valor, 2, ',', '.') }}</p>
                            <p><strong>Categoria:</strong> {{ $despesa->categoria ?? '-' }}</p>
                            <p><strong>Status:</strong> 
                                @if($despesa->status === 'PAID')
                                    <span class="badge bg-success">Pago</span>
                                @else
                                    <span class="badge bg-warning">Pendente</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Data de Vencimento:</strong> {{ $despesa->data_vencimento ? $despesa->data_vencimento->format('d/m/Y') : '-' }}</p>
                            <p><strong>Empresa:</strong> {{ $despesa->empresa->nome ?? '-' }}</p>
                            <p><strong>Usuário:</strong> {{ $despesa->usuario->nome ?? '-' }}</p>
                            <p><strong>Criado em:</strong> {{ $despesa->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <a href="{{ route('financeiro.despesas.edit', $despesa->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <a href="{{ route('financeiro.despesas.index') }}" class="btn btn-secondary btn-sm">Voltar</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>