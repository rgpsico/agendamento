<x-admin.layout title="Conversas">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <x-header.titulo pageTitle="Conversas do Bot"/>
            <x-alert/>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Bot</th>
                        <th>Última Mensagem</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($conversas as $conversa)
                    <tr>
                        <td>{{ $conversa->id }}</td>
                        <td>{{ $conversa->user?->name ?? 'Anônimo' }}</td>
                        <td>{{ $conversa->bot?->nome }}</td>
                        <td>{{ Str::limit($conversa->mensagem, 40) }}</td>
                        <td>{{ $conversa->updated_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.conversations.show', $conversa->id) }}" class="btn btn-sm btn-primary">
                                Ver
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $conversas->links() }}
        </div>
    </div>
</x-admin.layout>
