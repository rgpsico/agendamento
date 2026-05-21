<x-admin.layout title="Planos dos Alunos">
<div class="page-wrapper">
<div class="content container-fluid" style="padding: 5%">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="mb-0 fw-bold">Planos dos Alunos</h3>
            <small class="text-muted">Gerencie os pacotes disponíveis para contratação</small>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPlano">
            <i class="fas fa-plus me-1"></i> Novo Plano
        </button>
    </div>

    <div class="card shadow-sm border-0" style="border-radius:12px;">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="ps-4 py-3">Nome</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Detalhes</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($planos as $plano)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $plano->nome }}</td>
                        <td>
                            @if($plano->dias_semana)
                                <span class="badge" style="background:#e0f2fe;color:#0369a1;">
                                    <i class="fas fa-calendar-week me-1"></i>Semanal
                                </span>
                            @else
                                <span class="badge" style="background:#f0fdf4;color:#166534;">
                                    <i class="fas fa-align-left me-1"></i>Livre
                                </span>
                            @endif
                        </td>
                        <td class="fw-semibold">R$ {{ number_format($plano->valor, 2, ',', '.') }}</td>
                        <td class="text-muted small">
                            @if($plano->dias_semana)
                                {{ collect(json_decode($plano->dias_semana))->map(fn($d) => ['seg'=>'Seg','ter'=>'Ter','qua'=>'Qua','qui'=>'Qui','sex'=>'Sex','sab'=>'Sáb','dom'=>'Dom'][$d] ?? $d)->join(', ') }}
                                @if($plano->horario) · {{ $plano->horario }} @endif
                            @else
                                {{ $plano->duracao_dias ? $plano->duracao_dias . ' dias' : '' }}
                                {{ $plano->descricao ? '· ' . Str::limit($plano->descricao, 60) : '' }}
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-secondary btn-editar-plano me-1"
                                    data-plano="{{ json_encode($plano) }}"
                                    data-bs-toggle="modal" data-bs-target="#modalPlano">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('alunos.planos.destroy', $plano->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Remover este plano?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="fas fa-box-open fa-2x mb-2 d-block opacity-25"></i>
                            Nenhum plano cadastrado ainda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('alunos.planos.vincular') }}" class="btn btn-outline-primary">
            <i class="fas fa-link me-1"></i> Vincular Plano a Aluno
        </a>
    </div>

</div>
</div>

{{-- ════════════════════════════════════════════════════════
     MODAL NOVO / EDITAR PLANO — com duas abas
════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalPlano" tabindex="-1" aria-labelledby="modalPlanoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">

            {{-- Header --}}
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="modalPlanoLabel">
                        <i class="fas fa-box me-2 text-primary"></i>
                        <span id="modalTitulo">Novo Plano</span>
                    </h5>
                    <small class="text-muted">Escolha o tipo de plano que deseja criar</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Abas --}}
            <div class="modal-body pt-3">

                {{-- Seletor de tipo (abas estilizadas) --}}
                <div class="d-flex gap-2 mb-4 p-1"
                     style="background:#f1f5f9;border-radius:10px;" id="tabSelector">
                    <button type="button" class="btn-tab active" id="tabSemanal"
                            onclick="trocarAba('semanal')">
                        <i class="fas fa-calendar-week me-2"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:.88rem;">Plano Semanal</div>
                            <div style="font-size:.73rem;opacity:.7;">Dias fixos na semana</div>
                        </div>
                    </button>
                    <button type="button" class="btn-tab" id="tabLivre"
                            onclick="trocarAba('livre')">
                        <i class="fas fa-align-left me-2"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:.88rem;">Plano Livre</div>
                            <div style="font-size:.73rem;opacity:.7;">Avulso ou por descrição</div>
                        </div>
                    </button>
                </div>

                {{-- ── ABA: PLANO SEMANAL ── --}}
                <div id="painelSemanal">
                    <form id="formSemanal"
                          method="POST"
                          action="{{ route('alunos.planos.store') }}">
                        @csrf
                        <input type="hidden" name="tipo" value="semanal">
                        <input type="hidden" name="id" id="semanalId">

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nome do Plano <span class="text-danger">*</span></label>
                                <input type="text" name="nome" id="semanalNome" class="form-control"
                                       placeholder="Ex.: Pilates 2x por semana" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Valor (R$) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" step="0.01" min="0" name="valor" id="semanalValor"
                                           class="form-control" placeholder="0,00" required>
                                </div>
                            </div>

                            {{-- Dias da semana --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">Dias da Semana <span class="text-danger">*</span></label>
                                <div class="d-flex gap-2 flex-wrap" id="diasSemana">
                                    @foreach(['seg'=>'Seg','ter'=>'Ter','qua'=>'Qua','qui'=>'Qui','sex'=>'Sex','sab'=>'Sáb','dom'=>'Dom'] as $val => $label)
                                    <label class="dia-pill">
                                        <input type="checkbox" name="dias_semana[]" value="{{ $val }}" class="d-none dia-check">
                                        <span class="dia-btn">{{ $label }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Horário</label>
                                <input type="time" name="horario" id="semanalHorario" class="form-control">
                                <small class="text-muted">Horário padrão das aulas</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Duração do Plano</label>
                                <select name="duracao_meses" id="semanalDuracao" class="form-select">
                                    <option value="1">1 mês</option>
                                    <option value="3">3 meses</option>
                                    <option value="6">6 meses</option>
                                    <option value="12">12 meses / Anual</option>
                                    <option value="">Sem prazo definido</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Aulas por semana</label>
                                <input type="number" name="aulas_semana" id="semanalAulas" class="form-control"
                                       min="1" max="7" readonly
                                       placeholder="Calculado pelos dias"
                                       style="background:#f8fafc;">
                                <small class="text-muted">Calculado pelos dias selecionados</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Observações</label>
                                <textarea name="descricao" id="semanalDescricao" class="form-control" rows="2"
                                          placeholder="Ex.: Inclui avaliação postural mensal..."></textarea>
                            </div>
                        </div>

                        {{-- Preview --}}
                        <div id="semanalPreview" class="mt-3 p-3 rounded" style="background:#eff6ff;border:1px solid #bfdbfe;display:none;">
                            <div class="fw-semibold text-primary mb-1" style="font-size:.85rem;">📋 Resumo do plano</div>
                            <div id="semanalPreviewTexto" class="text-muted" style="font-size:.85rem;"></div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i><span id="semanalBtnTexto">Criar Plano Semanal</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ── ABA: PLANO LIVRE ── --}}
                <div id="painelLivre" style="display:none;">
                    <form id="formLivre"
                          method="POST"
                          action="{{ route('alunos.planos.store') }}">
                        @csrf
                        <input type="hidden" name="tipo" value="livre">
                        <input type="hidden" name="id" id="livreId">

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nome do Plano <span class="text-danger">*</span></label>
                                <input type="text" name="nome" id="livreNome" class="form-control"
                                       placeholder="Ex.: Pacote Avulso, Mensal Livre..." required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Valor (R$) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" step="0.01" min="0" name="valor" id="livreValor"
                                           class="form-control" placeholder="0,00" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Modalidade de cobrança</label>
                                <select name="periodicidade" id="livrePeriodicidade" class="form-select"
                                        onchange="toggleVigencia()">
                                    <option value="avulso">Avulso (pagamento único)</option>
                                    <option value="mensal">Mensal</option>
                                    <option value="trimestral">Trimestral</option>
                                    <option value="semestral">Semestral</option>
                                    <option value="anual">Anual</option>
                                </select>
                            </div>

                            <div class="col-md-6" id="campoVigencia">
                                <label class="form-label fw-semibold">Vigência (dias)</label>
                                <input type="number" name="duracao_dias" id="livreDuracao" class="form-control"
                                       min="1" placeholder="Ex.: 30, 90, 365">
                                <small class="text-muted">Quanto tempo o plano é válido após ativação</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Descrição do Plano</label>
                                <textarea name="descricao" id="livreDescricao" class="form-control" rows="4"
                                          placeholder="Descreva o que está incluído no plano, regras, benefícios..."></textarea>
                                <small class="text-muted">Esta descrição aparece para o aluno na contratação</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i><span id="livreBtnTexto">Criar Plano Livre</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>{{-- /modal-body --}}
        </div>
    </div>
</div>

{{-- ════════ CSS ════════ --}}
<style>
.btn-tab {
    flex: 1; display: flex; align-items: center; gap: 10px;
    padding: 10px 16px; border: none; background: transparent;
    border-radius: 8px; cursor: pointer; text-align: left;
    transition: background .15s, color .15s;
    color: #64748b;
}
.btn-tab.active {
    background: #fff;
    color: #1e40af;
    box-shadow: 0 1px 6px rgba(0,0,0,.1);
}
.btn-tab.active i { color: #2563eb; }
.btn-tab i { font-size: 1.1rem; }

/* Dias da semana */
.dia-pill { cursor: pointer; }
.dia-btn {
    display: inline-block; min-width: 44px; text-align: center;
    padding: 7px 10px; border-radius: 8px; font-size: .82rem; font-weight: 600;
    border: 2px solid #e2e8f0; color: #64748b; background: #f8fafc;
    transition: all .15s; user-select: none;
}
.dia-check:checked + .dia-btn {
    background: #2563eb; border-color: #2563eb; color: #fff;
}
</style>

{{-- ════════ JS ════════ --}}
<script>
/* ── Trocar abas ─────────────────────────────────────── */
function trocarAba(aba) {
    document.getElementById('painelSemanal').style.display = aba === 'semanal' ? 'block' : 'none';
    document.getElementById('painelLivre').style.display   = aba === 'livre'   ? 'block' : 'none';
    document.getElementById('tabSemanal').classList.toggle('active', aba === 'semanal');
    document.getElementById('tabLivre').classList.toggle('active',   aba === 'livre');
}

/* ── Contar dias selecionados ────────────────────────── */
document.querySelectorAll('.dia-check').forEach(cb => {
    cb.addEventListener('change', () => {
        const total = document.querySelectorAll('.dia-check:checked').length;
        document.getElementById('semanalAulas').value = total || '';
        atualizarPreview();
    });
});

/* ── Preview dinâmico (aba semanal) ─────────────────── */
function atualizarPreview() {
    const nome    = document.getElementById('semanalNome').value;
    const valor   = document.getElementById('semanalValor').value;
    const horario = document.getElementById('semanalHorario').value;
    const duracao = document.getElementById('semanalDuracao').value;

    const diasMarcados = [...document.querySelectorAll('.dia-check:checked')]
        .map(c => ({seg:'Segunda',ter:'Terça',qua:'Quarta',qui:'Quinta',sex:'Sexta',sab:'Sábado',dom:'Domingo'}[c.value] ?? c.value));

    const preview = document.getElementById('semanalPreview');
    const texto   = document.getElementById('semanalPreviewTexto');

    if (!nome && !diasMarcados.length) { preview.style.display = 'none'; return; }
    preview.style.display = 'block';

    const duracaoLabel = {1:'1 mês',3:'3 meses',6:'6 meses',12:'12 meses','':`sem prazo`}[duracao] ?? '';
    texto.innerHTML = `
        <strong>${nome || '—'}</strong> · R$ ${valor ? parseFloat(valor).toFixed(2).replace('.',',') : '—'}<br>
        📅 ${diasMarcados.length ? diasMarcados.join(', ') : '—'}
        ${horario ? ` às ${horario}` : ''}
        ${duracaoLabel ? ` · ${duracaoLabel}` : ''}
    `;
}

['semanalNome','semanalValor','semanalHorario','semanalDuracao'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', atualizarPreview);
    document.getElementById(id)?.addEventListener('change', atualizarPreview);
});

/* ── Vigência visível só quando não avulso ────────────── */
function toggleVigencia() {
    const p = document.getElementById('livrePeriodicidade').value;
    document.getElementById('campoVigencia').style.display = p === 'avulso' ? 'none' : 'block';
}
toggleVigencia();

/* ── Editar plano existente ──────────────────────────── */
document.querySelectorAll('.btn-editar-plano').forEach(btn => {
    btn.addEventListener('click', function() {
        const p = JSON.parse(this.dataset.plano);
        document.getElementById('modalTitulo').textContent = 'Editar Plano';

        if (p.dias_semana) {
            // É semanal
            trocarAba('semanal');
            document.getElementById('semanalId').value      = p.id;
            document.getElementById('semanalNome').value    = p.nome;
            document.getElementById('semanalValor').value   = p.valor;
            document.getElementById('semanalHorario').value = p.horario ?? '';
            document.getElementById('semanalDescricao').value = p.descricao ?? '';

            const dias = JSON.parse(p.dias_semana || '[]');
            document.querySelectorAll('.dia-check').forEach(cb => {
                cb.checked = dias.includes(cb.value);
                cb.dispatchEvent(new Event('change'));
            });

            document.getElementById('semanalBtnTexto').textContent = 'Salvar Alterações';
            document.getElementById('formSemanal').action = `/admin/planos/${p.id}`;
            // Adiciona _method PUT
            let m = document.getElementById('semanalMethod');
            if (!m) {
                m = document.createElement('input');
                m.type = 'hidden'; m.name = '_method'; m.id = 'semanalMethod';
                document.getElementById('formSemanal').appendChild(m);
            }
            m.value = 'PUT';

        } else {
            // É livre
            trocarAba('livre');
            document.getElementById('livreId').value          = p.id;
            document.getElementById('livreNome').value         = p.nome;
            document.getElementById('livreValor').value        = p.valor;
            document.getElementById('livreDescricao').value    = p.descricao ?? '';
            document.getElementById('livreDuracao').value      = p.duracao_dias ?? '';
            document.getElementById('livrePeriodicidade').value = p.periodicidade ?? 'avulso';
            toggleVigencia();

            document.getElementById('livreBtnTexto').textContent = 'Salvar Alterações';
            document.getElementById('formLivre').action = `/admin/planos/${p.id}`;
            let m = document.getElementById('livreMethod');
            if (!m) {
                m = document.createElement('input');
                m.type = 'hidden'; m.name = '_method'; m.id = 'livreMethod';
                document.getElementById('formLivre').appendChild(m);
            }
            m.value = 'PUT';
        }
    });
});

/* ── Limpar modal ao fechar ──────────────────────────── */
document.getElementById('modalPlano').addEventListener('hidden.bs.modal', () => {
    document.getElementById('modalTitulo').textContent = 'Novo Plano';
    document.getElementById('formSemanal').reset();
    document.getElementById('formLivre').reset();
    document.getElementById('formSemanal').action = '{{ route("alunos.planos.store") }}';
    document.getElementById('formLivre').action   = '{{ route("alunos.planos.store") }}';
    document.querySelectorAll('.dia-check').forEach(cb => cb.checked = false);
    document.getElementById('semanalPreview').style.display = 'none';
    document.getElementById('semanalBtnTexto').textContent = 'Criar Plano Semanal';
    document.getElementById('livreBtnTexto').textContent   = 'Criar Plano Livre';
    ['semanalMethod','livreMethod'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.remove();
    });
    trocarAba('semanal');
    toggleVigencia();
});
</script>

</x-admin.layout>
