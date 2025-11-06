<x-admin.layout title="Vincular Aluno a Plano">
    <div class="page-wrapper">
        <div class="content container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('alunos.planos.vincular') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label for="busca" class="form-label">Buscar aluno</label>
                                <input type="text" name="busca" id="busca" class="form-control" placeholder="Nome, e-mail ou telefone" value="{{ $busca }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                            </div>
                            @if($busca)
                                <div class="col-md-3">
                                    <a href="{{ route('alunos.planos.vincular') }}" class="btn btn-outline-secondary w-100">Limpar busca</a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-5">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Alunos</h5>
                            <span class="badge bg-secondary">{{ $alunos->total() }} encontrados</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($alunos as $aluno)
                                    @php
                                        $estaSelecionado = $alunoSelecionado && $alunoSelecionado->id === $aluno->id;
                                        $query = array_filter([
                                            'busca' => $busca,
                                            'aluno_id' => $aluno->id,
                                        ]);
                                    @endphp
                                    <a href="{{ route('alunos.planos.vincular', $query) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start {{ $estaSelecionado ? 'active' : '' }}">
                                        <div>
                                            <div class="fw-semibold">{{ optional($aluno->usuario)->nome ?? 'Aluno sem nome' }}</div>
                                            <small class="text-muted d-block">{{ optional($aluno->usuario)->email ?? '-' }}</small>
                                            @if(optional($aluno->usuario)->telefone)
                                                <small class="text-muted">{{ optional($aluno->usuario)->telefone }}</small>
                                            @endif
                                        </div>
                                        <span class="text-muted small">#{{ $aluno->id }}</span>
                                    </a>
                                @empty
                                    <div class="p-4 text-center text-muted">Nenhum aluno encontrado.</div>
                                @endforelse
                            </div>
                        </div>
                        @if($alunos->hasPages())
                            <div class="card-footer">
                                {{ $alunos->links() }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-7">
                    @if($alunoSelecionado)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Vincular plano</h5>
                                <small class="text-muted">{{ optional($alunoSelecionado->usuario)->nome ?? 'Aluno sem nome' }}</small>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('alunos.planos.vincular.store') }}">
                                    @csrf
                                    <input type="hidden" name="aluno_id" value="{{ $alunoSelecionado->id }}">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="plano_id" class="form-label">Plano</label>
                                            <select name="plano_id" id="plano_id" class="form-select" required>
                                                <option value="" disabled selected>Selecione um plano</option>
                                                @foreach($planos as $plano)
                                                    <option value="{{ $plano->id }}" @selected(old('plano_id') == $plano->id)>{{ $plano->nome }} - R$ {{ number_format($plano->valor, 2, ',', '.') }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="data_inicio" class="form-label">Data de início</label>
                                            <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="{{ old('data_inicio') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="data_fim" class="form-label">Data de término</label>
                                            <input type="date" name="data_fim" id="data_fim" class="form-control" value="{{ old('data_fim') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" id="status" class="form-select">
                                                <option value="ativo" @selected(old('status', 'ativo') === 'ativo')>Ativo</option>
                                                <option value="inativo" @selected(old('status') === 'inativo')>Inativo</option>
                                                <option value="cancelado" @selected(old('status') === 'cancelado')>Cancelado</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="forma_pagamento" class="form-label">Forma de pagamento</label>
                                            <input type="text" name="forma_pagamento" id="forma_pagamento" class="form-control" placeholder="Ex.: Cartão, PIX" value="{{ old('forma_pagamento') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="valor_pago" class="form-label">Valor pago</label>
                                            <input type="number" step="0.01" min="0" name="valor_pago" id="valor_pago" class="form-control" value="{{ old('valor_pago') }}">
                                        </div>
                                        <div class="col-md-12 text-end">
                                            <button type="submit" class="btn btn-success">Vincular plano</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Planos vinculados</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Plano</th>
                                                <th>Status</th>
                                                <th>Início</th>
                                                <th>Término</th>
                                                <th>Valor pago</th>
                                                <th>Forma</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($historicoPlanos as $plano)
                                                <tr>
                                                    <td>{{ $plano->nome }}</td>
                                                    <td><span class="badge bg-{{ $plano->pivot->status === 'ativo' ? 'success' : ($plano->pivot->status === 'inativo' ? 'secondary' : 'danger') }} text-uppercase">{{ $plano->pivot->status }}</span></td>
                                                    <td>{{ $plano->pivot->data_inicio ? \Illuminate\Support\Carbon::parse($plano->pivot->data_inicio)->format('d/m/Y') : '-' }}</td>
                                                    <td>{{ $plano->pivot->data_fim ? \Illuminate\Support\Carbon::parse($plano->pivot->data_fim)->format('d/m/Y') : '-' }}</td>
                                                    <td>{{ $plano->pivot->valor_pago ? 'R$ ' . number_format($plano->pivot->valor_pago, 2, ',', '.') : '-' }}</td>
                                                    <td>{{ $plano->pivot->forma_pagamento ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-3">Nenhum plano vinculado ainda.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Histórico de pagamentos</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Status</th>
                                                <th>Valor</th>
                                                <th>Método</th>
                                                <th>Vencimento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($pagamentos as $pagamento)
                                                <tr>
                                                    <td>{{ $pagamento->created_at?->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $pagamento->status }}</td>
                                                    <td>R$ {{ number_format($pagamento->valor ?? 0, 2, ',', '.') }}</td>
                                                    <td>{{ $pagamento->metodo_pagamento ?? '-' }}</td>
                                                    <td>{{ $pagamento->data_vencimento ? $pagamento->data_vencimento->format('d/m/Y') : '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-3">Nenhum pagamento registrado.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Busque e selecione um aluno para vincular um plano e visualizar o histórico de pagamentos.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
