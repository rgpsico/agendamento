<x-admin.layout title="Criar Permissão">

    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-10">
                        <h3 class="page-title">Criar Permissão</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('configuracoes.permissoes') }}">Permissões</a></li>
                            <li class="breadcrumb-item active">Criar</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-lg-8 col-md-10">
                    <div class="card">
                        <div class="card-body">

                            <form id="form-create-permission">
                                @csrf
                                <div class="form-group">
                                    <label for="nome_permission">Nome da Permissão</label>
                                    <input type="text" id="nome_permission" name="name" class="form-control" required>
                                </div>

                                <div class="form-group mt-2">
                                    <label for="guard_permission">Guard</label>
                                    <input type="text" id="guard_permission" name="guard_name" value="web" class="form-control">
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                    <a href="{{ route('configuracoes.permissoes') }}" class="btn btn-secondary">Cancelar</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Script Ajax para salvar --}}
    <script>
        $(document).ready(function () {
            $('#form-create-permission').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '/api/permissions/store',
                    method: 'POST',
                    data: {
                        name: $('#nome_permission').val(),
                        guard_name: $('#guard_permission').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        alert('Permissão criada com sucesso!');
                        window.location.href = "{{ route('configuracoes.permissoes') }}";
                    },
                    error: function (xhr) {
                        alert('Erro ao criar a permissão.');
                    }
                });
            });
        });
    </script>

</x-admin.layout>
