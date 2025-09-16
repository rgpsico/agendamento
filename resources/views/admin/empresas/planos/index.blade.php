<x-admin.layout title="Gerenciar Plano da Empresa">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <x-header.titulo pageTitle="Plano da {{ $empresa->nome }}"/>
            <x-alert/>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5>Plano Atual</h5>
                    @if($planoAtual)
                        <p><strong>{{ $planoAtual->plano->nome }}</strong> - 
                           R$ {{ number_format($planoAtual->plano->valor, 2, ',', '.') }}</p>
                        <p>Status: <span class="badge bg-success">Ativo</span></p>
                        <p>Desde: {{ $planoAtual->data_inicio->format('d/m/Y') }}</p>
                    @else
                        <p class="text-muted">Nenhum plano ativo no momento.</p>
                    @endif
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5>Alterar Plano</h5>
                    <form action="{{ route('admin.empresas.planos.store', $empresa->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="plano_id">Selecione um novo plano</label>
                            <select name="plano_id" id="plano_id" class="form-control">
                                @foreach($planos as $plano)
                                    <option value="{{ $plano->id }}">
                                        {{ $plano->nome }} - R$ {{ number_format($plano->valor, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Plano</button>
                    </form>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <h5>Histórico de Planos</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Plano</th>
                                <th>Status</th>
                                <th>Início</th>
                                <th>Fim</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historico as $registro)
                                <tr>
                                    <td>{{ $registro->plano->nome }}</td>
                                    <td>
                                        @if($registro->status === 'ativo')
                                            <span class="badge bg-success">Ativo</span>
                                        @else
                                            <span class="badge bg-secondary">Inativo</span>
                                        @endif
                                    </td>
                                    <td>{{ $registro->data_inicio->format('d/m/Y') }}</td>
                                    <td>{{ $registro->data_fim ? $registro->data_fim->format('d/m/Y') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Nenhum histórico de planos.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
