<x-admin.layout title="Conversa #{{ $conversa->id }}">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <x-header.titulo pageTitle="Conversa com {{ $conversa->user?->name ?? 'Anônimo' }}"/>
            <x-alert/>

            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $conversa->user?->name ?? 'Cliente Anônimo' }}</h5>
                            <small class="opacity-75">
                                {{ $conversa->messages->count() }} mensagens • 
                                Iniciado em {{ $conversa->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-circle text-success me-1" style="font-size: 8px;"></i>
                                Ativo
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="chat-container" style="height: 500px; overflow-y: auto; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                    <div class="chat-messages p-3">
                        @foreach($conversa->messages as $msg)
                            @if($msg->role === 'user')
                                <!-- Mensagem do Cliente -->
                                <div class="message-wrapper user-message mb-3 d-flex justify-content-end">
                                    <div class="message-bubble user-bubble">
                                        <div class="message-content">
                                            {{ $msg->body }}
                                        </div>
                                        <div class="message-time">
                                            {{ $msg->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                    <div class="avatar-small ms-2">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                            @else
                                <!-- Mensagem do Bot -->
                                <div class="message-wrapper bot-message mb-3 d-flex justify-content-start">
                                    <div class="avatar-small me-2">
                                        <i class="fas fa-robot"></i>
                                    </div>
                                    <div class="message-bubble bot-bubble">
                                        <div class="message-content">
                                            {{ $msg->body }}
                                        </div>
                                        <div class="message-time">
                                            {{ $msg->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        
                        @if($conversa->messages->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-comments text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">Nenhuma mensagem ainda</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="card-footer bg-light border-0 py-3">
                    <div class="d-flex align-items-center text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>
                            Esta conversa contém {{ $conversa->messages->count() }} mensagens
                        </small>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.conversations.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar às Conversas
                </a>
                <div class="btn-group">
                    <button class="btn btn-outline-primary" onclick="scrollToTop()">
                        <i class="fas fa-arrow-up"></i> Topo
                    </button>
                    <button class="btn btn-outline-primary" onclick="scrollToBottom()">
                        <i class="fas fa-arrow-down"></i> Fim
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
        }
        
        .avatar-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        .avatar-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }
        
        .user-message .avatar-small {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }
        
        .bot-message .avatar-small {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .chat-container {
            position: relative;
        }
        
        .chat-messages {
            min-height: 100%;
        }
        
        .message-bubble {
            max-width: 70%;
            border-radius: 18px;
            padding: 12px 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            position: relative;
            animation: messageSlideIn 0.3s ease-out;
        }
        
        .user-bubble {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-bottom-right-radius: 4px;
        }
        
        .bot-bubble {
            background: white;
            color: #333;
            border: 1px solid #e9ecef;
            border-bottom-left-radius: 4px;
        }
        
        .message-content {
            word-wrap: break-word;
            line-height: 1.4;
            margin-bottom: 4px;
        }
        
        .message-time {
            font-size: 11px;
            opacity: 0.7;
            text-align: right;
        }
        
        .bot-bubble .message-time {
            text-align: left;
        }
        
        @keyframes messageSlideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .chat-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .chat-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .chat-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        .chat-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .btn {
            transition: all 0.2s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        @media (max-width: 768px) {
            .message-bubble {
                max-width: 85%;
            }
            
            .chat-container {
                height: 400px !important;
            }
        }
    </style>

    <script>
        function scrollToTop() {
            const chatContainer = document.querySelector('.chat-container');
            chatContainer.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
        
        function scrollToBottom() {
            const chatContainer = document.querySelector('.chat-container');
            chatContainer.scrollTo({
                top: chatContainer.scrollHeight,
                behavior: 'smooth'
            });
        }
        
        // Auto scroll to bottom on page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(scrollToBottom, 100);
        });
    </script>
</x-admin.layout>