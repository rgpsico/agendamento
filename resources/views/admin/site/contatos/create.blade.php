<x-admin.layout title="Novo Contato">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <h3 class="page-title">Novo Contato</h3>
            </div>

            <div class="card">
                <div class="card-body">
                    <x-alert/>

                    <form action="{{ route('admin.site.contatos.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <select name="tipo" class="form-control" required>
                                <option value="telefone">Telefone</option>
                                <option value="email">E-mail</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="instagram">Instagram</option>
                                <option value="endereco">Endereço</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <input type="text" name="valor" class="form-control" required>
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
