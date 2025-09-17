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

<!-- GSAP Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

<!-- Estilos Modernos -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

:root {
    --primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --dark: #1a1a2e;
    --glass: rgba(255, 255, 255, 0.1);
    --glass-border: rgba(255, 255, 255, 0.2);
    --text-light: rgba(255, 255, 255, 0.9);
    --shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    --shadow-hover: 0 25px 50px rgba(0, 0, 0, 0.15);
}

* {
    font-family: 'Inter', sans-serif;
}

.page-wrapper {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}

.page-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
    animation: backgroundShift 20s ease-in-out infinite alternate;
}

@keyframes backgroundShift {
    0% { transform: translate(0, 0) rotate(0deg); }
    100% { transform: translate(50px, 30px) rotate(5deg); }
}

.content {
    position: relative;
    z-index: 2;
}

.card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 24px;
    box-shadow: var(--shadow);
    overflow: hidden;
    transition: all 0.3s ease;
    transform: translateY(30px);
    opacity: 0;
}

.card:hover {
    box-shadow: var(--shadow-hover);
    transform: translateY(0);
}

.card-body {
    padding: 2.5rem;
    background: linear-gradient(145deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
}

/* Controle da conversa */
.d-flex h5 {
    background: var(--primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
    font-size: 1.4rem;
    margin: 0;
    text-shadow: none;
    position: relative;
}

.d-flex h5::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--primary);
    border-radius: 2px;
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.d-flex:hover h5::after {
    transform: scaleX(1);
}

/* Switch moderno */
.form-check-input {
    width: 3.5rem;
    height: 1.75rem;
    background: var(--glass);
    border: 2px solid var(--glass-border);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    cursor: pointer;
}

.form-check-input:checked {
    background: var(--success);
    border-color: transparent;
    box-shadow: 0 0 20px rgba(79, 172, 254, 0.5);
}

.form-check-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
}

.form-check-label {
    font-weight: 500;
    color: #444;
    cursor: pointer;
    margin-left: 0.75rem;
    transition: all 0.3s ease;
}

.form-check:hover .form-check-label {
    color: #667eea;
    transform: translateX(5px);
}

/* Chat Box */
#chat-box {
    background: linear-gradient(145deg, #f8f9ff 0%, #e8f4ff 100%);
    border: 2px solid var(--glass-border);
    border-radius: 20px;
    box-shadow: 
        inset 0 1px 0 rgba(255, 255, 255, 0.6),
        0 10px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

#chat-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 20%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(240, 147, 251, 0.1) 0%, transparent 50%);
    pointer-events: none;
    animation: chatGlow 8s ease-in-out infinite alternate;
}

@keyframes chatGlow {
    0% { opacity: 0.3; }
    100% { opacity: 0.7; }
}

#messages {
    position: relative;
    z-index: 2;
}

/* Mensagens */
.text-end .badge, .text-start .badge {
    font-size: 0.95rem;
    padding: 0.75rem 1.25rem;
    border-radius: 18px;
    max-width: 280px;
    word-wrap: break-word;
    position: relative;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    display: inline-block;
    font-weight: 500;
    transform: translateY(20px);
    opacity: 0;
    transition: all 0.3s ease;
}

.text-end .badge {
    background: var(--primary) !important;
    color: white;
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    animation: slideInRight 0.6s ease-out forwards;
}

.text-start .badge {
    background: var(--glass) !important;
    color: #333;
    border: 2px solid var(--glass-border);
    animation: slideInLeft 0.6s ease-out forwards;
}

@keyframes slideInRight {
    from {
        transform: translateX(30px) translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateX(0) translateY(0);
        opacity: 1;
    }
}

@keyframes slideInLeft {
    from {
        transform: translateX(-30px) translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateX(0) translateY(0);
        opacity: 1;
    }
}

.badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Scrollbar personalizada */
#chat-box::-webkit-scrollbar {
    width: 8px;
}

#chat-box::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 10px;
}

#chat-box::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 10px;
    border: 2px solid transparent;
    background-clip: content-box;
}

#chat-box::-webkit-scrollbar-thumb:hover {
    background: var(--secondary);
    background-clip: content-box;
}

/* Input Group */
.input-group {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50px;
    padding: 0.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--glass-border);
    backdrop-filter: blur(20px);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.input-group::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    transition: left 0.5s ease;
}

.input-group:hover::before {
    left: 100%;
}

.input-group:focus-within {
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
    border-color: rgba(102, 126, 234, 0.5);
    transform: translateY(-2px);
}

.form-control {
    background: transparent;
    border: none;
    font-weight: 500;
    font-size: 1rem;
    padding: 1rem 1.5rem;
    color: #333;
    position: relative;
    z-index: 2;
}

.form-control:focus {
    background: transparent;
    border: none;
    box-shadow: none;
    color: #333;
}

.form-control::placeholder {
    color: rgba(51, 51, 51, 0.6);
    font-weight: 400;
}

/* Botão */
.btn-primary {
    background: var(--primary);
    border: none;
    border-radius: 50px;
    padding: 1rem 1.5rem;
    font-weight: 600;
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
    overflow: hidden;
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: all 0.3s ease;
}

.btn-primary:hover::before {
    width: 300px;
    height: 300px;
}

.btn-primary:hover {
    background: var(--secondary);
    box-shadow: 0 10px 30px rgba(240, 147, 251, 0.5);
    transform: translateY(-3px);
}

.btn-primary:active {
    transform: translateY(0);
}

.btn-primary i {
    position: relative;
    z-index: 3;
    transition: transform 0.3s ease;
}

.btn-primary:hover i {
    transform: translateX(3px);
}

/* Animações de entrada */
.card {
    animation: cardSlideIn 0.8s ease-out forwards;
}

@keyframes cardSlideIn {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Responsividade */
@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem;
    }
    
    .d-flex {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .form-check {
        justify-content: center;
    }
    
    #chat-box {
        height: 300px !important;
    }
    
    .badge {
        max-width: 250px !important;
    }
}

/* Estados especiais */
.human-active {
    border-color: rgba(79, 172, 254, 0.8) !important;
    box-shadow: 0 0 30px rgba(79, 172, 254, 0.3) !important;
}

.bot-active {
    border-color: rgba(118, 75, 162, 0.8) !important;
    box-shadow: 0 0 30px rgba(118, 75, 162, 0.3) !important;
}

/* Efeito de loading */
.loading-pulse {
    position: relative;
}

.loading-pulse::after {
    content: '';
    position: absolute;
    top: 50%;
    right: 1rem;
    width: 20px;
    height: 20px;
    border: 2px solid transparent;
    border-top: 2px solid rgba(255, 255, 255, 0.7);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}
</style>

<!-- Script GSAP -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Timeline principal de animações
    const tl = gsap.timeline();
    
    // Animação de entrada do card
    tl.from('.card', {
        duration: 1,
        y: 100,
        opacity: 0,
        scale: 0.9,
        ease: "back.out(1.7)"
    })
    .from('.card h5', {
        duration: 0.6,
        x: -50,
        opacity: 0,
        ease: "power2.out"
    }, "-=0.5")
    .from('.form-check', {
        duration: 0.6,
        x: 50,
        opacity: 0,
        ease: "power2.out"
    }, "-=0.4")
    .from('#chat-box', {
        duration: 0.8,
        y: 30,
        opacity: 0,
        ease: "power2.out"
    }, "-=0.3")
    .from('#chat-form', {
        duration: 0.8,
        y: 30,
        opacity: 0,
        ease: "power2.out"
    }, "-=0.5");

    // Animação das mensagens existentes
    gsap.from('.badge', {
        duration: 0.6,
        y: 20,
        opacity: 0,
        stagger: 0.1,
        ease: "back.out(1.7)",
        delay: 0.5
    });

    // Função para animar novas mensagens
    function animateNewMessage(element) {
        gsap.fromTo(element, 
            {
                y: 30,
                opacity: 0,
                scale: 0.8
            },
            {
                y: 0,
                opacity: 1,
                scale: 1,
                duration: 0.6,
                ease: "back.out(1.7)"
            }
        );
    }

    // Observar mudanças no chat para animar novas mensagens
    const chatMessages = document.getElementById('messages');
    if (chatMessages) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1 && node.querySelector('.badge')) {
                            animateNewMessage(node.querySelector('.badge'));
                        }
                    });
                }
            });
        });
        
        observer.observe(chatMessages, {
            childList: true,
            subtree: true
        });
    }

    // Animações de hover e interação
    const elements = {
        card: document.querySelector('.card'),
        switchInput: document.getElementById('humanToggle'),
        chatBox: document.getElementById('chat-box'),
        sendButton: document.querySelector('.btn-primary'),
        messageInput: document.getElementById('mensagem')
    };

    // Hover no card
    if (elements.card) {
        elements.card.addEventListener('mouseenter', function() {
            gsap.to(this, {
                duration: 0.3,
                y: -5,
                boxShadow: "0 30px 60px rgba(0, 0, 0, 0.15)"
            });
        });
        
        elements.card.addEventListener('mouseleave', function() {
            gsap.to(this, {
                duration: 0.3,
                y: 0,
                boxShadow: "0 20px 40px rgba(0, 0, 0, 0.1)"
            });
        });
    }

    // Switch animation
    if (elements.switchInput) {
        elements.switchInput.addEventListener('change', function() {
            const chatBox = elements.chatBox;
            
            if (this.checked) {
                gsap.to(chatBox, {
                    duration: 0.5,
                    borderColor: "rgba(79, 172, 254, 0.8)",
                    boxShadow: "0 0 30px rgba(79, 172, 254, 0.3)"
                });
                chatBox.classList.add('human-active');
                chatBox.classList.remove('bot-active');
            } else {
                gsap.to(chatBox, {
                    duration: 0.5,
                    borderColor: "rgba(118, 75, 162, 0.8)",
                    boxShadow: "0 0 30px rgba(118, 75, 162, 0.3)"
                });
                chatBox.classList.add('bot-active');
                chatBox.classList.remove('human-active');
            }
            
            // Pulse effect no switch
            gsap.to(this, {
                duration: 0.1,
                scale: 1.1,
                yoyo: true,
                repeat: 1
            });
        });
    }

    // Button loading effect
    if (elements.sendButton) {
        elements.sendButton.addEventListener('click', function(e) {
            if (!elements.messageInput.value.trim()) {
                e.preventDefault();
                return;
            }
            
            // Adicionar classe de loading
            this.classList.add('loading-pulse');
            
            // Animação de click
            gsap.to(this, {
                duration: 0.1,
                scale: 0.95,
                yoyo: true,
                repeat: 1
            });
            
            // Remover loading após 2 segundos (simular envio)
            setTimeout(() => {
                this.classList.remove('loading-pulse');
            }, 2000);
        });
    }

    // Input focus animation
    if (elements.messageInput) {
        elements.messageInput.addEventListener('focus', function() {
            gsap.to(this.closest('.input-group'), {
                duration: 0.3,
                scale: 1.02,
                y: -2
            });
        });
        
        elements.messageInput.addEventListener('blur', function() {
            gsap.to(this.closest('.input-group'), {
                duration: 0.3,
                scale: 1,
                y: 0
            });
        });
    }

    // Scroll automático com animação
    function smoothScrollToBottom() {
        const chatBox = elements.chatBox;
        if (chatBox) {
            gsap.to(chatBox, {
                duration: 0.5,
                scrollTop: chatBox.scrollHeight,
                ease: "power2.out"
            });
        }
    }

    // Auto-scroll quando novas mensagens aparecerem
    if (elements.chatBox) {
        const scrollObserver = new MutationObserver(smoothScrollToBottom);
        scrollObserver.observe(elements.chatBox, {
            childList: true,
            subtree: true
        });
    }

    // Particle effect no background
    function createParticle() {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
        `;
        
        document.querySelector('.page-wrapper').appendChild(particle);
        
        gsap.set(particle, {
            x: Math.random() * window.innerWidth,
            y: window.innerHeight + 10,
            opacity: 0
        });
        
        gsap.to(particle, {
            duration: Math.random() * 3 + 2,
            y: -10,
            x: `+=${Math.random() * 100 - 50}`,
            opacity: 1,
            ease: "none",
            onComplete: () => particle.remove()
        });
        
        gsap.to(particle, {
            duration: Math.random() * 2 + 1,
            opacity: 0,
            delay: Math.random() * 2
        });
    }

    // Criar partículas periodicamente
    setInterval(createParticle, 3000);
});
</script>
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
