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

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="{{ asset('admin/js/jquery-3.6.3.min.js') }}"></script>

<script>
$(document).ready(function() {
    let conversationId = null;
    let isOpen = false;
    let isMinimized = false;
    let hasStarted = false;

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

    // Send message function
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
                conversationId = response.conversation_id;
                
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

    // Send message events
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

<style>
/* Chatbot Styles */
.chatbot-float-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    cursor: pointer;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.chatbot-float-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.6);
}

.chatbot-float-btn .chat-icon {
    color: white;
    font-size: 24px;
}

.notification-dot {
    position: absolute;
    top: -2px;
    right: -2px;
    width: 16px;
    height: 16px;
    background: #ef4444;
    border-radius: 50%;
    border: 2px solid white;
    transform: scale(0);
    animation: pulse-notification 2s infinite;
}

@keyframes pulse-notification {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.chatbot-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 380px;
    height: 500px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    z-index: 1001;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid rgba(102, 126, 234, 0.1);
}

.chatbot-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.avatar {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.header-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.bot-name {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    opacity: 0.9;
}

.status-dot {
    width: 6px;
    height: 6px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse-status 2s infinite;
}

@keyframes pulse-status {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.header-actions {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.2s ease;
}

.action-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

.chatbot-messages {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.chatbot-messages::-webkit-scrollbar {
    width: 4px;
}

.chatbot-messages::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.chatbot-messages::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 2px;
}

.welcome-message, .message-wrapper {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.user-wrapper {
    flex-direction: row-reverse;
}

.bot-avatar {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    flex-shrink: 0;
}

.message-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
    max-width: 80%;
}

.message {
    padding: 12px 16px;
    border-radius: 18px;
    font-size: 14px;
    line-height: 1.4;
    word-wrap: break-word;
}

.bot-message {
    background: white;
    border: 1px solid #e2e8f0;
    color: #374151;
    border-bottom-left-radius: 4px;
}

.user-message {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

.message-time {
    font-size: 11px;
    color: #9ca3af;
    opacity: 0.7;
    padding: 0 4px;
}

.user-wrapper .message-time {
    text-align: right;
}

.typing-indicator {
    display: flex;
    align-items: center;
    gap: 4px;
    opacity: 0;
}

.typing-indicator span {
    width: 6px;
    height: 6px;
    background: #cbd5e1;
    border-radius: 50%;
    animation: typing-dots 1.4s ease-in-out infinite;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing-dots {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.4;
    }
    30% {
        transform: translateY(-10px);
        opacity: 1;
    }
}

.chatbot-input-area {
    padding: 20px;
    background: white;
    border-top: 1px solid #e2e8f0;
    flex-shrink: 0;
}

.phone-input-container {
    margin-bottom: 12px;
    height: 0;
    opacity: 0;
    overflow: hidden;
}

.message-input-container {
    opacity: 0.5;
    transition: opacity 0.3s ease;
}

.input-group {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 4px;
    transition: all 0.2s ease;
}

.input-group:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.input-icon {
    padding: 0 8px;
    color: #9ca3af;
    font-size: 14px;
}

.form-input {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    padding: 12px 8px;
    font-size: 14px;
    color: #374151;
}

.form-input::placeholder {
    color: #9ca3af;
}

.send-btn {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.2s ease;
}

.send-btn:hover:not(:disabled) {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

.typing-indicator-container {
    margin-top: 8px;
    height: 0;
    overflow: hidden;
}

.bot-typing {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #6b7280;
    opacity: 0;
}

.bot-typing .dots {
    display: flex;
    gap: 2px;
}

.bot-typing .dots span {
    width: 4px;
    height: 4px;
    background: #9ca3af;
    border-radius: 50%;
}

.chatbot-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    z-index: 999;
    opacity: 0;
    pointer-events: none;
    backdrop-filter: blur(2px);
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .chatbot-container {
        bottom: 0;
        right: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        border-radius: 0;
        max-height: none;
    }
    
    .chatbot-float-btn {
        bottom: 80px; /* Above mobile navigation if present */
    }
    
    .chatbot-overlay {
        pointer-events: all;
    }
}

@media (max-width: 480px) {
    .chatbot-messages {
        padding: 16px;
    }
    
    .chatbot-input-area {
        padding: 16px;
    }
    
    .message-content {
        max-width: 85%;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .chatbot-container {
        background: #1f2937;
        border-color: rgba(102, 126, 234, 0.2);
    }
    
    .chatbot-messages {
        background: #111827;
    }
    
    .bot-message {
        background: #374151;
        border-color: #4b5563;
        color: #f3f4f6;
    }
    
    .input-group {
        background: #374151;
        border-color: #4b5563;
    }
    
    .form-input {
        color: #f3f4f6;
    }
    
    .form-input::placeholder {
        color: #9ca3af;
    }
}
</style>