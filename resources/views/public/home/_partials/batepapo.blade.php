       <link rel="stylesheet" href="{{ asset('css/batepapo.css') }}">
       <div class="chat-float-button" id="chatFloatButton" onclick="toggleChat()">
           <i class="fas fa-question-circle"></i>
           <div class="chat-notification-badge" id="chatBadge">1</div>
       </div>

       <!-- Chat Container -->
       <div class="chat-container" id="chatContainer">
           <div class="chat-header">
               <div>
                   <h5>Atendimento <span class="online-status"></span></h5>
               </div>
               <button class="chat-close" onclick="toggleChat()">
                   <i class="fas fa-times"></i>
               </button>
           </div>

           <div class="chat-messages" id="chatMessages">
               <div class="message bot">
                   <div class="message-content">
                       Olá! 👋 Bem-vindo ao nosso atendimento. Como posso ajudá-lo hoje?
                       <div class="message-time" id="welcomeTime"></div>
                   </div>
               </div>
           </div>

           <div class="typing-indicator" id="typingIndicator">
               Atendente digitando
               <span class="typing-dots">
                   <span></span>
                   <span></span>
                   <span></span>
               </span>
           </div>

           <div class="chat-input-container">
               <input type="text" class="chat-input" id="chatInput" placeholder="Digite sua mensagem..."
                   onkeypress="handleKeyPress(event)">
               <button class="chat-send-btn" onclick="sendMessage()">
                   <i class="fas fa-paper-plane"></i>
               </button>
           </div>
       </div>
       <script>
           let isChatOpen = false;
           let messageCount = 0;
           let hasUnreadMessages = false;
           const API_ENDPOINT = '/api/openai/chat-contexto';

           // Initialize chat
           document.addEventListener('DOMContentLoaded', function() {
               updateTime('welcomeTime');
               showNotificationBadge();

               // Auto responses for demo
               setTimeout(() => {
                   addBotMessage(
                       "Posso ajudá-lo com informações sobre nossos passeios e escolas. O que você gostaria de saber?"
                   );
                   if (!isChatOpen) {
                       showNotificationBadge();
                   }
               }, 3000);
           });

           function toggleChat() {
               const container = document.getElementById('chatContainer');
               const floatButton = document.getElementById('chatFloatButton');

               isChatOpen = !isChatOpen;

               if (isChatOpen) {
                   container.classList.add('show');
                   floatButton.style.display = 'none';
                   hideNotificationBadge();
                   hasUnreadMessages = false;
               } else {
                   container.classList.remove('show');
                   floatButton.style.display = 'flex';
               }
           }

           function showNotificationBadge() {
               const badge = document.getElementById('chatBadge');
               if (!isChatOpen) {
                   badge.style.display = 'flex';
                   hasUnreadMessages = true;
               }
           }

           function hideNotificationBadge() {
               const badge = document.getElementById('chatBadge');
               badge.style.display = 'none';
           }

           function sendMessage() {
               const input = document.getElementById('chatInput');
               const message = input.value.trim();

               if (message === '') return;

               addUserMessage(message);
               input.value = '';

               // Show typing indicator
               showTyping();

               // Send message to API
               sendToAPI(message);
           }

           async function sendToAPI(message) {
               try {
                   const response = await fetch(API_ENDPOINT, {
                       method: 'POST',
                       headers: {
                           'Content-Type': 'application/json',
                           'Accept': 'application/json',
                           'X-Requested-With': 'XMLHttpRequest',
                           // Adicione o token CSRF se necessário
                           'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                               'content') || ''
                       },
                       body: JSON.stringify({
                           mensagem: message
                       })
                   });

                   if (!response.ok) {
                       throw new Error(`HTTP error! status: ${response.status}`);
                   }

                   const data = await response.json();

                   hideTyping();

                   if (data.resposta) {
                       addBotMessage(data.resposta);
                   } else {
                       addBotMessage('Desculpe, ocorreu um erro. Tente novamente em alguns instantes.');
                   }

               } catch (error) {
                   console.error('Erro ao enviar mensagem:', error);
                   hideTyping();

                   // Fallback para resposta offline
                   setTimeout(() => {
                       generateBotResponse(message);
                   }, 500);
               }
           }

           function handleKeyPress(event) {
               if (event.key === 'Enter') {
                   sendMessage();
               }
           }

           function addUserMessage(message) {
               const messagesContainer = document.getElementById('chatMessages');
               const messageDiv = document.createElement('div');
               messageDiv.className = 'message user';

               messageDiv.innerHTML = `
                <div class="message-content">
                    ${escapeHtml(message)}
                    <div class="message-time">${getCurrentTime()}</div>
                </div>
            `;

               messagesContainer.appendChild(messageDiv);
               scrollToBottom();
           }

           function addBotMessage(message) {
               const messagesContainer = document.getElementById('chatMessages');
               const messageDiv = document.createElement('div');
               messageDiv.className = 'message bot';

               messageDiv.innerHTML = `
                <div class="message-content">
                    ${message}
                    <div class="message-time">${getCurrentTime()}</div>
                </div>
            `;

               messagesContainer.appendChild(messageDiv);
               scrollToBottom();

               // Show notification if chat is closed
               if (!isChatOpen) {
                   showNotificationBadge();
               }
           }

           function showTyping() {
               document.getElementById('typingIndicator').style.display = 'block';
               scrollToBottom();
           }

           function hideTyping() {
               document.getElementById('typingIndicator').style.display = 'none';
           }

           // Fallback responses (caso a API não esteja disponível)
           function generateBotResponse(userMessage) {
               const responses = {
                   'ola': 'Olá! Como posso ajudá-lo? 😊',
                   'oi': 'Oi! Em que posso ser útil?',
                   'passeio': 'Temos diversos passeios disponíveis! Use os filtros ao lado para encontrar o ideal para você.',
                   'escola': 'Você pode buscar escolas usando o campo de pesquisa. Que tipo de escola procura?',
                   'preco': 'Os preços variam conforme o passeio. Entre em contato conosco para mais detalhes sobre valores.',
                   'contato': 'Você pode entrar em contato pelo telefone (21) 99999-9999 ou pelo email contato@example.com',
                   'horario': 'Nosso atendimento funciona de segunda a sexta, das 8h às 18h.',
                   'obrigado': 'Por nada! Fico feliz em ajudar! 😊',
                   'tchau': 'Até logo! Sempre que precisar, estarei aqui! 👋',
                   'dono': 'O dono e engenheiro deste sistema é Roger Neves. Como posso ajudá-lo hoje?'
               };

               const lowerMessage = userMessage.toLowerCase();
               let response =
                   'Desculpe, estou com dificuldades para me conectar no momento. Nossa equipe estará disponível em breve.';

               // Check for keywords
               for (let keyword in responses) {
                   if (lowerMessage.includes(keyword)) {
                       response = responses[keyword];
                       break;
                   }
               }

               // Special responses based on content
               if (lowerMessage.includes('modalidade')) {
                   response =
                       'Você pode filtrar por modalidade usando as opções ao lado. Temos várias modalidades disponíveis!';
               } else if (lowerMessage.includes('buscar') || lowerMessage.includes('filtro')) {
                   response =
                       'Use os filtros na lateral esquerda para refinar sua busca. Você pode buscar por nome da escola e filtrar por modalidade.';
               }

               addBotMessage(response);
           }

           function scrollToBottom() {
               const messagesContainer = document.getElementById('chatMessages');
               messagesContainer.scrollTop = messagesContainer.scrollHeight;
           }

           function getCurrentTime() {
               const now = new Date();
               return now.toLocaleTimeString('pt-BR', {
                   hour: '2-digit',
                   minute: '2-digit'
               });
           }

           function updateTime(elementId) {
               document.getElementById(elementId).textContent = getCurrentTime();
           }

           function escapeHtml(text) {
               const div = document.createElement('div');
               div.textContent = text;
               return div.innerHTML;
           }

           // Auto-hide notification after some time when chat is closed
           let notificationTimer;

           function resetNotificationTimer() {
               clearTimeout(notificationTimer);
               if (!isChatOpen && hasUnreadMessages) {
                   notificationTimer = setTimeout(() => {
                       // Add a gentle reminder message
                       if (!isChatOpen) {
                           addBotMessage("Ainda estou aqui se precisar de ajuda! 😊");
                       }
                   }, 30000); // 30 seconds
               }
           }

           // Reset timer on user interaction
           document.addEventListener('click', function(e) {
               if (e.target.closest('#chatContainer') || e.target.closest('#chatFloatButton')) {
                   resetNotificationTimer();
               }
           });

           // Initialize notification timer
           setTimeout(resetNotificationTimer, 1000);
       </script>
