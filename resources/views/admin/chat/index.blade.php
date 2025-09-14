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
                            <!-- As mensagens serão carregadas aqui -->
                        </div>
                    </div>

                    <!-- Formulário -->
                    <form id="chat-form">
                        @csrf
                        <input type="hidden" id="conversation_id" name="conversation_id">
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

    <!-- Script para AJAX -->
    <script>
        const chatForm = document.getElementById('chat-form');
        const chatBox = document.getElementById('chat-box');
        const messagesDiv = document.getElementById('messages');
        const conversationInput = document.getElementById('conversation_id');

        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            let mensagem = document.getElementById('mensagem').value;
            let conversation_id = conversationInput.value;

            // Mostra a mensagem do usuário na tela
            messagesDiv.innerHTML += `
                <div class="text-end mb-2">
                    <span class="badge bg-primary">${mensagem}</span>
                </div>
            `;
            chatBox.scrollTop = chatBox.scrollHeight;

            // Limpa o campo
            document.getElementById('mensagem').value = "";

            try {
                let response = await fetch("{{ route('chat.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        mensagem: mensagem,
                        conversation_id: conversation_id
                    })
                });

                let data = await response.json();

                // Atualiza o conversation_id se for a primeira mensagem
                if (!conversationInput.value) {
                    conversationInput.value = data.conversation_id;
                }

                // Exibe a resposta do bot
                messagesDiv.innerHTML += `
                    <div class="text-start mb-2">
                        <span class="badge bg-secondary">${data.bot_response}</span>
                    </div>
                `;
                chatBox.scrollTop = chatBox.scrollHeight;

            } catch (error) {
                console.error("Erro no chat:", error);
            }
        });
    </script>
</x-admin.layout>
