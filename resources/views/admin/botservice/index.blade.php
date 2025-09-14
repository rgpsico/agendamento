<x-admin.layout title="Lista de Serviços dos Bots">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Lista de Serviços dos Bots"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="mb-4 text-end">
                <a href="{{ route('admin.botservice.create') }}" class="btn btn-primary">Criar Serviço do Bot</a>
            </div>

            <form method="GET" class="row mb-4">
    <div class="col-md-4">
        <select name="bot_id" class="form-select">
            <option value="">Todos os Bots</option>
            @foreach($bots as $bot)
                <option value="{{ $bot->id }}" {{ request('bot_id') == $bot->id ? 'selected' : '' }}>
                    {{ $bot->nome }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <select name="servico_id" class="form-select">
            <option value="">Todos os Serviços</option>
            @foreach($servicos as $servico)
                <option value="{{ $servico->id }}" {{ request('servico_id') == $servico->id ? 'selected' : '' }}>
                    {{ $servico->titulo }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="{{ route('admin.botservice.index') }}" class="btn btn-secondary">Limpar</a>
    </div>
</form>


            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Bot</th>
                                <th>Serviço</th>
                                <th>Professor</th>
                                <th>Horário</th>
                                <th>Valor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($botServices as $service)
                            <tr>
                                <td>{{ $service->bot->nome ?? 'N/A' }}</td>
                                <td>{{ $service->nome_servico }}</td>
                                <td>{{ $service->professor ?? '-' }}</td>
                                <td>{{ $service->horario ?? '-' }}</td>
                                <td>R$ {{ number_format($service->valor, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('admin.botservice.edit', $service->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('admin.botservice.destroy', $service->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este serviço?')">Excluir</button>
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
</x-admin.layout>
