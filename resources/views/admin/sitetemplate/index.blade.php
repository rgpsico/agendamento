<x-admin.layout title="Listar Templates">

    @include('components.modal-delete') {{-- Modal de exclusão padrão --}}
    
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
                $('.confirmar_exclusao').attr('data_id', id);
                $(".delete_modal").modal('show');
            });

            $(document).on("click", ".confirmar_exclusao", function() {
                var id = $(this).attr('data_id');

                $.ajax({
                    url:'/site-templates/' + id,
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(result) {
                        $(".modal").modal('hide');
                        $(".linha_id-" + id).fadeOut();
                    },
                    error: function(request,msg,error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>

</x-admin.layout>
