<style>
        /* ESTILOS APENAS PARA O MODAL */
        
        /* Modal Backdrop */
        .modal-backdrop {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
        }

        /* Modal Container */
        #registerModal .modal-content {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            background: white;
            position: relative;
        }

        #registerModal .modal-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4f46e5, #06b6d4, #10b981);
        }

        #registerModal .modal-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-bottom: 1px solid #e2e8f0;
            padding: 2rem 2rem 1.5rem;
            position: relative;
        }

        #registerModal .modal-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        #registerModal .modal-title i {
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        #registerModal .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            opacity: 0.6;
            transition: all 0.3s ease;
            padding: 0.5rem;
            border-radius: 50%;
        }

        #registerModal .btn-close:hover {
            opacity: 1;
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            transform: scale(1.1);
        }

        #registerModal .modal-body {
            padding: 0;
        }

        /* Tabs Styling */
        #registerModal .nav-tabs {
            border: none;
            background: #f8fafc;
            padding: 0.5rem;
            margin: 1.5rem 2rem 0;
            border-radius: 16px;
            display: flex;
            gap: 0.5rem;
        }

        #registerModal .nav-tabs .nav-link {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: #64748b;
            background: transparent;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            flex: 1;
            text-align: center;
        }

        #registerModal .nav-tabs .nav-link:hover {
            color: #4f46e5;
            background: rgba(79, 70, 229, 0.1);
            transform: translateY(-2px);
        }

        #registerModal .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: white;
            box-shadow: 0 8px 25px -5px rgba(79, 70, 229, 0.4);
            transform: translateY(-2px);
        }

        #registerModal .nav-tabs .nav-link i {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        /* Form Container */
        #registerModal .tab-content {
            padding: 2rem;
            background: white;
        }

        #registerModal .tab-pane {
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Styling */
        #registerModal .form-label {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        #registerModal .form-label i {
            color: #4f46e5;
            width: 16px;
        }

        #registerModal .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        #registerModal .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            background: white;
            transform: translateY(-1px);
        }

        #registerModal .form-control:hover {
            border-color: #4338ca;
            background: white;
        }

        #registerModal .mb-3 {
            margin-bottom: 1.5rem !important;
        }

        /* Button Styling */
        #registerModal .btn-register {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px -5px rgba(79, 70, 229, 0.4);
        }

        #registerModal .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        #registerModal .btn-register:hover::before {
            left: 100%;
        }

        #registerModal .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px -5px rgba(79, 70, 229, 0.5);
            background: linear-gradient(135deg, #4338ca, #4f46e5);
        }

        #registerModal .btn-register:active {
            transform: translateY(-1px);
        }

        #registerModal .btn-register i {
            margin-right: 0.5rem;
        }

        /* Success/Error States */
        #registerModal .form-control.is-valid {
            border-color: #10b981;
            background-image: none;
        }

        #registerModal .form-control.is-invalid {
            border-color: #ef4444;
            background-image: none;
        }

        #registerModal .invalid-feedback,
        #registerModal .valid-feedback {
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        /* Loading State */
        #registerModal .btn-register.loading {
            position: relative;
            color: transparent;
            pointer-events: none;
        }

        #registerModal .btn-register.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive para o modal */
        @media (max-width: 768px) {
            #registerModal .modal-dialog {
                margin: 1rem;
                max-width: calc(100% - 2rem);
            }

            #registerModal .modal-header,
            #registerModal .tab-content {
                padding: 1.5rem;
            }

            #registerModal .nav-tabs {
                margin: 1rem 1.5rem 0;
            }

            #registerModal .nav-tabs .nav-link {
                padding: 0.875rem 1rem;
                font-size: 0.9rem;
            }

            #registerModal .modal-title {
                font-size: 1.5rem;
            }
        }

        /* Estilo básico para a página de demonstração */
    
    </style>



<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registerModalLabel">Registre-se</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="registerTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="aluno-tab" data-bs-toggle="tab" data-bs-target="#aluno" type="button" role="tab" aria-controls="aluno" aria-selected="true">Aluno</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="professor-tab" data-bs-toggle="tab" data-bs-target="#professor" type="button" role="tab" aria-controls="professor" aria-selected="false">Professor</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="registerTabContent">
                            <!-- Aluno Form -->
                            <div class="tab-pane fade show active" id="aluno" role="tabpanel" aria-labelledby="aluno-tab">
                                <form action="{{ route('register.aluno') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="alunoNome" class="form-label">Nome Completo</label>
                                        <input type="text" class="form-control" id="alunoNome" name="nome" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="alunoEmail" class="form-label">E-mail</label>
                                        <input type="email" class="form-control" id="alunoEmail" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="alunoSenha" class="form-label">Senha</label>
                                        <input type="password" class="form-control" id="alunoSenha" name="senha" required minlength="6">
                                    </div>
                                    <div class="mb-3">
                                        <label for="alunoConfirmarSenha" class="form-label">Confirmar Senha</label>
                                        <input type="password" class="form-control" id="alunoConfirmarSenha" name="confirmar_senha" required minlength="6">
                                    </div>
                                    <input type="hidden" name="tipo_usuario" value="Aluno">
                                    <button type="submit" class="btn btn-register w-100">Cadastrar como Aluno</button>
                                </form>
                            </div>
                            <!-- Professor Form -->
                            <div class="tab-pane fade" id="professor" role="tabpanel" aria-labelledby="professor-tab">
                                <form action="{{ route('register.professor') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="professorNome" class="form-label">Nome Completo</label>
                                        <input type="text" class="form-control" id="professorNome" name="nome" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="professorEmail" class="form-label">E-mail</label>
                                        <input type="email" class="form-control" id="professorEmail" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="professorSenha" class="form-label">Senha</label>
                                        <input type="password" class="form-control" id="professorSenha" name="senha" required minlength="6">
                                    </div>
                                    <div class="mb-3">
                                        <label for="professorConfirmarSenha" class="form-label">Confirmar Senha</label>
                                        <input type="password" class="form-control" id="professorConfirmarSenha" name="confirmar_senha" required minlength="6">
                                    </div>
                                    <div class="mb-3">
                                        <label for="professorEspecialidade" class="form-label">Especialidade</label>
                                        <input type="text" class="form-control" id="professorEspecialidade" name="especialidade" required>
                                    </div>
                                    <input type="hidden" name="tipo_usuario" value="Professor">
                                    <button type="submit" class="btn btn-register w-100">Cadastrar como Professor</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>