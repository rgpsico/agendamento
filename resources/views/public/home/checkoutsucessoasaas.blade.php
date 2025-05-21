<x-admin.layout title="Pagamento Confirmado">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <x-header.titulo pageTitle="{{ $pageTitle }}" />
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-success">Pagamento iniciado com sucesso!</h4>
                            <p>Seu agendamento foi registrado. Acesse o link abaixo para realizar o pagamento:</p>
                            @if($payment_url)
                                <a href="{{ $payment_url }}" target="_blank" class="btn btn-primary">Pagar agora</a>
                            @endif
                            <p>Professor: {{ $professor->nome }}</p>
                            <a href="{{ route('empresa.pagamento.index') }}" class="btn btn-outline-secondary">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>