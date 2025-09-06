<style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --accent-color: #06d6a0;
            --success-color: #10b981;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f8fafc;
            --border-light: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Global Styles */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .container {
            max-width: 1200px;
        }

        /* Main Container */
        .main-booking-container {
            background: white;
            border-radius: 24px;
            box-shadow: var(--shadow-xl);
            margin: 2rem auto;
            overflow: hidden;
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Profile Section */
        .profile-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 3rem 0;
            position: relative;
            overflow: hidden;
        }

        .profile-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="70" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
            opacity: 0.3;
        }

        .booking-doc-info {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .booking-doc-info:hover {
            transform: translateY(-5px);
        }

        .booking-doc-img img,
        .usuario-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .booking-doc-img img:hover,
        .usuario-img:hover {
            transform: scale(1.05);
        }

        .booking-info h4 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .booking-info h4 a {
            color: white;
            text-decoration: none;
        }

        .booking-info .text-muted {
            color: rgba(255, 255, 255, 0.8) !important;
            font-size: 1.1rem;
        }

        /* Services Section */
        .services-section {
            padding: 3rem 0;
            background: var(--bg-light);
        }

        .services-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 3rem;
            position: relative;
        }

        .services-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            border-radius: 2px;
        }

        /* Service Cards */
        .selection-wrapper {
            height: 100%;
            transition: all 0.3s ease;
        }

        .card_servicos {
            background: white;
            border: 2px solid transparent;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            height: 100%;
            position: relative;
        }

        .card_servicos:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-color);
        }

        .selected-label {
            display: block;
            cursor: pointer;
            margin: 0;
            height: 100%;
        }

        .selected-label input[type="radio"] {
            display: none;
        }

        .selected-label .icon {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 24px;
            height: 24px;
            border: 2px solid var(--border-light);
            border-radius: 50%;
            background: white;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .selected-label .icon::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .selected-label input:checked+.icon {
            background: var(--accent-color);
            border-color: var(--accent-color);
        }

        .selected-label input:checked+.icon::after {
            opacity: 1;
        }

        .selected-label input:checked~.selected-content {
            background: linear-gradient(135deg, rgba(6, 214, 160, 0.05) 0%, rgba(99, 102, 241, 0.05) 100%);
        }

        .card_servicos:has(input:checked) {
            border-color: var(--accent-color);
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .selected-content {
            padding: 0;
            height: 100%;
            transition: all 0.3s ease;
        }

        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card_servicos:hover .card-img-top {
            transform: scale(1.05);
        }

        .selected-content h4 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 1rem 1.5rem 0.5rem;
        }

        .selected-content h5 {
            color: var(--text-light);
            font-size: 0.9rem;
            font-weight: 400;
            line-height: 1.5;
            margin: 0 1.5rem 1.5rem;
        }

        /* Schedule Section */
        .schedule-section {
            padding: 3rem 0;
            background: white;
        }

        .schedule-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 2rem;
            position: relative;
        }

        .schedule-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            border-radius: 2px;
        }

        .date-display {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
        }

        .date-display h4 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .date-display p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
        }

        /* Schedule Widget */
        .booking-schedule {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .schedule-header {
            background: var(--bg-light);
            padding: 1rem;
            border-bottom: 1px solid var(--border-light);
        }

        .day-slot ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            gap: 2rem;
        }

        .day-slot a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .day-slot a:hover {
            background: var(--secondary-color);
            transform: scale(1.1);
        }

        .schedule-cont {
            padding: 2rem;
        }

        .time-slot ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
        }

        .time-slot li {
            background: white;
            border: 2px solid var(--border-light);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            float: none !important;
            width: auto !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .time-slot li:hover {
            border-color: var(--primary-color);
            background: rgba(99, 102, 241, 0.05);
            transform: translateY(-2px);
        }

        .time-slot li.selected {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Spinner */
        #spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
            margin: 2rem 0;
        }

        /* Submit Section */
        .submit-section {
            background: var(--bg-light);
            padding: 3rem 0;
        }

        .submit-btn {
            background: linear-gradient(135deg, var(--accent-color) 0%, #00b894 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            min-width: 200px;
        }

        .submit-btn:hover:not(.disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(6, 214, 160, 0.3);
            color: white;
        }

        .submit-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: var(--text-light);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-booking-container {
                margin: 1rem;
                border-radius: 16px;
            }

            .profile-section {
                padding: 2rem 0;
            }

            .booking-doc-info {
                text-align: center;
                padding: 1.5rem;
            }

            .services-section,
            .schedule-section {
                padding: 2rem 0;
            }

            .services-title,
            .schedule-title {
                font-size: 1.5rem;
            }

            .time-slot ul {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
                gap: 0.5rem;
            }

            .submit-section {
                text-align: center !important;
            }
        }

        /* Animation for cards */
        .card_servicos {
            animation: slideInUp 0.6s ease forwards;
            opacity: 0;
        }

        .card_servicos:nth-child(1) {
            animation-delay: 0.1s;
        }

        .card_servicos:nth-child(2) {
            animation-delay: 0.2s;
        }

        .card_servicos:nth-child(3) {
            animation-delay: 0.3s;
        }

        .card_servicos:nth-child(4) {
            animation-delay: 0.4s;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@isset($empresa)
    

<div class="modal fade" id="edit_personal_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('empresa.update',['id' =>$empresa->id ]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="user_id" value="{{ $empresa->user_id }}">
                    
                    <div class="row form-row">
                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" class="form-control" name="nome" value="{{ $empresa->nome }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" name="email" value="{{ $empresa->user->email }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Data de cadastro</label>
                                <div class="cal-icon">
                                    <input type="text" class="form-control datetimepicker" name="data_created" value="{{ $empresa->created_at->format('d/m/Y H:i') }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Telefone</label>
                                <input type="text" name="telefone" class="form-control" value="{{ $empresa->telefone }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>CPF/CNPJ</label>
                                <input type="text" name="cnpj" class="form-control" value="{{ $empresa->cnpj ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label>Descrição</label>
                                <textarea class="form-control" name="descricao">{{ $empresa->descricao ?? '' }}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Valor Aula De</label>
                                <input type="text" name="valor_aula_de" class="form-control" value="{{ $empresa->valor_aula_de ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Valor Aula Até</label>
                                <input type="text" name="valor_aula_ate" class="form-control" value="{{ $empresa->valor_aula_ate ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label>Modalidade</label>
                                <select class="form-control" name="modalidade_id">
                                    <!-- Aqui você deve listar as modalidades disponíveis -->
                                    <option value="{{ $empresa->modalidade_id }}" selected>Atual</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Avatar</label>
                                <input type="file" class="form-control" name="avatar">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Banner</label>
                                <input type="file" class="form-control" name="banner">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <h5 class="form-title"><span>Endereço</span></h5>
                        </div>                       
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Cep</label>
                                <input type="text" class="form-control" name="cep" id="cep" value="{{ $empresa->endereco->cep ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Endereço</label>
                                <input type="text" class="form-control" name="endereco" id="endereco" value="{{ $empresa->endereco->endereco ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Cidade</label>
                                <input type="text" class="form-control" name="cidade" id="cidade" value="{{ $empresa->endereco->cidade ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <input type="text" class="form-control" name="estado" id="estado" value="{{ $empresa->endereco->estado ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Uf</label>
                                <input type="text" class="form-control" name="uf" id="uf" value="{{ $empresa->endereco->uf ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Pais</label>
                                <input type="text" class="form-control" name="pais" id="pais" value="{{ $empresa->endereco->pais ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endisset