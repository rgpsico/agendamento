<x-admin.layout title="CRM - Leads">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h3 class="page-title">Gerenciar Leads</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                            <li class="breadcrumb-item">CRM</li>
                            <li class="breadcrumb-item active">Leads</li>
                        </ul>
                    </div>
                    <div class="col-sm-6 text-end d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.leads.template') }}" class="btn btn-outline-secondary">
                            <i class="fe fe-download"></i> Template CSV
                        </a>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImport">
                            <i class="fe fe-upload"></i> Importar CSV
                        </button>
                        <a href="{{ route('admin.leads.create') }}" class="btn btn-primary">
                            <i class="fe fe-plus"></i> Novo Lead
                        </a>
                    </div>
                </div>
            </div>

            <x-alert-messages />

            {{-- Filtros --}}
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.leads.index') }}" class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Buscar</label>
                            <input type="text" name="busca" class="form-control" placeholder="Nome, e-mail ou telefone..."
                                   value="{{ request('busca') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="">Todos</option>
                                @foreach($statusList as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Origem</label>
                            <select name="origem" class="form-control">
                                <option value="">Todas</option>
                                @foreach($origens as $key => $label)
                                    <option value="{{ $key }}" {{ request('origem') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Bairro</label>
                            <select name="bairro" class="form-control">
                                <option value="">Todos</option>
                                @foreach($bairros as $bairro)
                                    <option value="{{ $bairro }}" {{ request('bairro') === $bairro ? 'selected' : '' }}>
                                        {{ $bairro }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">E-mail</label>
                            <select name="email_status" class="form-control">
                                <option value="">Todos</option>
                                <option value="nao_enviado" {{ request('email_status') === 'nao_enviado' ? 'selected' : '' }}>Não enviado</option>
                                <option value="enviado" {{ request('email_status') === 'enviado' ? 'selected' : '' }}>Já enviado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">WhatsApp</label>
                            <select name="whatsapp_status" class="form-control">
                                <option value="">Todos</option>
                                <option value="nao_enviado" {{ request('whatsapp_status') === 'nao_enviado' ? 'selected' : '' }}>Nao enviado</option>
                                <option value="enviado" {{ request('whatsapp_status') === 'enviado' ? 'selected' : '' }}>Ja enviado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Temperatura</label>
                            <select name="temperatura" class="form-control">
                                <option value="">Todos</option>
                                <option value="quente" {{ request('temperatura') === 'quente' ? 'selected' : '' }}>🔥 Quente</option>
                                <option value="morno"  {{ request('temperatura') === 'morno'  ? 'selected' : '' }}>🌡️ Morno</option>
                                <option value="frio"   {{ request('temperatura') === 'frio'   ? 'selected' : '' }}>🧊 Frio</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Mostrar</label>
                            <select name="per_page" class="form-control">
                                <option value="20" {{ request('per_page', '20') === '20' ? 'selected' : '' }}>20</option>
                                <option value="100" {{ request('per_page') === '100' ? 'selected' : '' }}>100</option>
                                <option value="200" {{ request('per_page') === '200' ? 'selected' : '' }}>200</option>
                                <option value="all" {{ request('per_page') === 'all' ? 'selected' : '' }}>Todos</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Data inicial</label>
                            <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Data final</label>
                            <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Ordenar</label>
                            <select name="ordem" class="form-control">
                                <option value="novos" {{ request('ordem', 'novos') === 'novos' ? 'selected' : '' }}>Novos primeiro</option>
                                <option value="antigos" {{ request('ordem') === 'antigos' ? 'selected' : '' }}>Antigos primeiro</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2 align-items-end">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fe fe-search"></i> Filtrar
                            </button>
                            <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary">
                                <i class="fe fe-x"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Form de envio em massa — FORA da tabela para evitar forms aninhados --}}
            <form action="{{ route('crm.leads.enviar-email-massa') }}" method="POST" id="formEmails">
                @csrf
                <div id="idsContainer"></div>
                <input type="hidden" name="email_template_id" id="hiddenTemplateId">
            </form>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            {{-- Barra de ação --}}
                            <div id="barraSelecionados" class="d-none mb-3 p-2 bg-light rounded d-flex align-items-center gap-3 flex-wrap">
                                <span class="text-muted small">
                                    <strong id="qtdSelecionados">0</strong> lead(s) selecionado(s)
                                </span>
                                <select id="selectTemplate" class="form-select form-select-sm" style="max-width:260px" required>
                                    <option value="">— Escolha o template —</option>
                                    @foreach($emailTemplates as $tpl)
                                        <option value="{{ $tpl->id }}">{{ $tpl->nome }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-info btn-sm"
                                    onclick="verTemplateDoSelect(document.getElementById('selectTemplate'))">
                                    <i class="fe fe-eye"></i> Ver
                                </button>
                                @if($emailTemplates->isEmpty())
                                    <a href="{{ route('crm.email-templates.index') }}" class="btn btn-warning btn-sm">
                                        Criar template primeiro
                                    </a>
                                @else
                                    <button type="button" class="btn btn-primary btn-sm" id="btnDispararEmails">
                                        <i class="fe fe-send"></i> Disparar e-mails
                                    </button>
                                @endif
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width:40px;">
                                                <input type="checkbox" id="checkAll" title="Marcar todos">
                                            </th>
                                            <th>Nome</th>
                                            <th>Contato</th>
                                            <th>Bairro</th>
                                            <th>Cadastrado em</th>
                                            <th>Origem</th>
                                            <th>Status</th>
                                            <th class="text-center">Temp.</th>
                                            <th>E-mail enviado</th>
                                            <th>WhatsApp enviado</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($leads as $lead)
                                            <tr>
                                                <td>
                                                    <input type="checkbox"
                                                           value="{{ $lead->id }}"
                                                           class="check-lead"
                                                           {{ $lead->email ? '' : 'disabled title="Sem e-mail"' }}>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.leads.show', $lead) }}">
                                                        {{ $lead->nome }}
                                                    </a>
                                                    @if($lead->empresa)
                                                        <br><small class="text-muted">{{ $lead->empresa }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $lead->email ?? '-' }}
                                                    @if($lead->telefone)
                                                        <br><small>{{ $lead->telefone }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $lead->bairro ?? '-' }}</td>
                                                <td>{{ $lead->created_at?->format('d/m/Y') ?? '-' }}</td>
                                                <td>{{ $lead->origem_label }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $lead->status_color }}">
                                                        {{ $lead->status_label }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @php $t = \App\Models\Lead::$temperaturaConfig[$lead->temperatura]; @endphp
                                                    <span class="badge bg-{{ $t['color'] }}" title="{{ $t['label'] }}">
                                                        {{ $t['icon'] }} {{ $t['label'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($lead->email_enviado_em)
                                                        <span class="badge bg-success"
                                                              title="{{ $lead->email_enviado_em->format('d/m/Y H:i') }}">
                                                            <i class="fe fe-check"></i>
                                                            {{ $lead->email_enviado_em->format('d/m/Y') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($lead->whatsapp_enviado_em)
                                                        <span class="badge bg-success"
                                                              title="{{ $lead->whatsapp_enviado_em->format('d/m/Y H:i') }}">
                                                            <i class="fe fe-check"></i>
                                                            {{ $lead->whatsapp_enviado_em->format('d/m/Y') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted small">---</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="actions">
                                                        @if($lead->whatsapp_url)
                                                            <a href="{{ route('admin.leads.whatsapp', $lead) }}" target="_blank"
                                                               class="btn btn-sm btn-success" title="WhatsApp">
                                                                <i class="fab fa-whatsapp"></i>
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('admin.leads.show', $lead) }}"
                                                           class="btn btn-sm bg-success-light" title="Ver">
                                                            <i class="fe fe-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.leads.edit', $lead) }}"
                                                           class="btn btn-sm bg-info-light" title="Editar">
                                                            <i class="fe fe-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('admin.leads.destroy', $lead) }}"
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('Deseja excluir este lead?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm bg-danger-light" title="Excluir">
                                                                <i class="fe fe-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center">Nenhum lead encontrado.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $leads->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('crm.email-templates._preview-script')

    {{-- Modal Import --}}
    <div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fe fe-upload"></i> Importar Leads</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabColar">Colar CSV</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabArquivo">Upload de Arquivo</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tabColar">
                            <form action="{{ route('admin.leads.import.text') }}" method="POST">
                                @csrf
                                <p class="text-muted small mb-2">
                                    Cole o CSV abaixo. Colunas aceitas: <code>id, nome_negocio, telefone, email, bairro, tipo, origem</code>
                                </p>
                                <textarea name="conteudo" class="form-control font-monospace" rows="14"
                                          placeholder="id,nome_negocio,telefone,email,bairro,tipo" required></textarea>
                                <div class="mt-3 text-end">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-success ms-2"><i class="fe fe-check"></i> Importar</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="tabArquivo">
                            <form action="{{ route('admin.leads.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <p class="text-muted small mb-2">
                                    Selecione um arquivo <strong>.csv</strong>.
                                    <a href="{{ route('admin.leads.template') }}">Baixar template</a>
                                </p>
                                <input type="file" name="arquivo" class="form-control" accept=".csv,.txt" required>
                                <div class="mt-3 text-end">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-success ms-2"><i class="fe fe-upload"></i> Importar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const checkAll  = document.getElementById('checkAll');
        const barra     = document.getElementById('barraSelecionados');
        const qtdSpan   = document.getElementById('qtdSelecionados');
        const btnDisparar = document.getElementById('btnDispararEmails');
        const formEmails  = document.getElementById('formEmails');
        const idsContainer = document.getElementById('idsContainer');

        function getSelecionados() {
            return [...document.querySelectorAll('.check-lead:checked')].map(c => c.value);
        }

        function atualizarBarra() {
            const qtd = getSelecionados().length;
            qtdSpan.textContent = qtd;
            barra.classList.toggle('d-none', qtd === 0);
            barra.classList.toggle('d-flex', qtd > 0);
        }

        checkAll.addEventListener('change', function () {
            document.querySelectorAll('.check-lead:not([disabled])').forEach(c => c.checked = this.checked);
            atualizarBarra();
        });

        document.querySelectorAll('.check-lead').forEach(c => c.addEventListener('change', function () {
            const todos = [...document.querySelectorAll('.check-lead:not([disabled])')];
            checkAll.checked = todos.every(c => c.checked);
            atualizarBarra();
        }));

        btnDisparar.addEventListener('click', function () {
            const ids = getSelecionados();
            if (!ids.length) return;

            const selectEl = document.getElementById('selectTemplate');
            if (!confirmarEnvioTemplate(selectEl, ids.length + ' lead(s)')) return;

            document.getElementById('hiddenTemplateId').value = selectEl.value;
            idsContainer.innerHTML = ids.map(id => `<input type="hidden" name="lead_ids[]" value="${id}">`).join('');
            formEmails.submit();
        });
    </script>
</x-admin.layout>
