<x-admin.layout title="Categorias">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <x-header.titulo pageTitle="Categorias"/>
            <x-alert/>
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @foreach([
                ['RECEITA', 'Categorias de receita', 'Ex.: Pilates, Fisioterapia. Cada serviço cadastrado já ganha a sua; use-as para ver o faturamento separado no Dashboard.', $categoriasReceita],
                ['DESPESA', 'Categorias de despesa', 'Ex.: Aluguel, Energia, Salários. As marcadas como "padrão" já vêm prontas para todos os estúdios.', $categoriasDespesa],
            ] as [$tipo, $titulo, $ajuda, $lista])
                <div class="card shadow mb-4">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="card-title mb-0">{{ $titulo }}</h5>
                            <small class="text-muted">{{ $ajuda }}</small>
                        </div>
                        <a href="{{ route('financeiro.categorias.create', ['tipo' => $tipo]) }}" class="btn btn-primary btn-sm">
                            Nova categoria de {{ $tipo === 'RECEITA' ? 'receita' : 'despesa' }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nome</th>
                                        <th>Descrição</th>
                                        <th class="text-center" style="width: 180px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lista as $categoria)
                                        <tr>
                                            <td>
                                                {{ $categoria->nome }}
                                                @if($categoria->empresa_id === null)
                                                    <span class="badge bg-secondary ms-1">padrão</span>
                                                @endif
                                            </td>
                                            <td>{{ $categoria->descricao }}</td>
                                            <td class="text-center">
                                                @if($categoria->empresa_id !== null)
                                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                                        <a href="{{ route('financeiro.categorias.edit', ['categoria' => $categoria->id, 'tipo' => $tipo]) }}" class="btn btn-sm btn-warning">Editar</a>
                                                        <form action="{{ route('financeiro.categorias.destroy', ['categoria' => $categoria->id, 'tipo' => $tipo]) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger">Excluir</button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <small class="text-muted">—</small>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Nenhuma categoria cadastrada.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</x-admin.layout>
