<x-admin.layout title="Bate-Papo">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">
            <input type="text" id="conversation_id" value="{{ $conversation->id }}" hidden>
            <!-- Header -->
            <x-header.titulo pageTitle="Bate-Papo com Bot"/>

            <div class="card shadow">
                <div class="card-body">
                    <!-- Caixa do chat -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Controle da conversa</h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="humanToggle"
                                {{ $conversation->human_controlled ? 'checked' : '' }}>
                            <label class="form-check-label" for="humanToggle">
                                Controle humano
                            </label>
                        </div>
                    </div>

                    <div id="chat-box" class="border rounded p-3 mb-3" 
                         style="height: 400px; overflow-y: auto; background-color: #f9f9f9;">
                        <div id="messages">
                            @if($conversation && $conversation->messages)
                                @foreach($conversation->messages as $message)
                                    @if($message->from === 'user')
                                        <div class="text-end mb-2">
                                            <span class="badge bg-primary">{{ $message->body }}</span>
                                        </div>
                                    @else
                                        <div class="text-start mb-2">
                                            <span class="badge bg-secondary">{{ $message->body }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Formulário -->
                    <form id="chat-form">
                        @csrf
                        <input type="hidden" id="conversation_id" name="conversation_id" value="{{ $conversation->id ?? '' }}">
                        <div class="input-group">
                            <input type="text" id="mensagem" name="mensagem" class="form-control" 
                                   placeholder="Digite sua mensagem..." required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i> Enviar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Socket.IO -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.7.5/socket.io.min.js"></script>

  
       <script>
$(document).ready(function() {
    const socket = io('https://www.comunidadeppg.com.br:3000');

    const conversationInput = $('#conversation_id');
    const conversation_id = conversationInput.val();
    const messagesDiv = $('#messages');
    const chatBox = $('#chat-box');
    const mensagemInput = $('#mensagem');
    const humanToggle = $('#humanToggle');

    let humanTimeout;
    let tempo_de_espera_ate_o_bot_assumir = 50000; // 10 segundos

    // ---------------------- Funções ----------------------
    function appendMessage(text, align = 'text-center', badgeClass = 'bg-secondary') {
        messagesDiv.append(`
            <div class="${align} mb-2">
                <span class="badge ${badgeClass}">${text}</span>
            </div>
        `);
        chatBox.scrollTop(chatBox[0].scrollHeight);
    }

    function updateHumanControl(isHuman, feedbackMsg = null) {
        humanToggle.prop('checked', isHuman);

        fetch(`/api/conversations/${conversation_id}/human-control`, {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ human_controlled: isHuman })
        })
        .then(res => res.json())
        .then(data => {
            console.log('Status atualizado:', data);
            if(feedbackMsg) appendMessage(feedbackMsg, 'text-center', 'text-muted');
        })
        .catch(err => console.error('Erro ao atualizar controle humano:', err));
    }

    function ativarControleHumano() {
        if (!humanToggle.is(':checked')) {
            updateHumanControl(true, "🔒 Controle humano ativado automaticamente ao digitar.");
        }
    }

    function reativarControleBot() {
        updateHumanControl(false, "🤖 Controle do bot retomado automaticamente após inatividade.");
    }

    function resetHumanTimeout() {
        clearTimeout(humanTimeout);
        humanTimeout = setTimeout(reativarControleBot, tempo_de_espera_ate_o_bot_assumir);
    }

    // ---------------------- Socket.IO ----------------------
    socket.on('connect', () => console.log('Conectado ao servidor Socket.IO.'));
    socket.on('disconnect', () => console.log('Desconectado do servidor Socket.IO.'));

    socket.on('chatmessage' + conversation_id, (data) => {
        const align = data.from === 'user' ? 'text-end' : 'text-start';
        const badge = data.from === 'user' ? 'bg-primary' : 'bg-secondary';
        appendMessage(data.mensagem, align, badge);
    });

    // ---------------------- Eventos ----------------------
    // Enviar mensagem
    $('#chat-form').submit((e) => {
        e.preventDefault();
        const mensagem = mensagemInput.val();
        if (!mensagem) return;

        appendMessage(mensagem, 'text-end', 'bg-primary');
        chatBox.scrollTop(chatBox[0].scrollHeight);
        mensagemInput.val('');

        socket.emit('chatmessage', {
            conversation_id: conversation_id,
            user_id: '{{ auth()->id() ?? "guest" }}',
            from: 'user',
            mensagem: mensagem
        });

        fetch("{{ route('chat.enviarparabatepaposite') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ mensagem, conversation_id })
        })
        .then(res => res.json())
        .then(data => console.log('Mensagem salva no backend:', data))
        .catch(err => console.error('Erro ao salvar mensagem:', err));
    });

    // Toggle manual
    humanToggle.change(function () {
        const isHuman = $(this).is(':checked');
        const feedbackMsg = isHuman
            ? "🔒 Controle humano ativado. O bot não responderá."
            : "🤖 Controle humano desativado. O bot voltou a responder.";
        updateHumanControl(isHuman, feedbackMsg);
    });

    // Input para ativar controle humano + timeout do bot
    mensagemInput.on('input', function () {
        ativarControleHumano();
        resetHumanTimeout();
    });

});
</script>


 
</x-admin.layout>
