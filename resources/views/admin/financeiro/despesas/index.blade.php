<x-admin.layout title="Despesas">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 2%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Despesas"/>
            <!-- /Page Header -->

            <x-alert/>

            <!-- Botão lançar despesa -->
            <div class="mb-3 text-end">
                <a href="{{ route('financeiro.despesas.create') }}" class="btn btn-primary">
                    Lançar Despesa
                </a>
            </div>

            <!-- Filtros -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('financeiro.despesas.index') }}" class="row g-3 align-items-end">

                        <div class="col-md-3 col-sm-6">
                            <label class="form-label">Categoria</label>
                            <select name="categoria_id" class="form-select">
                                <option value="">Todas</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 col-sm-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>Pago</option>
                                <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pendente</option>
                            </select>
                        </div>

                        <div class="col-md-2 col-sm-6">
                            <label class="form-label">Data Início</label>
                            <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
                        </div>

                        <div class="col-md-2 col-sm-6">
                            <label class="form-label">Data Fim</label>
                            <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
                        </div>

                        <div class="col-md-3 col-sm-6 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-50">Filtrar</button>
                            <a href="{{ route('financeiro.despesas.index') }}" class="btn btn-secondary w-50">Limpar</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Total -->
            <div class="mb-3">
                <h6>Total Despesas (filtro aplicado): <strong>R$ {{ number_format($totalDespesas, 2, ',', '.') }}</strong></h6>
            </div>

            <!-- Tabela -->
            <div class="card shadow-sm">
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th>Categoria</th>
                                    <th>Status</th>
                                    <th>Data Vencimento</th>
                                    <th>Empresa</th>
                                    <th>Usuário</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($despesas as $despesa)
                                    <tr>
                                        <td>{{ $despesa->descricao }}</td>
                                        <td>R$ {{ $despesa->valor }}</td>
                                        <td>{{ $despesa->categoria->nome ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $despesa->status === 'PAID' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $despesa->status === 'PAID' ? 'Pago' : 'Pendente' }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($despesa->data_vencimento)->format('d/m/Y') }}</td>
                                        <td>{{ $despesa->empresa->nome ?? '-' }}</td>
                                        <td>{{ $despesa->usuario->nome ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                                <a href="{{ route('financeiro.despesas.edit', $despesa->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                                <form action="{{ route('financeiro.despesas.destroy', $despesa->id) }}" method="POST" onsubmit="return confirm('Excluir esta despesa?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Nenhuma despesa encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação + Total -->
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Total Despesas (filtro aplicado):</strong> R$ {{ number_format($totalDespesas, 2, ',', '.') }}
                        </div>

                        {{ $despesas->links() }}

                        <button id="exportCsv" class="btn btn-success btn-sm">Exportar CSV</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
document.getElementById('exportCsv').addEventListener('click', function () {
    const rows = [];
    let total = 0;
    const trs = document.querySelectorAll('table tbody tr');

    trs.forEach(tr => {
        const tds = tr.querySelectorAll('td');
        if(tds.length === 0) return;

        const valor = parseFloat(tds[1].innerText.replace('R$', '').replace('.', '').replace(',', '.')) || 0;
        total += valor;

        rows.push({
            "Descrição": tds[0].innerText.trim(),
            "Valor (R$)": valor,
            "Categoria": tds[2].innerText.trim(),
            "Status": tds[3].innerText.trim(),
            "Data Vencimento": tds[4].innerText.trim(),
            "Empresa": tds[5].innerText.trim(),
            "Usuário": tds[6].innerText.trim()
        });
    });

    // Adiciona linha de total
    rows.push({
        "Descrição": "TOTAL",
        "Valor (R$)": total,
        "Categoria": "",
        "Status": "",
        "Data Vencimento": "",
        "Empresa": "",
        "Usuário": ""
    });

    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.json_to_sheet(rows);

    ws['!cols'] = [
        { wch: 30 },
        { wch: 12 },
        { wch: 20 },
        { wch: 12 },
        { wch: 15 },
        { wch: 20 },
        { wch: 20 }
    ];

    XLSX.utils.book_append_sheet(wb, ws, "Despesas");
    XLSX.writeFile(wb, "despesas.xlsx");
});

</script>
