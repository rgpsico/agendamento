<x-admin.layout title="Listar Permissões">

    {{-- Inclui modais --}}
    {{-- <x-modal_editar_permission/> --}}

     @include('components.modal-editar-permission')
    @include('components.modal-delete')
    @include('components.modal-create-permission')
    
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
                    <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_create">
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
                                                            id="editar_bt">
                                                            <i class="fe fe-pencil"></i> Editar
                                                        </a>
                                                        <a data-bs-toggle="modal"
                                                            href="#delete_modal"
                                                            data-id="{{ $perm->id }}"
                                                            class="btn btn-sm bg-danger-light bt_excluir">
                                                            <i class="fe fe-trash"></i> Excluir
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- /table-responsive -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('request.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Excluir
        $(document).on("click", ".bt_excluir", function() {
            var id = $(this).data('id');
            $('.confirmar_exclusao').attr('data_id', id);
            $(".delete_modal").modal('show');
        });

        $(document).on("click", ".confirmar_exclusao", function() {
            var id = $(this).attr('data_id');
            $.ajax({
            url: '/api/permissions/delete/' + id, // CORRIGIDO
            type: 'POST',
            success: function() {
                $(".modal").modal('hide');
                $(".linha_id-" + id).fadeOut();
            },
            error: function(e) {
                console.log(e);
            }
        });
        });

        // Editar
        $(document).on("click", "#editar_bt", function() {
            $("#modal_editar").modal('show');
            $(".modal-title").text('Editar Permissão');
            var nome = $(this).data('nome');
            var guard = $(this).data('guard');
            var id = $(this).data('id');
            $("#nome_permission").val(nome);
            $("#guard_permission").val(guard);
            $("#permission_id").val(id);
        });

        $(document).on("click", "#salvar_permission", function() {
            var nome = $('#nome_permission').val();
            var guard = $('#guard_permission').val();
            var id = $('#permission_id').val();

            $.ajax({
                url: '/api/permissions/update/' + id, // CORRIGIDO
                method: 'POST', // Melhor usar PUT em vez de POST aqui
                data: { name: nome, guard_name: guard },
                success: function() {
                    $('.linha_id-' + id).find('td:eq(1)').text(nome);
                    $('.linha_id-' + id).find('td:eq(2)').text(guard);
                    alert('Permissão atualizada com sucesso!');
                    $('.modal').modal('hide');
                },
                error: function(xhr) {
                    alert('Erro ao atualizar permissão!');
                }
            });

        });


        // Criar
        $(document).on("click", "#criar_permission", function() {
            var nome = $('#create_nome_permission').val();
            var guard = $('#create_guard_permission').val();

            $.ajax({
                url: '/api/permissions/store',
                method: 'POST',
                data: { name: nome, guard_name: guard },
                success: function(response) {
                    // adiciona a nova linha sem precisar recarregar
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
                                        id="editar_bt">
                                        <i class="fe fe-pencil"></i> Editar
                                    </a>
                                    <a data-bs-toggle="modal"
                                        href="#delete_modal"
                                        data-id="${response.id}"
                                        class="btn btn-sm bg-danger-light bt_excluir">
                                        <i class="fe fe-trash"></i> Excluir
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `);

                    $('#modal_create').modal('hide');
                    $('#create_nome_permission').val('');
                    alert('Permissão criada com sucesso!');
                },
                error: function(xhr) {
                    alert('Erro ao criar permissão!');
                }
            });
        });

    </script>

</x-admin.layout>
