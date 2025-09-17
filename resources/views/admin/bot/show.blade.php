<x-admin.layout title="Detalhes do Bot">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Bot: {{ $bot->nome }}"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Bate-papo de Teste</h5>
                </div>
                <div class="card-body">

                    <!-- Área do chat -->
                    <div id="chat-box" class="border rounded p-3 mb-3" 
                         style="height: 400px; overflow-y: auto; background: #f9f9f9">
                        <div class="text-muted text-center">Converse com seu bot aqui...</div>
                    </div>

                    <!-- Input de mensagem -->
                    <form id="chat-form">
                        @csrf
                        <div class="input-group">
                            <input type="text" id="user-message" class="form-control" placeholder="Digite sua mensagem..." required>
                            <button class="btn btn-primary" type="submit">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.bot.index') }}" class="btn btn-secondary">Voltar</a>
            </div>

        </div>
    </div>

    <!-- Script do chat -->
   <script>
    const form = document.getElementById('chat-form');
    const input = document.getElementById('user-message');
    const chatBox = document.getElementById('chat-box');

   let conversationId = null; // variável global

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const message = input.value.trim();
    if (!message) return;

    appendMessage('Você', message, ['text-end', 'text-primary']);
    input.value = '';

    try {
        const response = await fetch(`/api/bots/{{ $bot->id }}/chat`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                message: message,
                empresa_id: "{{ auth()->user()->empresa->id ?? '' }}",
                user_id: "{{ auth()->user()->id ?? '' }}",
                conversation_id: conversationId // envia se existir
            })
        });

        const data = await response.json();

        appendMessage("{{ $bot->nome }}", data.reply, ['text-start', 'text-success']);

        // salva o conversation_id retornado na primeira mensagem
        if (!conversationId && data.conversation_id) {
            conversationId = data.conversation_id;
        }
    } catch (error) {
        appendMessage("Erro", "Não foi possível se conectar ao servidor.", ['text-start', 'text-danger']);
    }
});

    function appendMessage(author, text, extraClasses = []) {
        const div = document.createElement('div');
        div.classList.add('mb-2');
        if (Array.isArray(extraClasses)) {
            div.classList.add(...extraClasses);
        }
        div.innerHTML = `<strong>${author}:</strong> ${text}`;
        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;
    }
</script>


</x-admin.layout>
