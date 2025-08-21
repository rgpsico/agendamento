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
                @include('admin.empresas._partials.editEmpresaModal')

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
                                                                    data-cep="{{ $empresa->endereco->cep ?? '' }}"
                                                                    data-endereco="{{ $empresa->endereco->endereco ?? '' }}"
                                                                    data-numero="{{ $empresa->endereco->numero ?? '' }}"
                                                                    data-bairro="{{ $empresa->endereco->bairro ?? '' }}"
                                                                    data-cidade="{{ $empresa->endereco->cidade ?? '' }}"
                                                                    data-estado="{{ $empresa->endereco->estado ?? '' }}"
                                                                    data-uf="{{ $empresa->endereco->uf ?? '' }}"
                                                                    data-pais="{{ $empresa->endereco->pais ?? '' }}"
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
        // Configurar CSRF para AJAX (aplicado apenas às requisições locais)
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Função para consultar ViaCEP
        function consultarCEP(cep) {
            // Remover caracteres não numéricos do CEP
            cep = cep.replace(/\D/g, '');

            if (cep.length === 8) { // Verifica se o CEP tem 8 dígitos
                $.ajax({
                    url: `https://viacep.com.br/ws/${cep}/json/`,
                    type: 'GET',
                    dataType: 'json',               
                    success: function(data) {
                        if (!data.erro) {
                            // Preencher os campos com os dados retornados
                            $('#endereco').val(data.logradouro || '');
                            $('#bairro').val(data.bairro || '');
                            $('#cidade').val(data.localidade || '');
                            $('#estado').val(data.uf || '');
                            $('#uf').val(data.uf || '');
                            $('#pais').val('Brasil');
                        } else {
                            alert('CEP não encontrado. Por favor, verifique o CEP digitado.');
                            // Limpar os campos de endereço
                            $('#endereco').val('');
                            $('#bairro').val('');
                            $('#cidade').val('');
                            $('#estado').val('');
                            $('#uf').val('');
                            $('#pais').val('');
                        }
                    },
                    error: function() {
                        alert('Erro ao consultar o CEP. Tente novamente mais tarde.');
                    }
                });
            }
        }

        // Evento de mudança no campo CEP
        $('#cep').on('blur', function() {
            let cep = $(this).val();
            if (cep) {
                consultarCEP(cep);
            }
        });

        // Aplicar máscara ao campo CEP (formato 99999-999)
        $('#cep').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.length > 5) {
                $(this).val(value.replace(/(\d{5})(\d{1,3})/, '$1-$2'));
            } else {
                $(this).val(value);
            }
        });

        // Preencher formulário ao clicar no botão de editar
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
            $('#modalidade_id').val($(this).data('modalidade_id'));

            // Preencher campos de endereço
            $('#cep').val($(this).data('cep'));
            $('#endereco').val($(this).data('endereco'));
            $('#numero').val($(this).data('numero'));
            $('#bairro').val($(this).data('bairro'));
            $('#cidade').val($(this).data('cidade'));
            $('#estado').val($(this).data('estado'));
            $('#uf').val($(this).data('uf'));
            $('#pais').val($(this).data('pais'));

            // Definir action do formulário
            $('#editEmpresaForm').attr('action', `/cliente/empresa/update/${empresaId}`);

            console.log('Modalidade selecionada:', $(this).data('modalidade_id'));
        });

        // Submissão do formulário via AJAX
        $('#editEmpresaForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert('Empresa atualizada com sucesso!');
                    $('#editEmpresaModal').modal('hide');
                    location.reload();
                },
                error: function(response) {
                    console.log(response)
                
            });
        });
    });
</script>
</x-admin.layout>
