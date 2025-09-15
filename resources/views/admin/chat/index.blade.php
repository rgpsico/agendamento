<x-admin.layout title="Bate-Papo">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 2%">
            <!-- Header com animação -->
            <x-header.titulo pageTitle="Bate-Papo com Bot"/>

            <!-- Chat Container -->
            <div class="chat-container">
                <div class="card chat-card shadow-lg">
                    <!-- Chat Header -->
                    <div class="chat-header">
                        <div class="bot-avatar">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="bot-info">
                            <h5 class="mb-0">Assistente Virtual</h5>
                            <small class="text-muted">
                                <span class="status-indicator"></span>
                                Online
                            </small>
                        </div>
                        <div class="chat-actions">
                            <button class="btn btn-sm btn-outline-secondary" id="clear-chat" title="Limpar conversa">
                                <i class="fas fa-broom"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div id="chat-box" class="chat-messages">
                        <div id="messages">
                            @if($conversation && $conversation->messages)
                                @foreach($conversation->messages as $message)
                                    @if($message->from === 'user')
                                        <div class="message-wrapper user-message" data-message-id="{{ $loop->index }}">
                                            <div class="message user-msg">
                                                <div class="message-content">{{ $message->body }}</div>
                                                <div class="message-time">{{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}</div>
                                            </div>
                                            <div class="message-avatar">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="message-wrapper bot-message" data-message-id="{{ $loop->index }}">
                                            <div class="message-avatar">
                                                <i class="fas fa-robot"></i>
                                            </div>
                                            <div class="message bot-msg">
                                                <div class="message-content">{{ $message->body }}</div>
                                                <div class="message-time">{{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}</div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <!-- Typing Indicator -->
                        <div id="typing-indicator" class="message-wrapper bot-message" style="display: none;">
                            <div class="message-avatar">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div class="message bot-msg typing">
                                <div class="typing-dots">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Input Area -->
                    <div class="chat-input-container">
                        <form id="chat-form" class="chat-form">
                            @csrf
                            <input type="hidden" id="conversation_id" name="conversation_id" value="{{ $conversation->id ?? '' }}">
                            
                            <div class="input-wrapper">
                                <button type="button" class="btn btn-link attachment-btn" title="Anexar arquivo">
                                    <i class="fas fa-paperclip"></i>
                                </button>
                                
                                <input type="text" 
                                       id="mensagem" 
                                       name="mensagem" 
                                       class="form-control chat-input" 
                                       placeholder="Digite sua mensagem..." 
                                       autocomplete="off"
                                       required>
                                
                                <button class="btn btn-primary send-btn" type="submit">
                                    <i class="fas fa-paper-plane send-icon"></i>
                                    <span class="btn-text">Enviar</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Styles -->
    <style>
        .chat-container {
            max-width: 900px;
            margin: 0 auto;
            height: calc(100vh - 200px);
        }

        .chat-card {
            height: 100%;
            border-radius: 20px;
            border: none;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .chat-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .bot-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .bot-info {
            flex: 1;
        }

        .bot-info h5 {
            color: #333;
            font-weight: 600;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            max-height: calc(100vh - 350px);
        }

        .message-wrapper {
            display: flex;
            margin-bottom: 20px;
            align-items: flex-end;
            gap: 12px;
        }

        .user-message {
            justify-content: flex-end;
        }

        .bot-message {
            justify-content: flex-start;
        }

        .message-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .user-message .message-avatar {
            background: #007bff;
            color: white;
        }

        .bot-message .message-avatar {
            background: #6c757d;
            color: white;
        }

        .message {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            position: relative;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .user-msg {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .bot-msg {
            background: white;
            color: #333;
            border: 1px solid #e9ecef;
        }

        .message-content {
            line-height: 1.4;
            word-wrap: break-word;
        }

        .message-time {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 4px;
        }

        .typing {
            padding: 20px 16px;
        }

        .typing-dots {
            display: flex;
            gap: 4px;
        }

        .typing-dots span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #6c757d;
            animation: typing 1.4s infinite ease-in-out;
        }

        .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
        .typing-dots span:nth-child(2) { animation-delay: -0.16s; }

        @keyframes typing {
            0%, 80%, 100% { 
                transform: scale(0.8);
                opacity: 0.5;
            }
            40% { 
                transform: scale(1);
                opacity: 1;
            }
        }

        .chat-input-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-top: 1px solid rgba(0,0,0,0.1);
        }

        .input-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            border-radius: 25px;
            padding: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .attachment-btn {
            color: #6c757d;
            border: none;
            padding: 8px 12px;
        }

        .attachment-btn:hover {
            color: #007bff;
            background: none;
        }

        .chat-input {
            flex: 1;
            border: none;
            padding: 12px 16px;
            font-size: 14px;
            border-radius: 20px;
            background: transparent;
        }

        .chat-input:focus {
            outline: none;
            box-shadow: none;
        }

        .send-btn {
            padding: 10px 20px;
            border-radius: 20px;
            border: none;
            background: linear-gradient(135deg, #28a745, #20c997);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .send-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .send-btn:disabled {
            opacity: 0.6;
            transform: none;
        }

        /* Scrollbar personalizada */
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.1);
            border-radius: 3px;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.3);
            border-radius: 3px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,0.5);
        }

        /* Responsivo */
        @media (max-width: 768px) {
            .chat-container {
                height: calc(100vh - 150px);
                margin: 0 10px;
            }
            
            .message {
                max-width: 85%;
            }
            
            .btn-text {
                display: none;
            }
        }
    </style>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.7.5/socket.io.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <script>
        $(document).ready(function() {
            const socket = io('https://www.comunidadeppg.com.br:3000');
            const conversation_id = $('#conversation_id').val();
            const messagesDiv = $('#messages');
            const chatBox = $('#chat-box');
            const typingIndicator = $('#typing-indicator');

            // Animações GSAP iniciais
            gsap.from('.chat-card', {
                duration: 0.8,
                y: 50,
                opacity: 0,
                ease: 'power2.out'
            });

            gsap.from('.chat-header', {
                duration: 0.6,
                y: -30,
                opacity: 0,
                delay: 0.2,
                ease: 'power2.out'
            });

            gsap.from('.message-wrapper', {
                duration: 0.5,
                x: -30,
                opacity: 0,
                stagger: 0.1,
                delay: 0.4,
                ease: 'power2.out'
            });

            // Animar entrada de novas mensagens
            function animateNewMessage(element, fromUser = false) {
                gsap.from(element, {
                    duration: 0.5,
                    x: fromUser ? 30 : -30,
                    opacity: 0,
                    scale: 0.8,
                    ease: 'back.out(1.7)'
                });
            }

            // Conexão Socket.IO
            socket.on('connect', () => {
                console.log('Conectado ao servidor Socket.IO.');
                
                // Animar indicador de status
                gsap.to('.status-indicator', {
                    duration: 0.3,
                    scale: 1.2,
                    ease: 'power2.out'
                });
            });

            // Receber mensagens
            socket.on('chatmessage' + conversation_id, (data) => {
                console.log('Mensagem recebida:', data);

                // Esconder indicador de digitação
                typingIndicator.hide();

                const messageWrapper = $(`
                    <div class="message-wrapper bot-message">
                        <div class="message-avatar">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="message bot-msg">
                            <div class="message-content">${data.mensagem}</div>
                            <div class="message-time">${new Date().toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})}</div>
                        </div>
                    </div>
                `);

                messagesDiv.append(messageWrapper);
                animateNewMessage(messageWrapper, false);
                
                // Scroll suave para baixo
                gsap.to(chatBox, {
                    duration: 0.5,
                    scrollTop: chatBox[0].scrollHeight,
                    ease: 'power2.out'
                });
            });

            // Enviar mensagem
            $('#chat-form').submit((e) => {
                e.preventDefault();

                const mensagem = $('#mensagem').val().trim();
                if (!mensagem) return;

                // Animar botão de envio
                const sendBtn = $('.send-btn');
                gsap.to('.send-icon', {
                    duration: 0.2,
                    rotation: 360,
                    ease: 'power2.inOut'
                });

                // Adicionar mensagem do usuário
                const userMessage = $(`
                    <div class="message-wrapper user-message">
                        <div class="message user-msg">
                            <div class="message-content">${mensagem}</div>
                            <div class="message-time">${new Date().toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})}</div>
                        </div>
                        <div class="message-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                `);

                messagesDiv.append(userMessage);
                animateNewMessage(userMessage, true);

                // Mostrar indicador de digitação
                typingIndicator.show();
                animateNewMessage(typingIndicator, false);

                // Limpar input com animação
                gsap.to('#mensagem', {
                    duration: 0.3,
                    scale: 0.95,
                    ease: 'power2.out',
                    onComplete: () => {
                        $('#mensagem').val('');
                        gsap.to('#mensagem', {
                            duration: 0.3,
                            scale: 1,
                            ease: 'power2.out'
                        });
                    }
                });

                // Scroll suave
                gsap.to(chatBox, {
                    duration: 0.5,
                    scrollTop: chatBox[0].scrollHeight,
                    ease: 'power2.out'
                });

                // Emitir via Socket.IO
                socket.emit('chatmessage', {
                    conversation_id: conversation_id,
                    user_id: '{{ auth()->id() ?? "guest" }}',
                    from: 'user',
                    mensagem: mensagem
                });

                // Salvar no backend
                fetch("{{ route('chat.enviarparabatepaposite') }}", {
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
                  .then(data => console.log('Mensagem salva:', data))
                  .catch(err => console.error('Erro:', err));
            });

            // Limpar chat
            $('#clear-chat').click(() => {
                gsap.to('.message-wrapper', {
                    duration: 0.3,
                    x: -100,
                    opacity: 0,
                    stagger: 0.05,
                    ease: 'power2.in',
                    onComplete: () => {
                        messagesDiv.empty();
                    }
                });
            });

            // Animações de hover
            $('.message-wrapper').on('mouseenter', function() {
                gsap.to(this, {
                    duration: 0.2,
                    scale: 1.02,
                    ease: 'power2.out'
                });
            }).on('mouseleave', function() {
                gsap.to(this, {
                    duration: 0.2,
                    scale: 1,
                    ease: 'power2.out'
                });
            });

            // Foco no input
            $('#mensagem').focus(() => {
                gsap.to('.input-wrapper', {
                    duration: 0.3,
                    boxShadow: '0 4px 20px rgba(0,123,255,0.3)',
                    ease: 'power2.out'
                });
            }).blur(() => {
                gsap.to('.input-wrapper', {
                    duration: 0.3,
                    boxShadow: '0 2px 10px rgba(0,0,0,0.1)',
                    ease: 'power2.out'
                });
            });

            socket.on('disconnect', () => {
                console.log('Desconectado do servidor Socket.IO.');
            });
        });
    </script>
</x-admin.layout>