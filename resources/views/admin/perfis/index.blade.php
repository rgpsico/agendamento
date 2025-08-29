<x-admin.layout title="Listar Perfis">
    <x-modal-delete />
    <div class="page-wrapper">
        <div class="content container-fluid">
           <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="page-title">Listar Perfis</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Admin</a></li>
                        <li class="breadcrumb-item active">Listar Perfis</li>
                    </ul>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('admin.perfis.create') }}" class="btn btn-success">
                        <i class="fe fe-plus"></i> Criar Perfil
                    </a>
                </div>
            </div>
         </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Tipo</th>
                                            
                                            <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($perfis as $perfil)
                                            <tr class="linha_{{ $perfil->id }}">
                                                <td>{{ $perfil->id }}</td>
                                                <td>{{ $perfil->nome }}</td>
                                                <td>{{ $perfil->tipo ?? '-' }}</td>
                                                <td class="text-center">
                                                    <div class="actions">
                                                        <a class="btn btn-sm bg-info-light" href="{{ route('admin.perfis.edit', $perfil->id) }}">
                                                            <i class="fe fe-pencil"></i> Editar
                                                        </a>
                                                        <a data-bs-toggle="modal" href="#delete_modal" data-id="{{ $perfil->id }}" class="btn btn-sm bg-danger-light bt_excluir">
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

<!-- jQuery (primeiro) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $(document).on("click", ".bt_excluir", function() {
        var id = $(this).data('id');
        $('.confirmar_exclusao').data('id', id);
        $(".delete_modal").modal('show');
    });

    $(document).on("click", ".confirmar_exclusao", function() {
        var id = $(this).data('id');
        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '{{ route("admin.perfis.destroy", ":id") }}'.replace(':id', id),
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
            },
            success: function(result) {
                alert('Perfil excluído com sucesso');
                $('.modal').modal('hide');
                $(".linha_" + id).fadeOut();
            },
            error: function(request, msg, error) {
                console.log(error);
            }
        });
    });
});
</script>

</x-admin.layout>
