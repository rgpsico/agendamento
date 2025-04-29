<x-admin.layout title="Novo Depoimento">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <h3 class="page-title">Novo Depoimento</h3>
            </div>

            <div class="card">
                <div class="card-body">
                    <x-alert/>

                    <form action="{{ route('admin.site.depoimentos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="nota">Nota</label>
                            <select name="nota" class="form-control">
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}">{{ $i }} estrela{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="comentario">Comentário</label>
                            <textarea name="comentario" rows="4" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="foto">Foto (opcional)</label>
                            <input type="file" name="foto" class="form-control">
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
