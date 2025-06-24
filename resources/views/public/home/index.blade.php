<x-public.layout title="HOME">
    <style>
        .input-icon {
            position: relative;
        }

        .input-icon>i {
            position: absolute;
            right: 10px;
            top: 15px;
        }

        .input-icon>.form-control {
            padding-left: 30px;
            /* Ajuste esse valor conforme necessário */
        }
    </style>
    <!-- Breadcrumb -->

    <x-home.breadcrumb title="Busque Seu Passeio " />
    <!-- /Breadcrumb -->

    <!-- Page Content -->
    <style>
        /* Filter Card Styling */
        .search-filter {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .search-filter:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background: linear-gradient(45deg, #00c4e0, #007a99);
            color: white;
            padding: 20px;
            border-bottom: none;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Input Styling */
        .filter-widget {
            margin-bottom: 20px;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #007a99;
            font-size: 1.2rem;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 12px 20px 12px 45px;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .form-control:focus {
            border-color: #00c4e0;
            box-shadow: 0 0 10px rgba(0, 196, 224, 0.3);
            background: white;
        }

        /* Checkbox Styling */
        .custom_check {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 0.95rem;
            color: #003b4d;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .custom_check:hover {
            color: #00c4e0;
        }

        .custom_check input {
            display: none;
        }

        .checkmark {
            width: 20px;
            height: 20px;
            border: 2px solid #007a99;
            border-radius: 5px;
            margin-right: 10px;
            position: relative;
            transition: all 0.3s ease;
        }

        .custom_check input:checked+.checkmark {
            background: linear-gradient(45deg, #00c4e0, #007a99);
            border-color: #007a99;
        }

        .custom_check input:checked+.checkmark::after {
            content: '\f00c';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
        }

        /* Button Styling */
        .btn-search .buscar_empresa {
            background: linear-gradient(45deg, #ff6f61, #ff3d00);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-search .buscar_empresa:hover {
            background: linear-gradient(45deg, #ff3d00, #d32f2f);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 61, 0, 0.4);
        }

        .btn-search .buscar_empresa::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: all 0.5s ease;
        }

        .btn-search .buscar_empresa:hover::before {
            left: 100%;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .search-filter {
                margin-bottom: 30px;
            }

            .card-header {
                padding: 15px;
            }

            .card-title {
                font-size: 1.3rem;
            }

            .form-control {
                padding: 10px 15px 10px 40px;
                font-size: 0.9rem;
            }

            .input-icon i {
                font-size: 1.1rem;
                left: 12px;
            }

            .custom_check {
                font-size: 0.9rem;
            }

            .checkmark {
                width: 18px;
                height: 18px;
            }

            .btn-search .buscar_empresa {
                padding: 10px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .card-header {
                padding: 12px;
            }

            .card-title {
                font-size: 1.2rem;
            }

            .form-control {
                padding: 8px 12px 8px 35px;
                font-size: 0.85rem;
            }

            .input-icon i {
                font-size: 1rem;
                left: 10px;
            }
        }

        /* Animations */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .search-filter {
            animation: slideInLeft 0.5s ease-out;
        }
    </style>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-4 col-xl-3 theiaStickySidebar">
                    <!-- Search Filter -->
                    <div class="card search-filter">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Filtro</h4>
                        </div>
                        <div class="card-body">
                            <div class="filter-widget">
                                <div class="input-icon">
                                    <i class="fas fa-building"></i>
                                    <input type="text" class="form-control" id="nome_empresa"
                                        placeholder="Buscar Escola">
                                </div>
                            </div>
                            <div class="filter-widget">
                                <h4>Modalidade</h4>
                                @isset($modalidade)
                                    @foreach ($modalidade as $value)
                                        <div>
                                            <label class="custom_check">
                                                <input type="checkbox" name="gender_type" data-type="{{ $value->id }}"
                                                    class="filter_empresa">
                                                <span class="checkmark"></span> {{ $value->nome }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endisset
                            </div>
                            <div class="btn-search">
                                <button type="button" class="btn w-100 buscar_empresa"
                                    id="buscar_empresa">Buscar</button>
                            </div>
                        </div>
                    </div>
                    <!-- /Search Filter -->
                </div>

                <div class="col-md-12 col-lg-8 col-xl-9 listar_empresas">
                    @foreach ($model as $value)
                        <x-home.cardprofissional :value="$value" />
                    @endforeach
                    <div class="load-more text-center">
                        <a class="btn btn-primary btn-sm prime-btn" href="javascript:void(0);">Carregar Mais</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
    <script src="{{ asset('admin/js/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>4




    </x-layoutsadmin>
