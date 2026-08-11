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
                                        @php
                                            $waBadges = ['enviado'=>['success','Enviado'],'respondeu'=>['info','Respondeu'],'confirmado'=>['primary','Confirmado'],'nao_respondeu'=>['danger','N. respondeu']];
                                            $wbConf = $waBadges[$lead->whatsapp_confirmado] ?? null;
                                        @endphp
                                        <a href="#" class="text-decoration-none btn-abrir-wa-modal"
                                            data-lead-id="{{ $lead->id }}"
                                            data-lead-nome="{{ $lead->nome }}"
                                            data-wa-enviado="{{ $lead->whatsapp_enviado_em ? $lead->whatsapp_enviado_em->format('d/m/Y H:i') : '' }}"
                                            data-wa-confirmado="{{ $lead->whatsapp_confirmado ?? '' }}"
                                            data-wa-url="{{ $lead->whatsapp_url ?? '' }}"
                                            data-wa-status-url="{{ route('crm.leads.whatsapp-status', $lead) }}"
                                            data-wa-send-url="{{ route('crm.leads.whatsapp', $lead) }}"
                                            data-bs-toggle="modal" data-bs-target="#modalWhatsappStatus">
                                            @if($lead->whatsapp_enviado_em)
                                                <span class="badge bg-success">Enviado</span>
                                                <div class="text-muted small">{{ $lead->whatsapp_enviado_em->format('d/m/Y H:i') }}</div>
                                            @else
                                                <span class="badge bg-secondary">Pendente</span>
                                            @endif
                                            @if($wbConf)
                                                <span class="badge bg-{{ $wbConf[0] }} mt-1 d-block">{{ $wbConf[1] }}</span>
                                            @endif
                                        </a>
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

    {{-- Toast feedback WhatsApp --}}
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:1100">
        <div id="waToast" class="toast align-items-center text-white border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body fw-semibold" id="waToastMsg"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    {{-- Modal WhatsApp Status (único, compartilhado entre todos os leads) --}}
    <div class="modal fade" id="modalWhatsappStatus" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">WhatsApp — <span id="waModalNome"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3" id="waModalEnviado"></p>

                    <label class="form-label fw-semibold">Status da conversa</label>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <button type="button" class="btn btn-outline-secondary btn-wa-status" data-value="">Nenhum</button>
                        <button type="button" class="btn btn-outline-success btn-wa-status" data-value="enviado">Enviado</button>
                        <button type="button" class="btn btn-outline-info btn-wa-status" data-value="respondeu">Respondeu</button>
                        <button type="button" class="btn btn-outline-primary btn-wa-status" data-value="confirmado">Confirmado</button>
                        <button type="button" class="btn btn-outline-danger btn-wa-status" data-value="nao_respondeu">Nao respondeu</button>
                        <button type="button" class="btn btn-outline-warning btn-wa-status" data-value="numero_invalido">Numero invalido</button>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <form id="waModalSendForm" method="POST" target="_blank">
                        @csrf
                        <button type="submit" class="btn btn-success">Abrir WhatsApp</button>
                    </form>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="waModalBtnSalvar">Salvar status</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('crm.email-templates._preview-script')

    <script>
    // Modal WhatsApp Status
    (function () {
        const modal    = document.getElementById('modalWhatsappStatus');
        const bsModal  = bootstrap.Modal.getOrCreateInstance(modal);
        const toast    = new bootstrap.Toast(document.getElementById('waToast'), { delay: 3000 });
        const toastMsg = document.getElementById('waToastMsg');
        const toastEl  = document.getElementById('waToast');

        let currentTrigger = null;
        let currentStatusUrl = '';
        let selectedValue = '';

        const badgeCfg = {
            '':               { cor: 'secondary', label: '' },
            'enviado':        { cor: 'success',   label: 'Enviado' },
            'respondeu':      { cor: 'info',       label: 'Respondeu' },
            'confirmado':     { cor: 'primary',    label: 'Confirmado' },
            'nao_respondeu':  { cor: 'danger',     label: 'Nao respondeu' },
            'numero_invalido':{ cor: 'warning',    label: 'Numero invalido' },
        };

        modal.addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget.closest('[data-lead-id]') || e.relatedTarget;
            currentTrigger   = btn;
            currentStatusUrl = btn.dataset.waStatusUrl;
            selectedValue    = btn.dataset.waConfirmado || '';

            document.getElementById('waModalNome').textContent    = btn.dataset.leadNome;
            document.getElementById('waModalEnviado').textContent = btn.dataset.waEnviado
                ? 'Ultimo envio: ' + btn.dataset.waEnviado
                : 'Ainda nao enviado pelo sistema.';
            document.getElementById('waModalSendForm').action = btn.dataset.waSendUrl;

            document.querySelectorAll('.btn-wa-status').forEach(function (b) {
                b.classList.toggle('active', b.dataset.value === selectedValue);
            });
        });

        document.querySelectorAll('.btn-wa-status').forEach(function (b) {
            b.addEventListener('click', function () {
                document.querySelectorAll('.btn-wa-status').forEach(x => x.classList.remove('active'));
                b.classList.add('active');
                selectedValue = b.dataset.value;
            });
        });

        document.getElementById('waModalBtnSalvar').addEventListener('click', function () {
            const btn = document.getElementById('waModalBtnSalvar');
            btn.disabled = true;
            btn.textContent = 'Salvando...';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(currentStatusUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-HTTP-Method-Override': 'PATCH',
                },
                body: JSON.stringify({ whatsapp_confirmado: selectedValue }),
            })
            .then(function (res) {
                if (!res.ok) throw new Error('Erro ' + res.status);
                return res.json();
            })
            .then(function (data) {
                // Atualiza a célula na linha sem refresh
                atualizarCelula(currentTrigger, data);
                bsModal.hide();
                mostrarToast('Status atualizado!', 'success');
            })
            .catch(function () {
                mostrarToast('Erro ao salvar. Tente novamente.', 'danger');
            })
            .finally(function () {
                btn.disabled = false;
                btn.textContent = 'Salvar status';
            });
        });

        function atualizarCelula(trigger, data) {
            // Atualiza data-* para próxima abertura do modal
            trigger.dataset.waConfirmado = data.whatsapp_confirmado || '';
            if (data.whatsapp_enviado_em) {
                trigger.dataset.waEnviado = data.whatsapp_enviado_em;
            }

            // Reconstrói o conteúdo visual da célula
            const cfg = badgeCfg[data.whatsapp_confirmado] || badgeCfg[''];
            let html = '';

            if (data.whatsapp_enviado_em) {
                html += '<span class="badge bg-success">Enviado</span>';
                html += '<div class="text-muted small">' + data.whatsapp_enviado_em + '</div>';
            } else {
                html += '<span class="badge bg-secondary">Pendente</span>';
            }

            if (cfg.label) {
                html += '<span class="badge bg-' + cfg.cor + ' mt-1 d-block">' + cfg.label + '</span>';
            }

            trigger.innerHTML = html;
        }

        function mostrarToast(msg, tipo) {
            toastMsg.textContent = msg;
            toastEl.classList.remove('bg-success', 'bg-danger');
            toastEl.classList.add('bg-' + tipo);
            toast.show();
        }
    })();

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
