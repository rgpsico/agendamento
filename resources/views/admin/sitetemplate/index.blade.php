<x-admin.layout title="Listar Templates">

 <div class="modal fade" id="delete_modal_template" tabindex="-1" aria-labelledby="deleteModalPermissionLabel" aria-hidden="true">
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
                    <button type="button" class="btn btn-danger confirmar_exclusao_template" data_id="">Excluir</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-10">
                        <h3 class="page-title">{{ $pageTitle }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Admin</a></li>
                            <li class="breadcrumb-item active">Templates</li>
                        </ul>
                    </div>
                    <div class="col-2 text-end">
                        <a href="{{ $route }}" class="btn btn-primary">Novo Template</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Título</th>
                                            <th>Slug</th>
                                            <th>Preview</th>
                                            <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($model as $template)
                                            <tr class="linha_id-{{ $template->id }}">
                                                <td>{{ $template->id }}</td>
                                                <td>{{ $template->titulo }}</td>
                                                <td>{{ $template->slug }}</td>
                                                <td>
                                                    @if($template->preview_image)
                                                        <img src="{{ asset('storage/'.$template->preview_image) }}" width="80" alt="Preview">
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="actions">
                                                        <a href="{{ route('site-templates.edit', $template->id) }}" class="btn btn-sm bg-info-light">
                                                            <i class="fe fe-pencil"></i> Editar
                                                        </a>

                                                        <a data-bs-toggle="modal" href="#delete_modal" data-id="{{ $template->id }}" class="btn btn-sm bg-danger-light bt_excluir">
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

    <script>
        $(document).ready(function(){
            // Exclusão via modal
            $(document).on("click", ".bt_excluir", function() {
                var id = $(this).data('id');
                $('.confirmar_exclusao_template').attr('data_id', id);
                $("#delete_modal_template").modal('show');
            });

           $(document).on("click", ".confirmar_exclusao_template", function() {
            var id = $(this).attr('data_id');

            $.ajax({
                url: '/site-templates/' + id,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(result) {
                    if (result.success) {
                        $("#delete_modal_template").modal('hide');
                        $(".linha_id-" + id).fadeOut();
                    } else {
                        alert(result.message);
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert("Ocorreu um erro ao excluir.");
                }
            });
        });

        });
    </script>

</x-admin.layout>
