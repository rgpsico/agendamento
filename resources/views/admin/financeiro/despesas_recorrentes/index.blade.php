<x-admin.layout title="Despesas Recorrentes">
<div class="page-wrapper">
    <div class="content container-fluid" style="padding: 5%">

        <!-- Page Header -->
        <x-header.titulo pageTitle="Despesas Recorrentes"/>
        <!-- /Page Header -->

        <x-alert/>

        <div class="card shadow">
            <div class="card-body">
                <a href="{{ route('financeiro.despesas_recorrentes.create') }}" class="btn btn-primary mb-3">Nova Despesa Recorrente</a>

                <!-- Filtros -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form action="{{ route('financeiro.despesas_recorrentes.index') }}" method="GET" class="row g-2 align-items-end">

                            <div class="col-md-3">
                                <label class="form-label">Descrição</label>
                                <input type="text" name="descricao" class="form-control" value="{{ request('descricao') }}">
                            </div>

                            <div class="col-md-3">
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

                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="ACTIVE" {{ request('status') == 'ACTIVE' ? 'selected' : '' }}>Ativo</option>
                                    <option value="INACTIVE" {{ request('status') == 'INACTIVE' ? 'selected' : '' }}>Inativo</option>
                                </select>
                            </div>

                            <!-- Checkbox para filtrar período -->
                            <div class="col-md-2 d-flex align-items-center mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="filtrarPeriodo" {{ request('data_inicio') || request('data_fim') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="filtrarPeriodo">
                                        Filtrar por período
                                    </label>
                                </div>
                            </div>

                            <!-- Campos de datas -->
                            <div class="col-md-2 periodo-inputs" style="display: {{ request('data_inicio') || request('data_fim') ? 'block' : 'none' }};">
                                <label class="form-label">De</label>
                                <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
                            </div>

                            <div class="col-md-2 periodo-inputs" style="display: {{ request('data_inicio') || request('data_fim') ? 'block' : 'none' }};">
                                <label class="form-label">Até</label>
                                <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
                            </div>

                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary mt-1">Filtrar</button>
                            </div>
                            <div class="col-md-2 d-grid">
                                <a href="{{ route('financeiro.despesas_recorrentes.index') }}" class="btn btn-secondary mt-1">Limpar</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Total -->
           

                <!-- Tabela -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th>Valor (R$)</th>
                                <th>Categoria</th>
                                <th>Frequência</th>
                                <th>Status</th>
                                <th>Data Início</th>
                                <th>Data Fim</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($despesas as $despesa)
                                <tr>
                                    <td>{{ $despesa->descricao }}</td>
                                    <td>{{ number_format($despesa->valor, 2, ',', '.') }}</td>
                                    <td>{{ $despesa->categoria->nome ?? '-' }}</td>
                                    <td>{{ ucfirst(strtolower($despesa->frequencia)) }}</td>
                                    <td>{{ $despesa->status }}</td>
                                    <td>{{ $despesa->data_inicio->format('d/m/Y') }}</td>
                                    <td>{{ $despesa->data_fim ? $despesa->data_fim->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('financeiro.despesas_recorrentes.edit', $despesa->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('financeiro.despesas_recorrentes.destroy', $despesa->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Nenhuma despesa recorrente encontrada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                <div class="mb-3">
                    <h5>Total Despesas Recorrentes (filtro aplicado): 
                        <strong>R$ {{ number_format($totalDespesas, 2, ',', '.') }}</strong>
                    </h5>
                </div>
                </div>

                <div class="mt-2 text-end">
                    <button id="exportCsv" class="btn btn-success btn-sm">Exportar Excel</button>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    const checkbox = document.getElementById('filtrarPeriodo');
    const periodoInputs = document.querySelectorAll('.periodo-inputs');

    checkbox.addEventListener('change', () => {
        periodoInputs.forEach(el => el.style.display = checkbox.checked ? 'block' : 'none');
    });

    // Export Excel
    document.getElementById('exportCsv').addEventListener('click', function () {
        const rows = [];
        const trs = document.querySelectorAll('table tbody tr');

        trs.forEach(tr => {
            const tds = tr.querySelectorAll('td');
            if (tds.length === 0) return;

            rows.push({
                "Descrição": tds[0].innerText.trim(),
                "Valor (R$)": parseFloat(tds[1].innerText.replace('.', '').replace(',', '.')),
                "Categoria": tds[2].innerText.trim(),
                "Frequência": tds[3].innerText.trim(),
                "Status": tds[4].innerText.trim(),
                "Data Início": tds[5].innerText.trim(),
                "Data Fim": tds[6].innerText.trim()
            });
        });

        // Adiciona linha total
        const total = {{ $totalDespesas }};
        rows.push({
            "Descrição": "TOTAL",
            "Valor (R$)": total,
            "Categoria": "",
            "Frequência": "",
            "Status": "",
            "Data Início": "",
            "Data Fim": ""
        });

        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.json_to_sheet(rows);

        ws['!cols'] = [
            { wch: 30 },
            { wch: 12 },
            { wch: 20 },
            { wch: 15 },
            { wch: 12 },
            { wch: 15 },
            { wch: 15 }
        ];

        XLSX.utils.book_append_sheet(wb, ws, "DespesasRecorrentes");
        XLSX.writeFile(wb, "despesas_recorrentes.xlsx");
    });
</script>
