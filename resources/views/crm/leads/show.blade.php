<x-admin.layout title="CRM - Perfil do Lead">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div><h3 class="page-title">{{ $lead->nome }}</h3><ul class="breadcrumb"><li class="breadcrumb-item">CRM</li><li class="breadcrumb-item active">Perfil</li></ul></div>
                <div class="d-flex gap-2 flex-wrap">
                    @if($lead->whatsapp_url)
                        <form method="POST" action="{{ route('crm.leads.whatsapp', $lead) }}" target="_blank" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">WhatsApp</button>
                        </form>
                    @endif
                    @if($lead->email)
                        <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalEnviarEmail">
                            Enviar E-mail
                        </button>
                    @endif
                    <a href="{{ route('agenda.create') }}" class="btn btn-outline-primary">Agendar experimental</a>
                    <form method="POST" action="{{ route('crm.pipeline.move', $lead) }}">@csrf @method('PATCH')<input type="hidden" name="pipeline_status" value="matriculado"><button class="btn btn-primary">Converter em aluno</button></form>
                </div>
            </div>
            @include('crm._nav')
            <x-alert-messages />

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header"><h5 class="card-title mb-0">Dados pessoais</h5></div>
                        <div class="card-body">
                            <p><strong>Telefone:</strong> {{ $lead->telefone ?? '-' }}</p>
                            <p><strong>E-mail:</strong> {{ $lead->email ?? '-' }}</p>
                            <p><strong>Origem:</strong> {{ $lead->origem_label }}</p>
                            <p><strong>Interesse:</strong> {{ $lead->interesse ?? '-' }}</p>
                            <p><strong>Status:</strong> {{ $lead->pipeline_status_label }}</p>
                            <p><strong>WhatsApp:</strong> {{ $lead->whatsapp_enviado_em ? 'Enviado em ' . $lead->whatsapp_enviado_em->format('d/m/Y H:i') : 'Pendente' }}</p>
                            <p><strong>E-mail:</strong> {{ $lead->email_enviado_em ? 'Enviado em ' . $lead->email_enviado_em->format('d/m/Y H:i') : 'Pendente' }}</p>
                            <p><strong>Campanha:</strong> {{ $lead->campanha->nome ?? '-' }}</p>
                            <p><strong>Responsavel:</strong> {{ $lead->responsavel->nome ?? '-' }}</p>
                            <p class="mb-0"><strong>Observacoes:</strong><br>{{ $lead->observacoes ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header"><h5 class="card-title mb-0">Nova tarefa</h5></div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('crm.tarefas.store') }}">
                                @csrf
                                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                <div class="mb-2"><input name="tipo" class="form-control" value="follow_up" required></div>
                                <div class="mb-2"><input type="datetime-local" name="vencimento" class="form-control"></div>
                                <div class="mb-2"><textarea name="descricao" class="form-control" rows="3" placeholder="Descricao" required></textarea></div>
                                <button class="btn btn-primary w-100">Criar tarefa</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header"><h5 class="card-title mb-0">Timeline</h5></div>
                        <div class="card-body">
                            @forelse($lead->historicos as $historico)
                                <div class="border-start ps-3 pb-3">
                                    <strong>{{ \App\Models\Lead::$pipelineStatus[$historico->de_status] ?? 'Inicio' }} -> {{ \App\Models\Lead::$pipelineStatus[$historico->para_status] ?? $historico->para_status }}</strong>
                                    <div class="text-muted small">{{ $historico->created_at->format('d/m/Y H:i') }} por {{ $historico->usuario->nome ?? 'Sistema' }}</div>
                                    @if($historico->observacao)<p class="mb-0">{{ $historico->observacao }}</p>@endif
                                </div>
                            @empty
                                <p class="text-muted">Ainda nao ha movimentacoes.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header"><h5 class="card-title mb-0">Tarefas</h5></div>
                        <div class="card-body">
                            @forelse($lead->tarefas as $tarefa)
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <div>
                                        <strong>{{ $tarefa->tipo }}</strong>
                                        <div>{{ $tarefa->descricao }}</div>
                                        <div class="text-muted small">{{ $tarefa->vencimento?->format('d/m/Y H:i') ?? 'Sem vencimento' }}</div>
                                    </div>
                                    <span class="badge bg-{{ $tarefa->concluida ? 'success' : 'warning' }}">{{ $tarefa->concluida ? 'Concluida' : 'Pendente' }}</span>
                                </div>
                            @empty
                                <p class="text-muted mb-0">Nenhuma tarefa cadastrada.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal enviar e-mail --}}
    @if($lead->email)
    <div class="modal fade" id="modalEnviarEmail" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('crm.leads.enviar-email', $lead) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Enviar E-mail para {{ $lead->nome }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-3">Para: <strong>{{ $lead->email }}</strong></p>
                        <div class="mb-3">
                            <label class="form-label">Escolha o template <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2">
                                <select name="email_template_id" id="selectTemplateShow" class="form-select" required>
                                    <option value="">— Selecione —</option>
                                    @foreach($emailTemplates as $tpl)
                                        <option value="{{ $tpl->id }}">{{ $tpl->nome }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-info"
                                    onclick="verTemplateDoSelect(document.getElementById('selectTemplateShow'))">
                                    <i class="fe fe-eye"></i> Ver
                                </button>
                            </div>
                        </div>
                        @if($emailTemplates->isEmpty())
                            <div class="alert alert-warning mb-0">
                                Nenhum template ativo. <a href="{{ route('crm.email-templates.index') }}">Criar template</a>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"
                            @if($emailTemplates->isEmpty()) disabled @endif
                            onclick="return confirmarEnvioTemplate(document.getElementById('selectTemplateShow'), '{{ addslashes($lead->nome) }} ({{ addslashes($lead->email) }})')">
                            Enviar E-mail
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('crm.email-templates._preview-script')
    @endif
</x-admin.layout>
