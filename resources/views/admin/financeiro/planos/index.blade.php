<x-admin.layout title="Planos">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <x-header.titulo pageTitle="Planos"/>
            <x-alert/>

            <div class="text-end mb-3">
                <a href="{{ route('admin.planos.create') }}" class="btn btn-primary">+ Novo Plano</a>
            </div>
            <div class="mb-3">
                <form method="GET" action="{{ route('admin.planos.index') }}" class="d-flex gap-2">
                    <input type="text" name="filtro" class="form-control" placeholder="Filtrar por nome" value="{{ $filtro ?? '' }}">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('admin.planos.index') }}" class="btn btn-secondary">Limpar</a>
                </form>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Valor</th>
                                <th>Periodicidade</th>
                                <th>Criado em</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($planos as $plano)
                                <tr>
                                    <td>{{ $plano->nome }}</td>
                                    <td>R$ {{ number_format($plano->valor, 2, ',', '.') }}</td>
                                    <td>{{ ucfirst($plano->periodicidade) }}</td>
                                    <td>{{ $plano->created_at->format('d/m/Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.planos.edit', $plano) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('admin.planos.destroy', $plano) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Nenhum plano cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
