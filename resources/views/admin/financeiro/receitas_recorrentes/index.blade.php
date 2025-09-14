<x-admin.layout title="Receitas Recorrentes">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Receitas Recorrentes"/>
            <x-alert/>

            <!-- Botão nova receita -->
            <div class="mb-3 text-end">
                <a href="{{ route('financeiro.receitas_recorrentes.create') }}" class="btn btn-primary">Nova Receita Recorrente</a>
                <button id="exportCsv" class="btn btn-success">Exportar Excel</button>
            </div>

            <!-- Filtros -->
            <div class="card mb-3 shadow">
                <div class="card-body">
                    <form action="{{ route('financeiro.receitas_recorrentes.index') }}" method="GET" class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label">Aluno</label>
                            <input type="text" name="aluno" class="form-control" placeholder="Nome do Aluno" value="{{ request('aluno') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Descrição</label>
                            <input type="text" name="descricao" class="form-control" placeholder="Descrição" value="{{ request('descricao') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Categoria</label>
                            <select name="categoria_id" class="form-select">
                                <option value="">Todas</option>
                                @foreach(App\Models\FinanceiroCategoria::all() as $cat)
                                    <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Frequência</label>
                            <select name="frequencia" class="form-select">
                                <option value="">Todas</option>
                                <option value="mensal" {{ request('frequencia')=='mensal' ? 'selected':'' }}>Mensal</option>
                                <option value="semanal" {{ request('frequencia')=='semanal' ? 'selected':'' }}>Semanal</option>
                                <option value="anual" {{ request('frequencia')=='anual' ? 'selected':'' }}>Anual</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="ATIVA" {{ request('status')=='ATIVA' ? 'selected':'' }}>Ativa</option>
                                <option value="INATIVA" {{ request('status')=='INATIVA' ? 'selected':'' }}>Inativa</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Data Início</label>
                            <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Data Fim</label>
                            <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
                        </div>


                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary w-50">Filtrar</button>
                            <a href="{{ route('financeiro.receitas_recorrentes.index') }}" class="btn btn-secondary w-50">Limpar</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela -->
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                    <div class="mb-3">
                        <h5>Total Receitas Recorrentes (filtro aplicado): 
                            <strong>R$ {{ number_format($totalReceitas, 2, ',', '.') }}</strong>
                        </h5>
                    </div>

                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Aluno</th>
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
                                @forelse($receitas as $receita)
                                    <tr>
                                        <td>{{ $receita->usuario->nome ?? '-' }}</td>
                                        <td>{{ $receita->descricao }}</td>
                                        <td>{{ number_format($receita->valor,2,',','.') }}</td>
                                        <td>{{ $receita->categoria->nome ?? '-' }}</td>
                                        <td>{{ ucfirst($receita->frequencia) }}</td>
                                        <td>{{ $receita->status }}</td>
                                        <td>{{ $receita->data_inicio->format('d/m/Y') }}</td>
                                        <td>{{ $receita->data_fim ? $receita->data_fim->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            <a href="{{ route('financeiro.receitas_recorrentes.edit',$receita->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                            <form action="{{ route('financeiro.receitas_recorrentes.destroy',$receita->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Nenhuma receita recorrente encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                </div>
                
            </div>

        </div>
    </div>

    <!-- XLSX -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        document.getElementById('exportCsv').addEventListener('click', function () {
            const rows = [];
            const trs = document.querySelectorAll('table tbody tr');

            trs.forEach(tr => {
                const tds = tr.querySelectorAll('td');
                if(tds.length === 0) return;

                rows.push({
                    "Aluno": tds[0].innerText.trim(),
                    "Descrição": tds[1].innerText.trim(),
                    "Valor (R$)": tds[2].innerText.replace('R$','').trim(),
                    "Categoria": tds[3].innerText.trim(),
                    "Frequência": tds[4].innerText.trim(),
                    "Status": tds[5].innerText.trim(),
                    "Data Início": tds[6].innerText.trim(),
                    "Data Fim": tds[7].innerText.trim(),
                });
            });

            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.json_to_sheet(rows);

            ws['!cols'] = [
                {wch:20},{wch:30},{wch:12},{wch:20},{wch:12},{wch:12},{wch:15},{wch:15}
            ];

            XLSX.utils.book_append_sheet(wb, ws, "ReceitasRecorrentes");
            XLSX.writeFile(wb,"receitas_recorrentes.xlsx");
        });
    </script>
</x-admin.layout>
