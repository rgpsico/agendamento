<x-admin.layout title="Configurações">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 10-%">
            <h3 class="mb-4">Configurações do Sistema</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('configuracoes.salvar') }}" method="POST">
                @csrf

                <!-- Escolha do Tipo de Agendamento -->
                <div class="form-group">
                    <label>Tipo de Agendamento:</label>
                    <select name="agendamento_tipo" class="form-control" id="tipoAgendamento">
                        <option value="horarios" {{ $tipoAgendamento === 'horarios' ? 'selected' : '' }}>Com Horário</option>
                        <option value="whatsapp" {{ $tipoAgendamento === 'whatsapp' ? 'selected' : '' }}>Via WhatsApp</option>
                    </select>
                </div>

                <!-- Número do WhatsApp (aparece apenas se selecionado) -->
                <div class="form-group" id="whatsappField" style="{{ $tipoAgendamento === 'whatsapp' ? '' : 'display:none;' }}">
                    <label>Número do WhatsApp:</label>
                    <input type="text" name="whatsapp_numero" class="form-control" value="{{ $whatsappNumero }}" placeholder="Ex: 21999998888">
                </div>

                <button type="submit" class="btn btn-primary mt-3">Salvar Configurações</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tipoAgendamento = document.getElementById("tipoAgendamento");
            const whatsappField = document.getElementById("whatsappField");

            tipoAgendamento.addEventListener("change", function () {
                if (this.value === "whatsapp") {
                    whatsappField.style.display = "block";
                } else {
                    whatsappField.style.display = "none";
                }
            });
        });
    </script>
</x-admin.layout>
