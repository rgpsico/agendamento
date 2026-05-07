<x-admin.layout title="CRM - Leads">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div><h3 class="page-title">Leads</h3><ul class="breadcrumb"><li class="breadcrumb-item">CRM</li><li class="breadcrumb-item active">Leads</li></ul></div>
                <a href="{{ route('crm.leads.create') }}" class="btn btn-primary">Novo Lead</a>
            </div>
            @include('crm._nav')
            <x-alert-messages />

            {{-- Filtros --}}
            <div class="card mb-3">
                <div class="card-body">
                    <form class="row g-2" method="GET">
                        <div class="col-md-3"><input name="busca" class="form-control" value="{{ request('busca') }}" placeholder="Buscar por nome, e-mail ou telefone"></div>
                        <div class="col-md-2">
                            <select name="pipeline_status" class="form-control">
                                <option value="">Todos os status</option>
                                @foreach($statuses as $key => $label)<option value="{{ $key }}" @selected(request('pipeline_status') === $key)>{{ $label }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="origem" class="form-control">
                                <option value="">Todas as origens</option>
                                @foreach($origens as $key => $label)<option value="{{ $key }}" @selected(request('origem') === $key)>{{ $label }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="whatsapp" class="form-control">
                                <option value="">WhatsApp - todos</option>
                                <option value="enviado" @selected(request('whatsapp') === 'enviado')>Mensagem enviada</option>
                                <option value="pendente" @selected(request('whatsapp') === 'pendente')>Ainda nao enviado</option>
                            </select>
                        </div>
                        <div class="col-md-2"><button class="btn btn-secondary w-100">Filtrar</button></div>
                    </form>
                </div>
            </div>

            {{-- Barra de acoes em massa --}}
            <div id="barraAcoesMassa" class="card mb-3 d-none">
                <div class="card-body d-flex align-items-center gap-3 flex-wrap">
                    <span class="fw-semibold"><span id="qtdSelecionados">0</span> lead(s) selecionado(s)</span>
                    <form method="POST" action="{{ route('crm.leads.enviar-email-massa') }}" id="formEnvioMassa" class="d-flex gap-2 align-items-center flex-wrap">
                        @csrf
                        <div id="inputsLeadsMassa"></div>
                        <select name="email_template_id" class="form-select" style="min-width:220px" required>
                            <option value="">— Escolha o template —</option>
                            @foreach($emailTemplates as $tpl)
                                <option value="{{ $tpl->id }}">{{ $tpl->nome }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-outline-info btn-sm"
                            onclick="verTemplateDoSelect(document.querySelector('#formEnvioMassa select[name=email_template_id]'))">
                            <i class="fe fe-eye"></i> Ver
                        </button>
                        @if($emailTemplates->isEmpty())
                            <a href="{{ route('crm.email-templates.index') }}" class="btn btn-outline-warning btn-sm">Criar template primeiro</a>
                        @else
                            <button type="submit" class="btn btn-primary"
                                onclick="return confirmarEnvioTemplate(this.form.querySelector('select[name=email_template_id]'), document.getElementById('qtdSelecionados').textContent + ' lead(s)')">
                                Enviar E-mail em Massa
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width:36px">
                                    <input type="checkbox" id="selecionarTodos" title="Selecionar todos">
                                </th>
                                <th>Nome</th>
                                <th>Contato</th>
                                <th>WhatsApp</th>
                                <th>Email</th>
                                <th>Origem</th>
                                <th>Status</th>
                                <th>Valor</th>
                                <th>Campanha</th>
                                <th class="text-end">Acoes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leads as $lead)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="checkbox-lead" value="{{ $lead->id }}"
                                            @unless($lead->email) disabled title="Sem e-mail cadastrado" @endunless>
                                    </td>
                                    <td><a href="{{ route('crm.leads.show', $lead) }}"><strong>{{ $lead->nome }}</strong></a><div class="text-muted small">{{ $lead->interesse }}</div></td>
                                    <td>{{ $lead->telefone ?? '-' }}<div class="text-muted small">{{ $lead->email }}</div></td>
                                    <td>
                                        @if($lead->whatsapp_enviado_em)
                                            <span class="badge bg-success">Enviado</span>
                                            <div class="text-muted small">{{ $lead->whatsapp_enviado_em->format('d/m/Y H:i') }}</div>
                                        @else
                                            <span class="badge bg-secondary">Pendente</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($lead->email_enviado_em)
                                            <span class="badge bg-success">Enviado</span>
                                            <div class="text-muted small">{{ $lead->email_enviado_em->format('d/m/Y H:i') }}</div>
                                        @else
                                            <span class="badge bg-secondary">Pendente</span>
                                        @endif
                                    </td>
                                    <td>{{ $lead->origem_label }}</td>
                                    <td><span class="badge bg-primary">{{ $lead->pipeline_status_label }}</span></td>
                                    <td>R$ {{ number_format((float) $lead->valor_estimado, 2, ',', '.') }}</td>
                                    <td>{{ $lead->campanha->nome ?? '-' }}</td>
                                    <td class="text-end">
                                        @if($lead->whatsapp_url)
                                            <form method="POST" action="{{ route('crm.leads.whatsapp', $lead) }}" target="_blank" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">WhatsApp</button>
                                            </form>
                                        @endif
                                        @if($lead->email)
                                            <button class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEmailLead{{ $lead->id }}">
                                                E-mail
                                            </button>
                                        @endif
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('crm.leads.show', $lead) }}">Ver</a>
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('crm.leads.edit', $lead) }}">Editar</a>
                                    </td>
                                </tr>

                                {{-- Modal enviar e-mail individual --}}
                                @if($lead->email)
                                <div class="modal fade" id="modalEmailLead{{ $lead->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form method="POST" action="{{ route('crm.leads.enviar-email', $lead) }}">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Enviar E-mail para {{ $lead->nome }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-muted small">Para: {{ $lead->email }}</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Escolha o template</label>
                                                        <select name="email_template_id" class="form-select" required>
                                                            <option value="">— Selecione —</option>
                                                            @foreach($emailTemplates as $tpl)
                                                                <option value="{{ $tpl->id }}">{{ $tpl->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if($emailTemplates->isEmpty())
                                                        <div class="alert alert-warning">
                                                            Nenhum template ativo. <a href="{{ route('crm.email-templates.index') }}">Criar template</a>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary" @if($emailTemplates->isEmpty()) disabled @endif>Enviar E-mail</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            @empty
                                <tr><td colspan="10" class="text-center">Nenhum lead encontrado.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $leads->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    @include('crm.email-templates._preview-script')

    <script>
    (function () {
        const checkboxes = () => document.querySelectorAll('.checkbox-lead:not([disabled])');
        const barra = document.getElementById('barraAcoesMassa');
        const qtdEl = document.getElementById('qtdSelecionados');
        const inputsContainer = document.getElementById('inputsLeadsMassa');
        const selecionarTodos = document.getElementById('selecionarTodos');

        function atualizar() {
            const marcados = [...checkboxes()].filter(c => c.checked);
            qtdEl.textContent = marcados.length;
            barra.classList.toggle('d-none', marcados.length === 0);

            inputsContainer.innerHTML = '';
            marcados.forEach(c => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'lead_ids[]';
                inp.value = c.value;
                inputsContainer.appendChild(inp);
            });
        }

        document.querySelectorAll('.checkbox-lead').forEach(c => c.addEventListener('change', atualizar));

        selecionarTodos.addEventListener('change', function () {
            checkboxes().forEach(c => { c.checked = selecionarTodos.checked; });
            atualizar();
        });
    })();
    </script>
</x-admin.layout>
