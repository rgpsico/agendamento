<x-admin.layout title="Listar Permissões">

    {{-- Inclui modais --}}
   

    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-10">
                        <h3 class="page-title">Permissões</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Admin</a></li>
                            <li class="breadcrumb-item"><a href="javascript:(0);">Permissões</a></li>
                        </ul>
                    </div>
                    <div class="col-2 text-end">
                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_create_permission">
                            <i class="fe fe-plus"></i> Nova Permissão
                        </a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Guard</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions as $perm)
                                            <tr class="linha_id-{{ $perm->id }}">
                                                <td>{{ $perm->id }}</td>
                                                <td>{{ $perm->name }}</td>
                                                <td>{{ $perm->guard_name }}</td>
                                                <td class="text-center">
                                                    <div class="actions">
                                                        <a class="btn btn-sm bg-info-light"
                                                            data-nome="{{ $perm->name }}"
                                                            data-guard="{{ $perm->guard_name }}"
                                                            data-id="{{ $perm->id }}"
                                                            id="editar_bt_permission">
                                                            <i class="fe fe-pencil"></i> Editar
                                                        </a>
                                                        <a data-bs-toggle="modal"
                                                            href="#delete_modal_permission"
                                                            data-id="{{ $perm->id }}"
                                                            class="btn btn-sm bg-danger-light bt_excluir_permission">
                                                            <i class="fe fe-trash"></i> Excluir
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

    {{-- Modal para Criar Permissão --}}
    <div class="modal fade" id="modal_create_permission" tabindex="-1" aria-labelledby="modalCreatePermissionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreatePermissionLabel">Criar Permissão</h5>
                    <button type="button" class="btn""
    <xaiArtifact artifact_id="0bb00ed0-e2e1-4e79-9b36-085bf393b850" artifact_version_id="9eab2b3a-99e1-4c08-a665-de5151a11e28" title="listar_permissoes.blade.php" contentType="text/html">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_permission_form">
                        <div class="mb-3">
                            <label for="create_nome_permission" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="create_nome_permission" required>
                        </div>
                        <div class="mb-3">
                            <label for="create_guard_permission" class="form-label">Guard</label>
                            <input type="text" class="form-control" id="create_guard_permission" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="salvar_create_permission">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Editar Permissão --}}
    <div class="modal fade" id="modal_editar_permission" tabindex="-1" aria-labelledby="modalEditarPermissionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarPermissionLabel">Editar Permissão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editar_permission_form">
                        <input type="hidden" id="permission_id">
                        <div class="mb-3">
                            <label for="nome_permission" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome_permission" required>
                        </div>
                        <div class="mb-3">
                            <label for="guard_permission" class="form-label">Guard</label>
                            <input type="text" class="form-control" id="guard_permission" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="salvar_editar_permission">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Excluir Permissão --}}
    <div class="modal fade" id="delete_modal_permission" tabindex="-1" aria-labelledby="deleteModalPermissionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalPermissionLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir esta permissão?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger confirmar_exclusao_permission" data_id="">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('admin/js/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('request.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Excluir
        $(document).on("click", ".bt_excluir_permission", function() {
            var id = $(this).data('id');
            $('.confirmar_exclusao_permission').attr('data_id', id);
            $("#delete_modal_permission").modal('show');
        });

        $(document).on("click", ".confirmar_exclusao_permission", function() {
            var id = $(this).attr('data_id');
            $.ajax({
                url: '/api/permissions/delete/' + id,
                type: 'POST',
                success: function() {
                    $("#delete_modal_permission").modal('hide');
                    $(".linha_id-" + id).fadeOut();
                },
                error: function(e) {
                    console.log(e);
                    alert('Erro ao excluir permissão!');
                }
            });
        });

        // Editar
        $(document).on("click", "#editar_bt_permission", function() {
            $("#modal_editar_permission").modal('show');
            $(".modal-title_permission").text('Editar Permissão');
            var nome = $(this).data('nome');
            var guard = $(this).data('guard');
            var id = $(this).data('id');
            $("#nome_permission").val(nome);
            $("#guard_permission").val(guard);
            $("#permission_id").val(id);
        });

        $(document).on("click", "#salvar_editar_permission", function() {
            var nome = $('#nome_permission').val();
            var guard = $('#guard_permission').val();
            var id = $('#permission_id').val();

            $.ajax({
                url: '/api/permissions/update/' + id,
                method: 'PUT',
                data: { name: nome, guard_name: guard },
                success: function() {
                    $('.linha_id-' + id).find('td:eq(1)').text(nome);
                    $('.linha_id-' + id).find('td:eq(2)').text(guard);
                    alert('Permissão atualizada com sucesso!');
                    $('#modal_editar_permission').modal('hide');
                },
                error: function(xhr) {
                    alert('Erro ao atualizar permissão!');
                }
            });
        });

        // Criar
        $(document).on("click", "#salvar_create_permission", function() {
            var nome = $('#create_nome_permission').val();
            var guard = $('#create_guard_permission').val();

            $.ajax({
                url: '/api/permissions/store',
                method: 'POST',
                data: { name: nome, guard_name: guard },
                success: function(response) {
                    $('.datatable tbody').append(`
                        <tr class="linha_id-${response.id}">
                            <td>${response.id}</td>
                            <td>${response.name}</td>
                            <td>${response.guard_name}</td>
                            <td class="text-center">
                                <div class="actions">
                                    <a class="btn btn-sm bg-info-light"
                                        data-nome="${response.name}"
                                        data-guard="${response.guard_name}"
                                        data-id="${response.id}"
                                        id="editar_bt_permission">
                                        <i class="fe fe-pencil"></i> Editar
                                    </a>
                                    <a data-bs-toggle="modal"
                                        href="#delete_modal_permission"
                                        data-id="${response.id}"
                                        class="btn btn-sm bg-danger-light bt_excluir_permission">
                                        <i class="fe fe-trash"></i> Excluir
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `);

                    $('#modal_create_permission').modal('hide');
                    $('#create_nome_permission').val('');
                    $('#create_guard_permission').val('');
                    alert('Permissão criada com sucesso!');
                },
                error: function(xhr) {
                    alert('Erro ao criar permissão!');
                }
            });
        });
    </script>

</x-admin.layout>