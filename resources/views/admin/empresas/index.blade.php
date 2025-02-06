<x-admin.layout title="{{$pageTitle}}">
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
            <x-header.titulo pageTitle="{{$pageTitle}}"/>
            <!-- /Page Header -->

            <!-- Modal de Edição -->
            <div id="editEmpresaModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Empresa</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editEmpresaForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="empresa_id" name="empresa_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" required>
                                </div>

                                <div class="form-group">
                                    <label for="descricao">Descrição</label>
                                    <textarea class="form-control" id="descricao" name="descricao" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="telefone">Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" required>
                                </div>

                                <div class="form-group">
                                    <label for="cnpj">CNPJ</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj" required>
                                </div>

                                <div class="form-group">
                                    <label for="valor_aula_de">Valor Aula (De)</label>
                                    <input type="text" class="form-control" id="valor_aula_de" name="valor_aula_de" required>
                                </div>

                                <div class="form-group">
                                    <label for="valor_aula_ate">Valor Aula (Até)</label>
                                    <input type="text" class="form-control" id="valor_aula_ate" name="valor_aula_ate" required>
                                </div>

                                <div class="form-group">
                                    <label for="modalidade_id">Modalidade</label>
                                    <select class="form-control" id="modalidade_id" name="modalidade_id" required>
                                        <option value="">Selecione uma modalidade</option>
                                        @foreach($modalidades as $modalidade)
                                            <option value="{{ $modalidade->id }}">{{ $modalidade->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="avatar">Avatar</label>
                                    <input type="file" class="form-control" id="avatar" name="avatar">
                                </div>

                                <div class="form-group">
                                    <label for="banner">Banner</label>
                                    <input type="file" class="form-control" id="banner" name="banner">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
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
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Empresa</th>
                                            <th>Email</th>
                                            <th>Telefone</th>
                                            <th>Data de Vencimento</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($empresas as $empresa)
                                        <tr>
                                            <td>{{ $empresa->id }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ asset('admin/img/patients/patient15.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $empresa->nome }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $empresa->email }}</td>
                                            <td>{{ $empresa->telefone }}</td>
                                            <td>{{ $empresa->data_vencimento }}</td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill bg-success inv-badge">Ativo</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <!-- Botão de Editar -->
                                                    <button class="btn btn-sm bg-success-light editEmpresaBtn"
                                                        data-id="{{ $empresa->id }}"
                                                        data-nome="{{ $empresa->nome }}"
                                                        data-descricao="{{ $empresa->descricao }}"
                                                        data-telefone="{{ $empresa->telefone }}"
                                                        data-cnpj="{{ $empresa->cnpj }}"
                                                        data-valor_aula_de="{{ $empresa->valor_aula_de }}"
                                                        data-valor_aula_ate="{{ $empresa->valor_aula_ate }}"
                                                        data-modalidade_id="{{ $empresa->modalidade_id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editEmpresaModal">
                                                        <i class="fe fe-pencil"></i> Editar
                                                    </button>
                                            
                                                    <!-- Botão de Ver -->
                                                    <a class="btn btn-sm bg-info-light" href="{{ route('empresa.show', $empresa->id) }}">
                                                        <i class="fe fe-eye"></i> Ver
                                                    </a>
                                            
                                                    <!-- Botão de Excluir -->
                                                    <form action="{{ route('empresa.destroy', $empresa->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm bg-danger-light">
                                                            <i class="fe fe-trash"></i> Excluir
                                                        </button>
                                                    </form>
                                            
                                                    <!-- Botão de WhatsApp -->
                                                    <a class="btn btn-sm bg-success-light" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $empresa->telefone) }}" target="_blank">
                                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                                    </a>
                                                </div>
                                            </td>
                                            
                                        </tr>
                                        @endforeach
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.editEmpresaBtn').click(function() {
                let empresaId = $(this).data('id');
                $('#empresa_id').val(empresaId);
                $('#nome').val($(this).data('nome'));
                $('#descricao').val($(this).data('descricao'));
                $('#telefone').val($(this).data('telefone'));
                $('#cnpj').val($(this).data('cnpj'));
                $('#valor_aula_de').val($(this).data('valor_aula_de'));
                $('#valor_aula_ate').val($(this).data('valor_aula_ate'));
                $('#modalidade_id').val($(this).data('modalidade_id'));

                $('#editEmpresaForm').attr('action', `/cliente/empresa/update/${empresaId}`);
            });

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
                        location.reload();
                    },
                    error: function(response) {
                        alert('Erro ao atualizar empresa. Verifique os dados.');
                    }
                });
            });
        });
    </script>
</x-layoutsadmin>
