<x-admin.layout title="Logs de Tokens dos Bots">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="dashboard-title">
                <span>💎</span> Logs de Tokens por Bot
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Bot</th>
                                <th>Empresa</th>
                                <th>Mensagem do Usuário</th>
                                <th>Resposta do Bot</th>
                                <th>Tokens Usados</th>
                                <th>Data / Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->bot->nome ?? 'Bot não definido' }}</td>
                                    <td>{{ $log->empresa->nome ?? 'N/A' }}</td>
                                    <td>{{ $log->mensagem_usuario }}</td>
                                    <td>{{ $log->resposta_bot }}</td>
                                    <td>{{ $log->tokens_usados }}</td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Nenhum log encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
