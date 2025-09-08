<x-admin.layout title="Virtual Hosts">
    <x-modal-delete />
    <x-modal-editar-vhost />
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="page-title">Gerenciar Virtual Hosts</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Admin</a></li>
                            <li class="breadcrumb-item active">Virtual Hosts</li>
                        </ul>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('virtualhosts.create') }}" class="btn btn-success">
                            <i class="fe fe-plus"></i> Criar Virtual Host
                        </a>
                    </div>
                </div>
            </div>

            <x-alert-messages />

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Arquivo</th>
                                            <th>ServerName</th>
                                            <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vhosts as $vhost)
                                            <tr class="linha_{{ $loop->index }}">
                                                <td>{{ $vhost['file'] }}</td>
                                                <td>{{ $vhost['servername'] }}</td>
                                                <td class="text-center">
                                                    <div class="actions">
                                                        <a class="btn btn-sm bg-info-light bt_editar"
                                                           data-file="{{ $vhost['file'] }}">
                                                            <i class="fe fe-pencil"></i> Editar
                                                        </a>
                                                        <a class="btn btn-sm bg-danger-light bt_excluir"
                                                           data-file="{{ $vhost['file'] }}">
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
  

    // Excluir
    $(document).on("click", ".bt_excluir", function() {
        var file = $(this).data('file');
        if(confirm('Deseja excluir o vhost ' + file + '?')) {
            $.ajax({
                url: '{{ route("virtualhosts.destroy", ":file") }}'.replace(':file', file),
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    alert('Vhost excluído com sucesso');
                    location.reload();
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
    });

    // Editar
    $(document).on("click", ".bt_editar", function() {
        var file = $(this).data('file');
        window.location.href = '{{ route("virtualhosts.edit", ":file") }}'.replace(':file', file);
    });
});
</script>
</x-admin.layout>
