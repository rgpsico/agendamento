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
    .chatbot-float-btn{    
        left: 20px;   /* Fica no lado esquerdo */
        bottom:0%;
        z-index: 1000;
        right: auto;  /* Remove fixação à direita */
   
 

    }
    .chatbot-container {
        bottom: 10px;
        left: 20px;   /* Fica no lado esquerdo */
        right: auto;  /* Remove fixação à direita */
        width: 90%;   /* Ajuste proporcional */
        height: 80vh; /* Evita ocupar tela toda */
        border-radius: 12px;
        max-height: none;
        background: red;
    }
    
    .chatbot-float-btn {
        bottom: 80px;
        right: 20px; /* Mantém botão no lado direito */
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
