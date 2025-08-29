<x-admin.layout title="Criar Perfil">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <h3>Criar Perfil</h3>

            <form action="{{ route('admin.perfis.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Tipo (opcional)</label>
                    <input type="text" name="tipo" class="form-control">
                </div>

                <button type="submit" class="btn btn-success mt-3">Criar Perfil</button>
            </form>
        </div>
    </div>
</x-admin.layout>
