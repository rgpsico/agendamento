<x-admin.layout title="Lista de Bots">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Lista de Bots"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="mb-4 text-end">
                <a href="{{ route('admin.bot.create') }}" class="btn btn-primary">Criar Bot</a>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Segmento</th>
                                <th>Status</th>
                                <th>Widget</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bots as $bot)
                            <tr>
                                <td>{{ $bot->nome }}</td>
                                <td>{{ $bot->segmento }}</td>
                                <td>
                                    @if($bot->status)
                                        <span class="badge bg-success">Ativo</span>
                                    @else
                                        <span class="badge bg-secondary">Inativo</span>
                                    @endif
                                </td>
                                <td>
                                    @if($bot->widget_ativo)
                                        <span class="badge bg-success mb-1">Widget ativo</span><br>
                                        {{-- Input oculto com o snippet --}}
                                        <input type="text" id="snippet-bot-{{ $bot->id }}"
                                            value='<script src="{{ $bot->widgetUrl() }}" defer></script>'
                                            style="position:absolute;opacity:0;pointer-events:none" readonly>
                                        <button class="btn btn-outline-secondary btn-sm"
                                            onclick="copiarSnippetBot({{ $bot->id }}, this)"
                                            title="Copiar código do widget">
                                            <i class="fas fa-code me-1"></i> Copiar snippet
                                        </button>
                                    @else
                                        <span class="badge bg-light text-secondary">Widget inativo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.bot.show', $bot->id) }}" class="btn btn-info btn-sm">Ver</a>
                                    <a href="{{ route('admin.bot.edit', $bot->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('admin.bot.destroy', $bot->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este bot?')">Excluir</button>
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

    <script>
    function copiarSnippetBot(id, btn) {
        var el = document.getElementById('snippet-bot-' + id);
        navigator.clipboard.writeText(el.value).then(function() {
            btn.innerHTML = '<i class="fas fa-check me-1"></i> Copiado!';
            btn.classList.replace('btn-outline-secondary', 'btn-success');
            setTimeout(function() {
                btn.innerHTML = '<i class="fas fa-code me-1"></i> Copiar snippet';
                btn.classList.replace('btn-success', 'btn-outline-secondary');
            }, 2500);
        });
    }
    </script>
</x-admin.layout>
