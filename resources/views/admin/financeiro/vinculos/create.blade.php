<x-admin.layout title="{{ $pageTitle }}">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <x-header.titulo pageTitle="{{ $pageTitle }}" />

            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Dados do Pagamento</h5>
                            <span class="badge bg-primary">Fluxo Manual</span>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Aluno</label>
                                        <select class="form-select">
                                            <option value="" selected disabled>Selecione o aluno</option>
                                            @foreach ($alunos as $aluno)
                                                <option value="{{ $aluno['id'] }}">{{ $aluno['nome'] }} • {{ $aluno['documento'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Plano</label>
                                        <select class="form-select">
                                            <option value="" selected disabled>Selecione o plano</option>
                                            @foreach ($planos as $plano)
                                                <option value="{{ $plano['id'] }}">{{ $plano['nome'] }} • {{ $plano['duracao'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Método de Pagamento</label>
                                        <select class="form-select">
                                            <option value="" selected disabled>Escolha</option>
                                            @foreach ($metodosPagamento as $metodo)
                                                <option value="{{ $metodo }}">{{ $metodo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Data do Pagamento</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Valor Recebido</label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="text" class="form-control" value="249,90">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Status do Pagamento</label>
                                        <select class="form-select">
                                            <option>Pago</option>
                                            <option>Pendente</option>
                                            <option>Aguardando Confirmação</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Próxima Fatura</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Observações</label>
                                        <textarea class="form-control" rows="3" placeholder="Informe detalhes importantes sobre o pagamento ou condições do plano"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.financeiro.vinculos.index') }}" class="btn btn-light border">
                            <i class="fe fe-arrow-left"></i> Voltar
                        </a>
                        <div>
                            <button type="button" class="btn btn-outline-primary me-2">
                                <i class="fe fe-save"></i> Salvar como Rascunho
                            </button>
                            <button type="button" class="btn btn-success">
                                <i class="fe fe-check-circle"></i> Confirmar Pagamento
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white border-0">
                            <h6 class="card-title mb-0">Resumo do Plano</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-md rounded-circle bg-light text-primary d-flex align-items-center justify-content-center me-3">
                                    <i class="fe fe-layers"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Plano Selecionado</h6>
                                    <small class="text-muted">Atualizado automaticamente</small>
                                </div>
                            </div>
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span>Duração</span>
                                    <span class="fw-semibold">12 meses</span>
                                </li>
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span>Valor Original</span>
                                    <span class="fw-semibold">R$ 249,90</span>
                                </li>
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span>Desconto Aplicado</span>
                                    <span class="fw-semibold text-success">R$ 20,00</span>
                                </li>
                                <li class="d-flex justify-content-between py-2">
                                    <span>Total a Receber</span>
                                    <span class="fw-bold">R$ 229,90</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-header bg-white border-0">
                            <h6 class="card-title mb-0">Linha do Tempo</h6>
                        </div>
                        <div class="card-body">
                            <ul class="timeline list-unstyled mb-0">
                                <li class="position-relative ps-4 pb-3">
                                    <span class="timeline-point bg-success"></span>
                                    <p class="mb-0 fw-semibold">05/05/2024</p>
                                    <small class="text-muted">Pagamento confirmado via cartão</small>
                                </li>
                                <li class="position-relative ps-4 pb-3">
                                    <span class="timeline-point bg-info"></span>
                                    <p class="mb-0 fw-semibold">05/06/2024</p>
                                    <small class="text-muted">Renovação automática prevista</small>
                                </li>
                                <li class="position-relative ps-4">
                                    <span class="timeline-point bg-secondary"></span>
                                    <p class="mb-0 fw-semibold">Disponível</p>
                                    <small class="text-muted">Adicione outras interações do aluno com o plano</small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .timeline-point {
            position: absolute;
            left: 0;
            top: 4px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .timeline li::before {
            content: '';
            position: absolute;
            left: 4px;
            top: 0;
            bottom: -12px;
            width: 2px;
            background: #e9ecef;
        }
        .timeline li:last-child::before {
            display: none;
        }
    </style>
</x-admin.layout>
