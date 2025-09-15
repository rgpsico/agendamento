<style>
    .chatbot-float-btn {
        position: fixed;
        bottom: 100px; /* antes era 20px */
        right: 20px;
        /* resto igual */
    }

    .chatbot-container {
        position: fixed;
        bottom: 100px; /* antes era 20px */
        right: 90px;
        /* resto igual */
    }
</style>

<!-- Chatbot Float Button -->
<div class="chatbot-float-btn" id="chatbot-float-btn" style="margin-right:7em;">
    <div class="chat-icon">
        <i class="fas fa-comments"></i>
    </div>
    <div class="notification-dot" id="notification-dot"></div>
</div>

<!-- Chatbot Container -->
<div class="chatbot-container" id="chatbot-container">
    <div class="chatbot-header">
        <div class="header-left">
            <div class="avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="header-info">
                <h5 class="bot-name">Assistente Virtual</h5>
                <span class="status-indicator">
                    <span class="status-dot"></span>
                    Online
                </span>
            </div>
        </div>
        <div class="header-actions">
            <button class="action-btn minimize-btn" id="minimize-btn">
                <i class="fas fa-minus"></i>
            </button>
            <button class="action-btn close-btn" id="close-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <div class="chatbot-messages" id="chatbot-messages">
        <div class="welcome-message">
            <div class="bot-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="message-content">
                <div class="message bot-message">
                    <div class="typing-indicator" id="initial-typing">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="chatbot-input-area">
        @if (!Auth::check())
            <div class="phone-input-container" id="phone-input-container">
                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <input type="text" id="chatbot-phone" placeholder="Digite seu número de telefone..." 
                           class="form-input phone-input" />
                </div>
            </div>
        @endif
        
        <div class="message-input-container">
            <div class="input-group">
                <input type="text" id="chatbot-input" placeholder="Digite sua mensagem..." 
                       class="form-input message-input" disabled />
                <button id="chatbot-send" class="send-btn" disabled>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            <div class="typing-indicator-container">
                <div class="typing-indicator bot-typing" id="bot-typing" style="display: none;">
                    <span>Assistente está digitando</span>
                    <div class="dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overlay for mobile -->
<div class="chatbot-overlay" id="chatbot-overlay"></div>

<!-- Campo hidden para conversation_id -->
<input type="hidden" id="conversation_id" value="">
<input type="hidden" id="professor_id" value="{{ $professor_id ?? '' }}">

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="{{ asset('admin/js/jquery-3.6.3.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.7.5/socket.io.min.js"></script>

<script>
$(document).ready(function() {
    alert('aaaa')
    let conversationId = null;
    let isOpen = false;
    let isMinimized = false;
    let hasStarted = false;
    let socketInitialized = false;
    let socket = null;

    // Initialize GSAP animations
    gsap.set('.chatbot-container', { 
        scale: 0, 
        opacity: 0,
        transformOrigin: 'bottom right',
        visibility: 'hidden'
    });

    // Float button pulse animation
    function startPulseAnimation() {
        gsap.to('.chatbot-float-btn', {
            scale: 1.1,
            duration: 1,
            ease: "power2.inOut",
            yoyo: true,
            repeat: -1
        });
    }

    // Initial welcome message with typing effect
    function showWelcomeMessage() {
        if (hasStarted) return;
        hasStarted = true;

        // Show typing indicator first
        gsap.to('#initial-typing', { opacity: 1, duration: 0.3 });

        setTimeout(() => {
            // Hide typing indicator and show message
            gsap.to('#initial-typing', { 
                opacity: 0, 
                duration: 0.3,
                onComplete: function() {
                    const welcomeText = `Olá! Como posso ajudar com seu agendamento hoje? @if (!Auth::check()) Por favor, informe seu número de telefone para continuar. @endif`;
                    
                    $('.welcome-message .message').html(welcomeText);
                    
                    // Animate message appearance
                    gsap.fromTo('.welcome-message .message', 
                        { opacity: 0, y: 20 },
                        { opacity: 1, y: 0, duration: 0.5, ease: "back.out(1.7)" }
                    );

                    @if (!Auth::check())
                        // Show phone input after welcome message
                        setTimeout(() => {
                            gsap.fromTo('#phone-input-container',
                                { height: 0, opacity: 0 },
                                { height: 'auto', opacity: 1, duration: 0.5, ease: "power2.out" }
                            );
                        }, 500);
                    @else
                        // Enable input for authenticated users
                        $('#chatbot-input, #chatbot-send').prop('disabled', false);
                        gsap.to('.message-input-container', { opacity: 1, duration: 0.3 });
                    @endif
                }
            });
        }, 2000);
    }

    // Open chatbot
    function openChatbot() {
        if (isOpen) return;
        isOpen = true;

        // Hide float button
        gsap.to('.chatbot-float-btn', {
            scale: 0,
            rotation: 180,
            duration: 0.3,
            ease: "back.in(1.7)"
        });

        // Show chatbot container
        gsap.set('.chatbot-container', { visibility: 'visible' });
        gsap.to('.chatbot-container', {
            scale: 1,
            opacity: 1,
            duration: 0.4,
            ease: "back.out(1.7)",
            onComplete: function() {
                showWelcomeMessage();
                // Show overlay on mobile
                if (window.innerWidth <= 768) {
                    gsap.to('.chatbot-overlay', { opacity: 1, duration: 0.3 });
                }
            }
        });

        // Hide notification dot
        gsap.to('#notification-dot', { scale: 0, duration: 0.2 });
    }

    // Close chatbot
    function closeChatbot() {
        if (!isOpen) return;
        isOpen = false;
        isMinimized = false;

        gsap.to('.chatbot-container', {
            scale: 0,
            opacity: 0,
            duration: 0.3,
            ease: "back.in(1.7)",
            onComplete: function() {
                gsap.set('.chatbot-container', { visibility: 'hidden' });
            }
        });

        // Show float button
        gsap.to('.chatbot-float-btn', {
            scale: 1,
            rotation: 0,
            duration: 0.4,
            ease: "back.out(1.7)"
        });

        // Hide overlay
        gsap.to('.chatbot-overlay', { opacity: 0, duration: 0.3 });
    }

    // Minimize chatbot
    function minimizeChatbot() {
        if (!isOpen || isMinimized) return;
        isMinimized = true;

        gsap.to('.chatbot-messages, .chatbot-input-area', {
            height: 0,
            opacity: 0,
            duration: 0.3,
            ease: "power2.inOut"
        });

        gsap.to('.chatbot-container', {
            height: '60px',
            duration: 0.3,
            ease: "power2.inOut"
        });
    }

    // Restore chatbot from minimized state
    function restoreChatbot() {
        if (!isMinimized) return;
        isMinimized = false;

        gsap.to('.chatbot-container', {
            height: 'auto',
            duration: 0.3,
            ease: "power2.inOut"
        });

        gsap.to('.chatbot-messages, .chatbot-input-area', {
            height: 'auto',
            opacity: 1,
            duration: 0.3,
            ease: "power2.inOut"
        });
    }

    // Event listeners
    $('#chatbot-float-btn').on('click', openChatbot);
    $('#close-btn').on('click', closeChatbot);
    $('#minimize-btn').on('click', function() {
        if (isMinimized) {
            restoreChatbot();
            $(this).find('i').removeClass('fas fa-window-restore').addClass('fas fa-minus');
        } else {
            minimizeChatbot();
            $(this).find('i').removeClass('fas fa-minus').addClass('fas fa-window-restore');
        }
    });
    
    $('#chatbot-overlay').on('click', closeChatbot);

    // Phone input validation
    @if (!Auth::check())
        $('#chatbot-phone').on('input', function() {
            const phone = $(this).val().trim();
            const isValidPhone = phone.length >= 10;
            
            if (isValidPhone) {
                $('#chatbot-input, #chatbot-send').prop('disabled', false);
                gsap.to('.message-input-container', { opacity: 1, duration: 0.3 });
                gsap.to(this, { borderColor: '#10b981', duration: 0.2 });
            } else {
                $('#chatbot-input, #chatbot-send').prop('disabled', true);
                gsap.to('.message-input-container', { opacity: 0.5, duration: 0.3 });
                gsap.to(this, { borderColor: '#ef4444', duration: 0.2 });
            }
        });
    @endif

    // Função para inicializar o Socket.IO
    function initializeSocketIO(convId) {
        socket = io('https://www.comunidadeppg.com.br:3000');

        console.log('Inicializando Socket.IO com conversation_id:', convId);

        socket.on('connect', () => {
            console.log('Conectado ao servidor Socket.IO.');
        });

        socket.on('enviarparaosass' + convId, (data) => {
            console.log('Mensagem recebida enviarparaosass:', data);
            hideBotTyping();
            appendMessage(data.from, data.mensagem);
        });

        socket.on('connect_error', (error) => {
            console.error('Erro de conexão Socket.IO:', error);
            appendMessage('bot', 'Não foi possível conectar ao servidor. Tente novamente mais tarde.');
        });

        socket.on('disconnect', () => {
            console.log('Desconectado do servidor Socket.IO.');
        });

        // Trocar o envio para sendViaSocket após inicializar o Socket.IO
        $('#chatbot-send').off('click').on('click', sendViaSocket);
        $('#chatbot-input').off('keypress').on('keypress', function(e) {
            if (e.which === 13 && !$(this).prop('disabled')) {
                sendViaSocket();
            }
        });
    }

    // Função para enviar mensagens via AJAX e Socket.IO (para mensagens subsequentes)
    function sendViaSocket() {
        const message = $('#chatbot-input').val().trim();
        if (!message) return;

        // Mostrar mensagem do usuário imediatamente
        appendMessage('user', message);
        $('#chatbot-input').val('');
        showBotTyping();

        // Enviar via AJAX para a rota chat.store
        $.ajax({
            url: '{{ route("chat.store") }}',
            method: 'POST',
            data: {
                mensagem: message,
                conversation_id: conversationId,
                professor_id: $('#professor_id').val(),
                @if (!Auth::check())
                phone: $('#chatbot-phone').val().trim(),
                @endif
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                setTimeout(() => {
                    hideBotTyping();
                    // appendMessage('bot', response.bot_response || 'Entendido! Como posso ajudar mais?');
                }, 1500);
            },
            error: function() {
                hideBotTyping();
                appendMessage('bot', 'Desculpe, ocorreu um erro. Tente novamente.');
            }
        });

        // Enviar via Socket.IO para comunicação em tempo real
        socket.emit('chatmessage', {
            conversation_id: conversationId,
            user_id: '{{ auth()->id() ?? "guest" }}',
            from: 'user',
            mensagem: message
        });
    }

    // Send message function (para a primeira mensagem via AJAX)
    function sendMessage() {
        const message = $('#chatbot-input').val().trim();
        if (!message) return;

        @if (!Auth::check())
            const phone = $('#chatbot-phone').val().trim();
            if (!phone || phone.length < 10) {
                showErrorMessage('Por favor, informe um número de telefone válido.');
                return;
            }
        @endif

        // Animate user message
        appendMessage('user', message);
        $('#chatbot-input').val('');
          
        // Show typing indicator
        showBotTyping();

        // Send to backend
        $.ajax({
            url: '{{ route("chat.store") }}',
            method: 'POST',
            data: {
                mensagem: message,
                conversation_id: conversationId,
                professor_id: $('#professor_id').val(),
                @if (!Auth::check())
                phone: phone,
                @endif
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.conversation_id) {
                    conversationId = response.conversation_id;
                    $('#conversation_id').val(conversationId);
                    console.log('Conversation ID recebido:', conversationId);

                    // Inicializar Socket.IO após obter o conversation_id
                    if (!socketInitialized) {
                        initializeSocketIO(conversationId);
                        socketInitialized = true;
                    }
                } else {
                    appendMessage('bot', 'Erro: Não foi possível obter o ID da conversa.');
                }

                setTimeout(() => {
                    hideBotTyping();
                    appendMessage('bot', response.bot_response || 'Entendido! Como posso ajudar mais?');
                    
                    @if (!Auth::check())
                        $('#chatbot-phone').prop('disabled', true);
                        gsap.to('#phone-input-container', { 
                            opacity: 0.5, 
                            duration: 0.3 
                        });
                    @endif
                }, 1500);
            },
            error: function() {
                hideBotTyping();
                appendMessage('bot', 'Desculpe, ocorreu um erro. Tente novamente.');
            }
        });
    }

    // Show bot typing indicator
    function showBotTyping() {
        gsap.to('#bot-typing', { 
            opacity: 1, 
            height: 'auto',
            duration: 0.3 
        });
        
        // Animate dots
        gsap.to('#bot-typing .dots span', {
            y: -5,
            duration: 0.6,
            ease: "power2.inOut",
            stagger: 0.1,
            repeat: -1,
            yoyo: true
        });
    }

    function hideBotTyping() {
        gsap.to('#bot-typing', { 
            opacity: 0, 
            height: 0,
            duration: 0.3 
        });
    }

    // Append message with animation
    function appendMessage(type, text) {
        const isUser = type === 'user';
        const messageHtml = `
            <div class="message-wrapper ${isUser ? 'user-wrapper' : 'bot-wrapper'}">
                ${!isUser ? '<div class="bot-avatar"><i class="fas fa-robot"></i></div>' : ''}
                <div class="message-content">
                    <div class="message ${isUser ? 'user-message' : 'bot-message'}">${text}</div>
                    <div class="message-time">${new Date().toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})}</div>
                </div>
            </div>
        `;

        $('#chatbot-messages').append(messageHtml);
        
        const newMessage = $('#chatbot-messages .message-wrapper').last();
        
        // Animate message appearance
        gsap.fromTo(newMessage, 
            { opacity: 0, y: 20, scale: 0.9 },
            { 
                opacity: 1, 
                y: 0, 
                scale: 1,
                duration: 0.4, 
                ease: "back.out(1.7)" 
            }
        );

        // Auto scroll with animation
        const messagesContainer = $('#chatbot-messages')[0];
        gsap.to(messagesContainer, {
            scrollTop: messagesContainer.scrollHeight,
            duration: 0.5,
            ease: "power2.out"
        });
    }

    function showErrorMessage(text) {
        gsap.to('.phone-input', { 
            x: [-10, 10, -10, 10, 0],
            duration: 0.5,
            ease: "power2.out"
        });
    }

    // Send message events (inicialmente via AJAX)
    $('#chatbot-send').on('click', sendMessage);
    $('#chatbot-input').on('keypress', function(e) {
        if (e.which === 13 && !$(this).prop('disabled')) {
            sendMessage();
        }
    });

    // Start pulse animation after page load
    setTimeout(startPulseAnimation, 2000);

    // Show notification dot after some time
    setTimeout(() => {
        gsap.to('#notification-dot', { scale: 1, duration: 0.3 });
    }, 5000);

    // Handle window resize
    $(window).on('resize', function() {
        if (window.innerWidth > 768 && isOpen) {
            gsap.to('.chatbot-overlay', { opacity: 0, duration: 0.3 });
        }
    });
});
</script>

@include('components.style.chatstyle')