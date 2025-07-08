<x-admin.layout title="Cadastrar Empresa">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Modal de boas-vindas -->
            <div class="modal fade" id="welcomeModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow-lg border-0">
                        <div class="modal-header border-0 bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-star me-2"></i>
                                Bem-vindo!
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="text-center mb-3">
                                <i class="fas fa-building text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <p class="text-muted mb-3">Esse é um cadastro simples para configurar seus serviços.</p>
                            <p class="text-muted mb-0">Com ele iremos gerenciar seus alunos e otimizar os agendamentos.
                            </p>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="btn btn-primary btn-lg px-4" data-bs-dismiss="modal">
                                <i class="fas fa-arrow-right me-2"></i>
                                Vamos começar!
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <x-header.titulo pageTitle="{{ $pageTitle }}" />

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0 text-primary">
                                <i class="fas fa-building me-2"></i>
                                Cadastro de Empresa
                            </h4>
                            <p class="text-muted mb-0 mt-1">Complete as informações em etapas simples</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="step-indicator active" data-step="0">
                                <div class="step-circle">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <span class="step-label">Informações Gerais</span>
                            </div>
                            <div class="progress-line"></div>
                            <div class="step-indicator" data-step="1">
                                <div class="step-circle">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <span class="step-label">Endereço</span>
                            </div>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"
                                role="progressbar" style="width: 50%;" id="progressBar"></div>
                        </div>
                    </div>

                    <x-alert />

                    <form
                        action="{{ isset($model) ? route('empresa.update', ['id' => $model->id]) : route('empresa.store') }}"
                        method="POST" enctype="multipart/form-data" id="empresaForm">
                        @csrf

                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
                        <input type="hidden" name="empresa_id" value="{{ Auth::user()->empresa->id ?? '' }}" />

                        <div id="empresaStepper">
                            <!-- Step 1: Informações Gerais -->
                            <div class="step-content active" data-step="0">
                                <div class="step-header mb-4">
                                    <h5 class="text-primary mb-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Informações Gerais
                                    </h5>
                                    <p class="text-muted">Preencha os dados básicos da sua empresa</p>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <x-text-input name="nome" size="30" label="Nome Completo"
                                            :value="$model->nome ?? ''" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-text-input name="email" size="30" label="Email" :value="$model->user->email ?? ''" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-select-modalidade label="Modalidade" :model="$model" :modalidades="$modalidades" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-text-input name="cnpj" size="30" label="CPF/CNPJ"
                                            :value="$model->cnpj ?? ''" />
                                    </div>
                                    <div class="col-12">
                                        <x-text-area name="descricao" label="Descrição" :model="$model" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-text-input name="telefone" size="30" label="Telefone"
                                            :value="$model->telefone ?? ''" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-text-input name="valor_aula_de" size="30" label="Preço Mínimo aula"
                                            :value="$model->valor_aula_de ?? ''" placeholder="R$ 0,00" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-text-input name="valor_aula_ate" size="30" label="Preço Máximo aula"
                                            :value="$model->valor_aula_ate ?? ''" placeholder="R$ 0,00" />
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Endereço -->
                            <div class="step-content" data-step="1">
                                <div class="step-header mb-4">
                                    <h5 class="text-primary mb-2">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        Endereço
                                    </h5>
                                    <p class="text-muted">Informe o endereço da sua empresa</p>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <x-text-input name="cep" size="15" label="CEP" :value="$model->endereco->cep ?? ''"
                                            id="cep" />
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Digite o CEP para preenchimento automático
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <x-text-input name="endereco" size="30" label="Endereço"
                                            :value="$model->endereco->endereco ?? ''" id="endereco" />
                                    </div>
                                    {{-- <div class="col-md-3">
                                        <x-text-input name="numero" size="10" label="Número" :value="$model->endereco->numero ?? ''"
                                            id="numero" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-text-input name="bairro" size="20" label="Bairro" :value="$model->endereco->bairro ?? ''"
                                            id="bairro" />
                                    </div> --}}
                                    <div class="col-md-4">
                                        <x-text-input name="cidade" size="20" label="Cidade" :value="$model->endereco->cidade ?? ''"
                                            id="cidade" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-text-input name="estado" size="20" label="Estado" :value="$model->endereco->estado ?? ''"
                                            id="estado" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-text-input name="uf" size="5" label="UF" :value="$model->endereco->uf ?? ''"
                                            id="uf" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-text-input name="pais" size="20" label="País" :value="$model->endereco->pais ?? 'Brasil'"
                                            id="pais" />
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="d-flex justify-content-between mt-5 pt-4 border-top">
                                <button type="button" class="btn btn-outline-secondary btn-lg px-4" id="prevBtn"
                                    style="display: none;">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Anterior
                                </button>
                                <div class="ms-auto">
                                    <button type="button" class="btn btn-primary btn-lg px-4" id="nextBtn">
                                        Próximo
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                    <button type="submit" class="btn btn-success btn-lg px-4" id="submitBtn"
                                        style="display: none;">
                                        <i class="fas fa-save me-2"></i>
                                        Salvar Empresa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Carregando...</span>
        </div>
    </div>

    <style>
        .step-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .step-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #e9ecef;
            border: 3px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #6c757d;
            transition: all 0.3s ease;
            margin-bottom: 10px;
        }

        .step-indicator.active .step-circle {
            background: #007bff;
            border-color: #007bff;
            color: white;
            box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
        }

        .step-indicator.completed .step-circle {
            background: #28a745;
            border-color: #28a745;
            color: white;
        }

        .step-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #6c757d;
            text-align: center;
            margin-top: 5px;
        }

        .step-indicator.active .step-label {
            color: #007bff;
            font-weight: 600;
        }

        .progress-line {
            flex: 1;
            height: 3px;
            background: #dee2e6;
            margin: 30px 20px 0 20px;
            border-radius: 2px;
            position: relative;
        }

        .step-content {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .step-content.active {
            display: block;
        }

        .step-header {
            border-bottom: 2px solid #f8f9fa;
            padding-bottom: 1rem;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .btn {
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        #loadingSpinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        /* Animação para o loading do CEP */
        .loading-cep {
            position: relative;
        }

        .loading-cep::after {
            content: '';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            border: 2px solid #007bff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: translateY(-50%) rotate(0deg);
            }

            100% {
                transform: translateY(-50%) rotate(360deg);
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentStep = 0;
            const totalSteps = 2;

            // Mostrar modal de boas-vindas
            $('#welcomeModal').modal('show');

            // Função para validar campo obrigatório
            function validateRequired(selector) {
                let isValid = true;
                $(selector).each(function() {
                    const $field = $(this);
                    const value = $field.val().trim();

                    if (!value) {
                        $field.addClass('is-invalid');
                        isValid = false;
                    } else {
                        $field.removeClass('is-invalid');
                    }
                });
                return isValid;
            }

            // Função para validar step atual
            function validateCurrentStep() {
                if (currentStep === 0) {
                    return validateRequired(
                        '#empresaForm input[name="nome"], #empresaForm input[name="email"], #empresaForm input[name="cnpj"]'
                    );
                } else if (currentStep === 1) {
                    return validateRequired('#empresaForm input[name="cep"], #empresaForm input[name="endereco"]');
                }
                return true;
            }

            // Função para mostrar step
            function showStep(step) {
                // Ocultar todos os steps
                $('.step-content').removeClass('active');

                // Mostrar step atual
                $(`.step-content[data-step="${step}"]`).addClass('active');

                // Atualizar indicadores
                $('.step-indicator').removeClass('active completed');

                // Marcar steps anteriores como completos
                for (let i = 0; i < step; i++) {
                    $(`.step-indicator[data-step="${i}"]`).addClass('completed');
                }

                // Marcar step atual como ativo
                $(`.step-indicator[data-step="${step}"]`).addClass('active');

                // Atualizar progress bar
                const progressPercent = ((step + 1) / totalSteps) * 100;
                $('#progressBar').css('width', progressPercent + '%');

                // Gerenciar botões
                if (step === 0) {
                    $('#prevBtn').hide();
                } else {
                    $('#prevBtn').show();
                }

                if (step === totalSteps - 1) {
                    $('#nextBtn').hide();
                    $('#submitBtn').show();
                } else {
                    $('#nextBtn').show();
                    $('#submitBtn').hide();
                }
            }

            // Evento do botão próximo
            $('#nextBtn').on('click', function() {
                if (validateCurrentStep()) {
                    if (currentStep < totalSteps - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                } else {
                    // Mostrar toast de erro
                    showToast('Por favor, preencha todos os campos obrigatórios!', 'error');
                }
            });

            // Evento do botão anterior
            $('#prevBtn').on('click', function() {
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            // Busca CEP
            $('#cep').on('blur', function() {
                const cep = $(this).val().replace(/\D/g, '');
                const $cepField = $(this);

                if (cep.length === 8) {
                    $cepField.addClass('loading-cep');

                    axios.get(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => {
                            const data = response.data;

                            if (!data.erro) {
                                $('#endereco').val(data.logradouro);
                                $('#bairro').val(data.bairro);
                                $('#cidade').val(data.localidade);
                                $('#estado').val(data.uf);
                                $('#uf').val(data.uf);
                                $('#pais').val('Brasil');

                                // Remover classe de erro se existir
                                $cepField.removeClass('is-invalid');

                                showToast('CEP encontrado com sucesso!', 'success');
                            } else {
                                showToast('CEP não encontrado!', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Erro ao buscar CEP:', error);
                            showToast('Erro ao buscar CEP. Tente novamente.', 'error');
                        })
                        .finally(() => {
                            $cepField.removeClass('loading-cep');
                        });
                } else if (cep.length > 0) {
                    showToast('CEP deve ter 8 dígitos!', 'error');
                }
            });

            // Função para mostrar toast
            function showToast(message, type = 'info') {
                const toastHtml = `
                    <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                                ${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                `;

                // Criar container de toast se não existir
                if (!$('#toastContainer').length) {
                    $('body').append(
                        '<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>'
                    );
                }

                const $toast = $(toastHtml);
                $('#toastContainer').append($toast);

                // Inicializar e mostrar toast
                const toast = new bootstrap.Toast($toast[0]);
                toast.show();

                // Remover toast após ser ocultado
                $toast.on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }

            // Remover classe de erro quando usuário digita
            $('#empresaForm input, #empresaForm textarea, #empresaForm select').on('input change', function() {
                $(this).removeClass('is-invalid');
            });

            // Formatação de valores monetários
            // $('input[name="valor_aula_de"], input[name="valor_aula_ate"]').on('input', function() {
            //     let value = $(this).val().replace(/\D/g, '');
            //     value = (value / 100).toFixed(2);
            //     value = value.replace('.', ',');
            //     value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            //     $(this).val('R$ ' + value);
            // });

            // Formatação de telefone
            $('input[name="telefone"]').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                } else {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                }
                $(this).val(value);
            });

            // Formatação de CPF/CNPJ
            $('input[name="cnpj"]').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                } else {
                    value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
                }
                $(this).val(value);
            });

            // Formatação de CEP
            $('input[name="cep"]').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
                $(this).val(value);
            });

            // Animação no submit
            $('#empresaForm').on('submit', function() {
                $('#submitBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-2"></i>Salvando...');
            });

            // Inicializar primeiro step
            showStep(currentStep);
        });
    </script>
</x-admin.layout>
