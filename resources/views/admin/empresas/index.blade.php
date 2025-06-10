<x-admin.layout title="{{ $pageTitle }}">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <x-header.titulo pageTitle="{{ $pageTitle }}" />
            <!-- /Page Header -->

            <!-- Filtros -->
            <div class="row mb-4">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Filtros</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('empresa.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nome">Nome da Empresa</label>
                                            <input type="text" class="form-control" id="nome" name="nome"
                                                value="{{ request('nome') }}" placeholder="Digite o nome...">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="modalidade_id">Modalidade</label>
                                            <select class="form-control" id="modalidade_id" name="modalidade_id">
                                                <option value="">Todas as modalidades</option>
                                                @foreach ($modalidades as $modalidade)
                                                    <option value="{{ $modalidade->id }}"
                                                        {{ request('modalidade_id') == $modalidade->id ? 'selected' : '' }}>
                                                        {{ $modalidade->nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="">Todos os status</option>
                                                <option value="ativo"
                                                    {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                                <option value="inativo"
                                                    {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> </label>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fe fe-search"></i> Filtrar
                                                </button>
                                                <a href="{{ route('empresa.index') }}" class="btn btn-secondary">
                                                    <i class="fe fe-refresh-cw"></i> Limpar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Filtros -->

            <!-- Modal de Edição -->
            <!DOCTYPE html>
            <html lang="pt-BR">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Modal Empresa - Exemplo</title>
                <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css"
                    rel="stylesheet">
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
            </head>

            <body>

                <!-- Modal de Edição -->
                <div id="editEmpresaModal" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Empresa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Fechar"></button>
                            </div>
                            <form id="editEmpresaForm" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" id="empresa_id" name="empresa_id">

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="nome_empresa" class="form-label">Nome</label>
                                                <input type="text" class="form-control" id="nome_empresa"
                                                    name="nome" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="email_empresa" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email_empresa"
                                                    name="email" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="site_url" class="form-label">Site URL</label>
                                                <input type="url" class="form-control" id="site_url"
                                                    name="site_url">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="telefone" class="form-label">Telefone</label>
                                                <input type="text" class="form-control" id="telefone"
                                                    name="telefone" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="cnpj" class="form-label">CNPJ</label>
                                                <input type="text" class="form-control" id="cnpj"
                                                    name="cnpj" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="data_vencimento" class="form-label">Data
                                                    Vencimento</label>
                                                <input type="text" class="form-control"
                                                    id="data_vencimento_empresa" name="data_vencimento" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="descricao" class="form-label">Descrição</label>
                                        <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="valor_aula_de" class="form-label">Valor Aula (De)</label>
                                                <input type="text" class="form-control" id="valor_aula_de"
                                                    name="valor_aula_de" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="valor_aula_ate" class="form-label">Valor Aula
                                                    (Até)</label>
                                                <input type="text" class="form-control" id="valor_aula_ate"
                                                    name="valor_aula_ate" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="modalidade_id" class="form-label">Modalidade</label>
                                        <select class="form-control" id="modalidade_id" name="modalidade_id"
                                            required>
                                            <option value="">Selecione uma modalidade</option>
                                            @foreach ($modalidades as $modalidade)
                                                <option value="{{ $modalidade->id }}"
                                                    {{ old('modalidade_id', $empresa->modalidade_id ?? '') == $modalidade->id ? 'selected' : '' }}>
                                                    {{ $modalidade->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="avatar" class="form-label">Avatar</label>
                                                <input type="file" class="form-control" id="avatar"
                                                    name="avatar" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="banner" class="form-label">Banner</label>
                                                <input type="file" class="form-control" id="banner"
                                                    name="banner" accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Fechar</button>
                                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tabela de Empresas -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Exibir mensagem de erro -->
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="datatable table table-hover table-center mb-0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Empresa</th>
                                                <th>Email</th>
                                                <th>Telefone</th>
                                                <th>Modalidade</th>
                                                <th>Data de Vencimento</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($empresas as $empresa)
                                                <tr>
                                                    <td>{{ $empresa->id }}</td>
                                                    <td>
                                                        <h2 class="table-avatar">
                                                            <a href="#" class="avatar avatar-sm me-2">
                                                                <img class="avatar-img rounded-circle"
                                                                    src="{{ $empresa->avatar ? asset('avatar/' . $empresa->avatar) : asset('admin/img/patients/patient15.jpg') }}"
                                                                    alt="User Image">
                                                            </a>
                                                            <a href="#">{{ $empresa->nome }}</a>
                                                        </h2>
                                                    </td>
                                                    <td>{{ $empresa->email }}</td>
                                                    <td>{{ $empresa->telefone }}</td>
                                                    <td>{{ $empresa->modalidade->nome ?? 'N/A' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($empresa->data_vencimento)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if (\Carbon\Carbon::parse($empresa->data_vencimento)->isFuture())
                                                            <span
                                                                class="badge rounded-pill bg-success inv-badge">Ativo</span>
                                                        @else
                                                            <span
                                                                class="badge rounded-pill bg-danger inv-badge">Inativo</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="actions">
                                                            <!-- Botão de Editar -->
                                                            <button class="btn btn-sm bg-success-light editEmpresaBtn"
                                                                data-id="{{ $empresa->id }}"
                                                                data-nome="{{ $empresa->nome }}"
                                                                data-email="{{ $empresa->user->email }}"
                                                                data-site_url="{{ $empresa->site_url }}"
                                                                data-descricao="{{ $empresa->descricao }}"
                                                                data-telefone="{{ $empresa->telefone }}"
                                                                data-cnpj="{{ $empresa->cnpj }}"
                                                                data-valor_aula_de="{{ $empresa->valor_aula_de }}"
                                                                data-valor_aula_ate="{{ $empresa->valor_aula_ate }}"
                                                                data-modalidade_id="{{ $empresa->modalidade_id ?? '' }}"
                                                                data-data_vencimento="{{ $empresa->data_vencimento }}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editEmpresaModal">
                                                                <i class="fe fe-pencil"></i> Editar
                                                            </button>

                                                            <!-- Botão de Ver -->
                                                            <a class="btn btn-sm bg-info-light"
                                                                href="{{ route('empresa.show', $empresa->id) }}">
                                                                <i class="fe fe-eye"></i> Ver
                                                            </a>

                                                            <!-- Botão de Excluir -->
                                                            <form
                                                                action="{{ route('empresa.destroy', $empresa->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm bg-danger-light">
                                                                    <i class="fe fe-trash"></i> Excluir
                                                                </button>
                                                            </form>

                                                            <!-- Botão de WhatsApp -->
                                                            <a class="btn btn-sm bg-success-light"
                                                                href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $empresa->telefone) }}"
                                                                target="_blank">
                                                                <i class="fab fa-whatsapp"></i> WhatsApp
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">Nenhuma empresa encontrada
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    {{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Adicionar no início do seu script, dentro do $(document).ready
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.editEmpresaBtn').click(function() {
                let empresaId = $(this).data('id');

                // Preencher campos do formulário
                $('#empresa_id').val(empresaId);
                $('#nome_empresa').val($(this).data('nome'));
                $('#email_empresa').val($(this).data('email'));
                $('#site_url').val($(this).data('site_url'));
                $('#descricao').val($(this).data('descricao'));
                $('#telefone').val($(this).data('telefone'));
                $('#cnpj').val($(this).data('cnpj'));
                $('#data_vencimento_empresa').val($(this).data('data_vencimento'));
                $('#valor_aula_de').val($(this).data('valor_aula_de'));
                $('#valor_aula_ate').val($(this).data('valor_aula_ate'));

                $('#modalidade_id option[value="' + $(this).data('modalidade_id') + '"]').prop('selected',
                    true);
                // Selecionar modalidade correta
                $('#modalidade_id').val($(this).data('modalidade_id'));

                // Definir action do formulário
                $('#editEmpresaForm').attr('action', `/cliente/empresa/update/${empresaId}`);

                console.log('Modalidade selecionada:', $(this).data('modalidade_id'));
            });

            $('#editEmpresaForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST', // Laravel aceita _method=PUT
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert('Empresa atualizada com sucesso!');
                        $('#editEmpresaModal').modal('hide');
                        location.reload();
                    },
                    error: function(response) {
                        let errorMsg = 'Erro ao atualizar empresa: ';
                        if (response.responseJSON && response.responseJSON.message) {
                            errorMsg += response.responseJSON.message;
                        } else if (response.responseJSON && response.responseJSON.errors) {
                            let errors = Object.values(response.responseJSON.errors).flat();
                            errorMsg += errors.join(', ');
                        } else {
                            errorMsg += 'Verifique os dados.';
                        }
                        alert(errorMsg);
                    }
                });
            });
        });
    </script>
</x-admin.layout>
