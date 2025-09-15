<x-admin.layout title="Bate-Papo">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Header -->
            <x-header.titulo pageTitle="Bate-Papo com Bot"/>

            <div class="card shadow">
                <div class="card-body">
                    <!-- Caixa do chat -->
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

            const conversation_id = $('#conversation_id').val();
            const messagesDiv = $('#messages');
            const chatBox = $('#chat-box');

            console.log('ID da conversa:', conversation_id);

            // Conexão
            socket.on('connect', () => {
                console.log('Conectado ao servidor Socket.IO.');
            });

            // Receber mensagens em tempo real
            socket.on('chatmessage' + conversation_id, (data) => {
                console.log('Mensagem recebida:', data);

                const align = data.from === 'user' ? 'text-end' : 'text-start';
                const badge = data.from === 'user' ? 'bg-primary' : 'bg-secondary';

                messagesDiv.append(`
                    <div class="${align} mb-2">
                        <span class="badge ${badge}">${data.mensagem}</span>
                    </div>
                `);
                chatBox.scrollTop(chatBox[0].scrollHeight);
            });

            // Enviar mensagem
            $('#chat-form').submit((e) => {
                e.preventDefault();

                const mensagem = $('#mensagem').val();
                if (!mensagem) return;

                // Mostra imediatamente no chat
                messagesDiv.append(`
                    <div class="text-end mb-2">
                        <span class="badge bg-primary">${mensagem}</span>
                    </div>
                `);
                chatBox.scrollTop(chatBox[0].scrollHeight);
                $('#mensagem').val('');

                // Emite para o servidor via Socket.IO
                socket.emit('chatmessage', {
                    conversation_id: conversation_id,
                    user_id: '{{ auth()->id() ?? "guest" }}',
                    from: 'user',
                    mensagem: mensagem
                });

                // Opcional: salvar no backend via AJAX
                fetch("{{ route('chat.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        mensagem: mensagem,
                        conversation_id: conversation_id
                    })
                }).then(res => res.json())
                  .then(data => console.log('Mensagem salva no backend:', data))
                  .catch(err => console.error('Erro ao salvar mensagem:', err));
            });

            socket.on('disconnect', () => {
                console.log('Desconectado do servidor Socket.IO.');
            });
        });
    </script>
</x-admin.layout>
