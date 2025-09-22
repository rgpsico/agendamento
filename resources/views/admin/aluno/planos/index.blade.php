<x-admin.layout title="Planos dos Alunos">
    <div class="page-wrapper">
        <div class="content container-fluid">

          @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('alunos.planos.create') }}" class="btn btn-success mb-3">Criar Novo Plano</a>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Descrição</th>
                                        <th>Valor</th>
                                        <th>Duração (dias)</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($planos as $plano)
                                    <tr>
                                        <td>{{ $plano->nome }}</td>
                                        <td>{{ $plano->descricao }}</td>
                                        <td>R$ {{ number_format($plano->valor, 2, ',', '.') }}</td>
                                        <td>{{ $plano->duracao_dias }}</td>
                                        <td>
                                            <a href="{{ route('alunos.planos.edit', $plano->id) }}" class="btn btn-primary btn-sm">Editar</a>
                                            <form action="{{ route('alunos.planos.destroy', $plano->id) }}" method="POST" style="display:inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>			
    </div>
</x-admin.layout>
