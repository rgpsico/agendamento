<x-admin.layout title="Sites da Empresa" >
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">
            <!-- Page Header with Fade-In Animation -->
            <div class="page-header" id="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Meus Sites</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Admin</a></li>
                            <li class="breadcrumb-item active">Sites</li>
                        </ul>
                    </div>
                </div>
            </div>

            @if($sites->count() < 3)
                <div class="text-right mb-3">
                    <a href="{{ route('admin.site.create') }}" class="btn btn-primary btn-animated">
                        <i class="fas fa-plus"></i> Novo Site
                    </a>
                </div>
            @endif

            <x-alert/>

            <!-- Filter Form with Slide-In Animation -->
            <div class="card mb-4" id="filter-card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.site.lista') }}" class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="titulo" value="{{ request('titulo') }}" 
                                   class="form-control filter-input" placeholder="Título">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="slug" value="{{ request('slug') }}" 
                                   class="form-control filter-input" placeholder="Slug">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="data_inicial" value="{{ request('data_inicial') }}" 
                                   class="form-control filter-input">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="data_final" value="{{ request('data_final') }}" 
                                   class="form-control filter-input">
                        </div>
                        <div class="col-md-12 text-right mt-2">
                            <button type="submit" class="btn btn-primary btn-animated">Filtrar</button>
                            <a href="{{ route('admin.site.lista') }}" class="btn btn-secondary btn-animated">Limpar</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table with Staggered Row Animations -->
            <div class="card card-table" id="site-table">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-center mb-0">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Slug</th>
                                    <th>Criado em</th>
                                    <th>Visualizações</th>
                                    <th>Cliques WhatsApp</th>
                                    <th>Visitantes</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($sites as $site)
                                <tr class="table-row">
                                    <td>{{ $site->titulo }}</td>
                                    <td>{{ $site->slug }}</td>
                                    <td>{{ $site->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $site->visualizacoes_count ?? 0 }}</td>
                                    <td>{{ $site->cliques_whatsapp_count ?? 0 }}</td>
                                    <td>{{ $site->visitantes_count ?? 0 }}</td>
                                    <td>
                                        <a href="{{ route('admin.site.edit', $site->id) }}" class="btn btn-sm btn-warning btn-animated">Editar</a>
                                        <a href="{{ route('site.publico', $site->slug) }}" target="_blank" class="btn btn-sm btn-success btn-animated">Ver Site</a>
                                        <form action="{{ route('admin.site.destroy', $site->id) }}" method="POST" style="display:inline;" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-animated delete-btn">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Nenhum site cadastrado ainda.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $sites->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include GSAP and Custom CSS/JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <style>
        /* Custom Styles for Enhanced Look */
        .page-wrapper {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .page-title {
            color: #2c3e50;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .btn-animated {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-animated:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .table-row {
            transition: background-color 0.3s ease;
        }
        .table-row:hover {
            background-color: #e9ecef;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 8px;
        }
    </style>
    <script>
        // GSAP Animations
        document.addEventListener('DOMContentLoaded', () => {
            // Animate Page Header
            gsap.from('#page-header', {
                duration: 1,
                y: -50,
                opacity: 0,
                ease: 'power3.out'
            });

            // Animate Filter Card
            gsap.from('#filter-card', {
                duration: 1,
                x: -100,
                opacity: 0,
                ease: 'power3.out',
                delay: 0.3
            });

            // Animate Filter Inputs (Staggered)
            gsap.from('.filter-input', {
                duration: 0.8,
                y: 20,
                opacity: 0,
                stagger: 0.2,
                ease: 'power2.out',
                delay: 0.5
            });

            // Animate Table
            gsap.from('#site-table', {
                duration: 1,
                y: 50,
                opacity: 0,
                ease: 'power3.out',
                delay: 0.7
            });

            // Animate Table Rows (Staggered)
            gsap.from('.table-row', {
                duration: 0.8,
                y: 20,
                opacity: 0,
                stagger: 0.1,
                ease: 'power2.out',
                delay: 1
            });

            // Animate Buttons on Hover
            document.querySelectorAll('.btn-animated').forEach(button => {
                button.addEventListener('mouseenter', () => {
                    gsap.to(button, {
                        scale: 1.05,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });
                button.addEventListener('mouseleave', () => {
                    gsap.to(button, {
                        scale: 1,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });
            });
        });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Existing GSAP animations remain unchanged...

        // Add confirmation for delete buttons
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                if (confirm('Tem certeza que deseja excluir este site? Esta ação não pode ser desfeita.')) {
                    button.closest('form').submit();
                }
            });
        });
    });
</script>
</x-admin.layout>