<x-admin.layout title="Tokens dos Bots">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="dashboard-title">
                <span>💎</span> Tokens por Bot
            </div>

            <!-- Filtros -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.bot.tokens') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="bot_id" class="form-label">Bot</label>
                            <select name="bot_id" id="bot_id" class="form-select">
                                <option value="">Todos os Bots</option>
                                @foreach($bots as $bot)
                                    <option value="{{ $bot->id }}" {{ request('bot_id') == $bot->id ? 'selected' : '' }}>
                                        {{ $bot->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="empresa_id" class="form-label">Empresa</label>
                            <select name="empresa_id" id="empresa_id" class="form-select">
                                <option value="">Todas as Empresas</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}" {{ request('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                        {{ $empresa->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                            <a href="{{ route('admin.bot.tokens') }}" class="btn btn-secondary">Limpar</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela -->
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
                            @forelse($TokenUsage as $TokenU)
                                <tr>
                                    <td>{{ $TokenU->bot->nome ?? 'Bot não definido' }}</td>
                                    <td>{{ $TokenU->empresa->nome ?? 'N/A' }}</td>
                                    <td>{{ $TokenU->mensagem_usuario }}</td>
                                    <td>{{ $TokenU->resposta_bot }}</td>
                                    <td>{{ $TokenU->tokens_usados }}</td>
                                    <td>{{ $TokenU->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Nenhum TokenU encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Paginação -->
                    <div class="mt-3">
                        {{ $TokenUsage->withQueryString()->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
