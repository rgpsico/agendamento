<x-admin.layout title="Editar Serviço de Bot">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 2rem">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Editar Serviço de Bot"/>
            <!-- /Page Header -->

            <x-alert/>

            <form action="{{ route('admin.botservice.update', $botService->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                
                <!-- Card Principal -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header bg-gradient-warning text-white py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-edit me-2 fs-5"></i>
                            <h5 class="card-title mb-0 fw-bold">Editar Informações do Serviço de Bot</h5>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Info Card -->
                        <div class="alert alert-info border-0 mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2 fs-5"></i>
                                <div>
                                    <strong>Editando:</strong> {{ $botService->nome_servico }}
                                    <br>
                                    <small class="text-muted">ID: #{{ $botService->id }} | Criado em: {{ $botService->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            
                            <!-- Seleção do Bot -->
                            <div class="col-md-6">
                                <label for="bot_id" class="form-label fw-semibold">
                                    <i class="fas fa-robot text-primary me-1"></i>
                                    Bot <span class="text-danger">*</span>
                                </label>
                                <select id="bot_id" name="bot_id" class="form-select form-select-lg @error('bot_id') is-invalid @enderror" required>
                                    <option value="">Selecione um bot...</option>
                                    @foreach($bots as $bot)
                                        <option value="{{ $bot->id }}" {{ old('bot_id', $botService->bot_id) == $bot->id ? 'selected' : '' }}>
                                            {{ $bot->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bot_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Bot atualmente selecionado: <strong>{{ $botService->bot->nome ?? 'N/A' }}</strong>
                                </div>
                            </div>

                            <!-- Seleção do Serviço -->
                            <div class="col-md-6">
    <label for="servico_id" class="form-label fw-semibold">
        <i class="fas fa-cog text-success me-1"></i>
        Serviço <span class="text-danger">*</span>
    </label>
    <select id="servico_id" name="servico_id" class="form-select form-select-lg @error('servico_id') is-invalid @enderror" required>
        <option value="">Selecione um serviço...</option>
        @foreach($servicos as $servico)
            <option value="{{ $servico->id }}" 
                {{ old('servico_id', $botService->servico_id) == $servico->id ? 'selected' : '' }}>
                {{ $servico->titulo }}
            </option>
        @endforeach
    </select>
    @error('servico_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text">
        <i class="fas fa-info-circle me-1"></i>
        Serviço atual: <strong>{{ $botService->servico->titulo ?? ($botService->nome_servico ?? 'N/A') }}</strong>
    </div>
</div>


                            <!-- Nome do Serviço -->
                            <div class="col-12">
                                <label for="nome_servico" class="form-label fw-semibold">
                                    <i class="fas fa-tag text-info me-1"></i>
                                    Nome do Serviço <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       id="nome_servico" 
                                       name="nome_servico" 
                                       class="form-control form-control-lg @error('nome_servico') is-invalid @enderror" 
                                       value="{{ old('nome_servico', $botService->nome_servico) }}" 
                                       placeholder="Digite o nome específico do serviço..."
                                       maxlength="255"
                                       required>
                                @error('nome_servico')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Nome específico que identifica este serviço (máx. 255 caracteres)
                                </div>
                            </div>

                            <!-- Professor -->
                            <div class="col-md-6">
                                <label for="professor" class="form-label fw-semibold">
                                    <i class="fas fa-chalkboard-teacher text-warning me-1"></i>
                                    Professor/Responsável
                                </label>
                                <input type="text" 
                                       id="professor" 
                                       name="professor" 
                                       class="form-control form-control-lg @error('professor') is-invalid @enderror" 
                                       value="{{ old('professor', $botService->professor) }}" 
                                       placeholder="Nome do professor ou responsável..."
                                       maxlength="255">
                                @error('professor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Campo opcional (máx. 255 caracteres)
                                    @if($botService->professor)
                                        <br><strong>Atual:</strong> {{ $botService->professor }}
                                    @endif
                                </div>
                            </div>

                            <!-- Horário -->
                            <div class="col-md-6">
                                <label for="horario" class="form-label fw-semibold">
                                    <i class="fas fa-clock text-secondary me-1"></i>
                                    Horário
                                </label>
                                <input type="text" 
                                       id="horario" 
                                       name="horario" 
                                       class="form-control form-control-lg @error('horario') is-invalid @enderror" 
                                       value="{{ old('horario', $botService->horario) }}" 
                                       placeholder="Ex: Segunda a Sexta, 08:00 às 17:00"
                                       maxlength="100">
                                @error('horario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Horário de funcionamento do serviço (máx. 100 caracteres)
                                    @if($botService->horario)
                                        <br><strong>Atual:</strong> {{ $botService->horario }}
                                    @endif
                                </div>
                            </div>

                            <!-- Valor -->
                            <div class="col-md-12">
                                <label for="valor" class="form-label fw-semibold">
                                    <i class="fas fa-dollar-sign text-success me-1"></i>
                                    Valor <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-dollar-sign text-success"></i>
                                    </span>
                                    <input type="number" 
                                           id="valor" 
                                           name="valor" 
                                           class="form-control @error('valor') is-invalid @enderror" 
                                           value="{{ old('valor', $botService->valor) }}" 
                                           placeholder="0.00"
                                           step="0.01"
                                           min="0"
                                           required>
                                    @error('valor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Valor do serviço em reais (mínimo R$ 0,00)
                                    <br><strong>Valor atual:</strong> <span class="text-success fw-bold">R$ {{ number_format($botService->valor, 2, ',', '.') }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Card de Histórico/Metadados -->
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-history me-2 text-muted"></i>
                            Informações do Registro
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-sm">
                            <div class="col-md-4">
                                <strong>Criado em:</strong><br>
                                <span class="text-muted">{{ $botService->created_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Última atualização:</strong><br>
                                <span class="text-muted">{{ $botService->updated_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>ID do registro:</strong><br>
                                <span class="text-muted">#{{ $botService->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="card shadow border-0">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                <small><i class="fas fa-asterisk text-danger me-1"></i>Campos obrigatórios</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.botservice.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                                <button type="reset" class="btn btn-outline-warning btn-lg px-4">
                                    <i class="fas fa-undo me-2"></i>Restaurar
                                </button>
                                <button type="submit" class="btn btn-warning btn-lg px-4 shadow-sm">
                                    <i class="fas fa-save me-2"></i>Atualizar Serviço
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <!-- Estilos CSS customizados -->
    <style>
        .bg-gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .card {
            border-radius: 15px;
            transition: transform 0.2s ease-in-out;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #f5576c;
            box-shadow: 0 0 0 0.2rem rgba(245, 87, 108, 0.25);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-warning:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(245, 87, 108, 0.3);
            color: white;
        }
        
        .btn-outline-warning:hover {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-color: #f5576c;
            color: white;
        }
        
        .form-label {
            margin-bottom: 0.75rem;
        }
        
        .form-text {
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }
        
        .input-group-text {
            border-color: #dee2e6;
        }
        
        .card-header i {
            font-size: 1.1rem;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
            border-left: 4px solid #667eea;
        }
        
        .text-sm {
            font-size: 0.875rem;
        }
        
        @media (max-width: 768px) {
            .content {
                padding: 1rem !important;
            }
            
            .d-flex.gap-2 {
                flex-direction: column;
            }
            
            .d-flex.gap-2 .btn {
                width: 100%;
            }
        }
    </style>

    <!-- JavaScript para validação e funcionalidades -->
    <script>
        // Validação do formulário
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
        
        // Máscara para o campo de valor
        document.getElementById('valor').addEventListener('input', function(e) {
            let value = e.target.value;
            // Remove caracteres não numéricos exceto ponto
            value = value.replace(/[^0-9.]/g, '');
            // Garante apenas um ponto decimal
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            // Limita a 2 casas decimais
            if (parts[1] && parts[1].length > 2) {
                value = parts[0] + '.' + parts[1].slice(0, 2);
            }
            e.target.value = value;
        });

        // Confirmação antes de restaurar o formulário
        document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
            if (!confirm('Tem certeza que deseja restaurar todos os campos para os valores originais?')) {
                e.preventDefault();
            }
        });

        // Destaque para campos modificados
        document.addEventListener('DOMContentLoaded', function() {
            const formFields = document.querySelectorAll('input, select, textarea');
            
            formFields.forEach(field => {
                const originalValue = field.value;
                
                field.addEventListener('input', function() {
                    if (this.value !== originalValue) {
                        this.classList.add('border-warning');
                    } else {
                        this.classList.remove('border-warning');
                    }
                });
            });
        });
    </script>
</x-admin.layout>