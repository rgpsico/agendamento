<x-admin.layout title="CRM - Campanhas">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header"><h3 class="page-title">Campanhas</h3></div>
            @include('crm._nav')
            <x-alert-messages />

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header"><h5 class="card-title mb-0">Nova campanha</h5></div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('crm.campanhas.store') }}">
                                @csrf
                                <div class="mb-3"><label class="form-label">Nome</label><input name="nome" class="form-control" required></div>
                                <div class="mb-3"><label class="form-label">Canal</label><input name="canal" class="form-control" placeholder="Instagram, Google, WhatsApp..." required></div>
                                <div class="row">
                                    <div class="col-md-6 mb-3"><label class="form-label">Inicio</label><input type="date" name="inicio" class="form-control"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Fim</label><input type="date" name="fim" class="form-control"></div>
                                </div>
                                <div class="mb-3"><label class="form-label">Custo</label><input type="number" step="0.01" min="0" name="custo" class="form-control" value="0"></div>
                                <div class="form-check mb-3"><input type="checkbox" name="ativo" value="1" class="form-check-input" checked><label class="form-check-label">Ativa</label></div>
                                <button class="btn btn-primary w-100">Salvar</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table class="table table-hover">
                                <thead><tr><th>Nome</th><th>Canal</th><th>Leads</th><th>CPL</th><th>Conversao</th><th>Status</th></tr></thead>
                                <tbody>
                                    @forelse($campanhas as $campanha)
                                        @php $taxa = $campanha->leads_count > 0 ? round(($campanha->convertidos_count / $campanha->leads_count) * 100, 1) : 0; @endphp
                                        <tr>
                                            <td>{{ $campanha->nome }}</td>
                                            <td>{{ $campanha->canal }}</td>
                                            <td>{{ $campanha->leads_count }}</td>
                                            <td>R$ {{ number_format($campanha->cpl, 2, ',', '.') }}</td>
                                            <td>{{ $taxa }}%</td>
                                            <td><span class="badge bg-{{ $campanha->ativo ? 'success' : 'secondary' }}">{{ $campanha->ativo ? 'Ativa' : 'Inativa' }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center">Nenhuma campanha cadastrada.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $campanhas->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
