<x-admin.layout title="Receitas">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 2%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Receitas"/>
            <!-- /Page Header -->

            <x-alert/>

            <!-- Botão lançar receita -->
            <div class="mb-3 text-end">
                <a href="{{ route('financeiro.receitas.create') }}" class="btn btn-primary">
                    Lançar Receita
                </a>
            </div>

            <!-- Filtros -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('financeiro.receitas.index') }}" method="GET" class="row g-2 align-items-end">
    
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label">Aluno</label>
                        <input type="text" name="aluno" class="form-control" placeholder="Nome do Aluno" value="{{ request('aluno') }}">
                    </div>
                    
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label">Descrição</label>
                        <input type="text" name="descricao" class="form-control" placeholder="Descrição" value="{{ request('descricao') }}">
                    </div>
                    
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="">Todos Status</option>
                            <option value="RECEBIDA" {{ request('status') === 'RECEBIDA' ? 'selected' : '' }}>Recebido</option>
                            <option value="PENDENTE" {{ request('status') === 'PENDENTE' ? 'selected' : '' }}>Pendente</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label">Método Pagamento</label>
                        <select name="metodo_pagamento" class="form-control">
                            <option value="">Todos Métodos</option>
                            <option value="PRESENCIAL" {{ request('metodo_pagamento') === 'PRESENCIAL' ? 'selected' : '' }}>Presencial</option>
                            <option value="PIX" {{ request('metodo_pagamento') === 'PIX' ? 'selected' : '' }}>Pix</option>
                            <option value="DINHEIRO" {{ request('metodo_pagamento') === 'DINHEIRO' ? 'selected' : '' }}>Dinheiro</option>
                            <option value="CARTAO" {{ request('metodo_pagamento') === 'CARTAO' ? 'selected' : '' }}>Cartão</option>
                        </select>
                    </div>

                    <!-- Checkbox para ativar filtro por período -->
                    <div class="col-md-2 col-sm-6 d-flex align-items-center mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="filtrarPeriodo" {{ request('data_inicio') || request('data_fim') ? 'checked' : '' }}>
                            <label class="form-check-label" for="filtrarPeriodo">
                                Filtrar por período
                            </label>
                        </div>
                    </div>

                    <!-- Campos de datas -->
                    <div class="col-md-2 col-sm-6 periodo-inputs" style="display: {{ request('data_inicio') || request('data_fim') ? 'block' : 'none' }};">
                        <label class="form-label">De</label>
                        <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
                    </div>
                    <div class="col-md-2 col-sm-6 periodo-inputs" style="display: {{ request('data_inicio') || request('data_fim') ? 'block' : 'none' }};">
                        <label class="form-label">Até</label>
                        <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
                    </div>

                    <div class="col-md-2 col-sm-6 d-grid">
                        <button type="submit" class="btn btn-primary mt-1">Filtrar</button>
                    </div>
                    <div class="col-md-2 col-sm-6 d-grid">
                        <a href="{{ route('financeiro.receitas.index') }}" class="btn btn-secondary mt-1">Limpar</a>
                    </div>
                </form>
                </div>
            </div>

            <!-- Total -->
        

            <!-- Tabela de Receitas -->
            <div class="card shadow-sm">
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Aluno</th>
                                    <th>Descrição</th>
                                    <th>Serviço</th>
                                    <th>Categoria</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Método</th>
                                    <th>Recebimento</th>
                                    <th>Vencimento</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receitas as $receita)
                                    <tr>
                                        <td>{{ $receita->usuario->nome }}</td>
                                        <td>{{ $receita->descricao }}</td>
                                        <td>{{ $receita->pagamento->agendamento->modalidade->nome ?? '-' }}</td>
                                        <td>{{ $receita->categoria->nome ?? '-' }}</td>
                                        <td>R$ {{ number_format($receita->valor, 2, ',', '.') }}</td>
                                        <td>
                                            <span class="badge {{ $receita->status === 'RECEBIDA' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $receita->status === 'RECEBIDA' ? 'Recebido' : 'Pendente' }}
                                            </span>
                                        </td>
                                        <td>{{ $receita->pagamento->metodo_pagamento ?? '-' }}</td>
                                        <td>{{ $receita->data_recebimento ? \Carbon\Carbon::parse($receita->data_recebimento)->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $receita->data_vencimento ? \Carbon\Carbon::parse($receita->data_vencimento)->format('d/m/Y') : '-' }}</td>
                                        <td class="text-center">
                                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                                <a href="{{ route('financeiro.receitas.edit', $receita->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                                <form action="{{ route('financeiro.receitas.destroy', $receita->id) }}" method="POST" onsubmit="return confirm('Excluir esta receita?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Nenhuma receita encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-5 d-flex justify-content-end">
                    <div>
                        <strong>Total Receitas (filtro aplicado):</strong> 
                        R$ {{ number_format($totalReceitas, 2, ',', '.') }}
                    </div>

                        {{ $receitas->links() }}
                    </div>
                    <div class="mb-2 text-end">
                        <button id="exportCsv" class="btn btn-success btn-sm">Exportar CSV</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>



    <button id="exportCsv" class="btn btn-success mb-2">Exportar CSV</button>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>


    const checkbox = document.getElementById('filtrarPeriodo');
    const periodoInputs = document.querySelectorAll('.periodo-inputs');

    checkbox.addEventListener('change', () => {
        periodoInputs.forEach(el => {
            el.style.display = checkbox.checked ? 'block' : 'none';
        });
    });

document.getElementById('exportCsv').addEventListener('click', function () {
    const rows = [];
    let total = 0;
    const trs = document.querySelectorAll('table tbody tr');

    trs.forEach(tr => {
        const tds = tr.querySelectorAll('td');

        // Pula linhas vazias
        if(tds.length === 0) return;

        // Valor numérico
        const valor = parseFloat(tds[4].innerText.replace('R$', '').replace('.', '').replace(',', '.')) || 0;
        total += valor;

        rows.push({
            "Aluno": tds[0].innerText.trim(),
            "Descrição": tds[1].innerText.trim(),
            "Serviço": tds[2].innerText.trim(),
            "Categoria": tds[3].innerText.trim(),
            "Valor (R$)": valor,
            "Status": tds[5].innerText.trim(),
            "Método": tds[6].innerText.trim(),
            "Data Recebimento": tds[7].innerText.trim(),
            "Data Vencimento": tds[8].innerText.trim()
        });
    });

    // Adiciona linha de total
    rows.push({
        "Aluno": "",
        "Descrição": "TOTAL",
        "Serviço": "",
        "Categoria": "",
        "Valor (R$)": total,
        "Status": "",
        "Método": "",
        "Data Recebimento": "",
        "Data Vencimento": ""
    });

    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.json_to_sheet(rows);

    // Formata coluna de valores como número
    const valorCol = XLSX.utils.decode_col("E"); // Coluna E = Valor
    for(let i = 1; i <= rows.length; i++) {
        if(ws[XLSX.utils.encode_cell({c: valorCol, r: i})]) {
            ws[XLSX.utils.encode_cell({c: valorCol, r: i})].t = 'n';
        }
    }

    // Largura das colunas
    ws['!cols'] = [
        { wch: 20 }, // Aluno
        { wch: 30 }, // Descrição
        { wch: 20 }, // Serviço
        { wch: 20 }, // Categoria
        { wch: 12 }, // Valor
        { wch: 12 }, // Status
        { wch: 15 }, // Método
        { wch: 15 }, // Recebimento
        { wch: 15 }  // Vencimento
    ];

    XLSX.utils.book_append_sheet(wb, ws, "Receitas");
    XLSX.writeFile(wb, "receitas.xlsx");
});

</script>
