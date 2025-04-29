<x-admin.layout title="Editar Contato">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <h3 class="page-title">Editar Contato</h3>
            </div>

            <div class="card">
                <div class="card-body">
                    <x-alert/>

                    <form action="{{ route('admin.site.contatos.update', $contato->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <select name="tipo" class="form-control" required>
                                @foreach(['telefone', 'email', 'whatsapp', 'instagram', 'endereco'] as $tipo)
                                    <option value="{{ $tipo }}" {{ $contato->tipo == $tipo ? 'selected' : '' }}>
                                        {{ ucfirst($tipo) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <input type="text" name="valor" class="form-control" value="{{ $contato->valor }}" required>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-success">Atualizar</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
