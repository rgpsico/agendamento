<x-admin.layout title="Conversas">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <x-header.titulo pageTitle="Conversas do Bot"/>
            <x-alert/>

            <!-- Stats Cards -->
            <div class="row mb-4 stats-cards">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $conversas->total() }}</h3>
                            <p>Total de Conversas</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $conversas->where('user_id', '!=', null)->count() }}</h3>
                            <p>Usuários Ativos</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $conversas->groupBy('bot_id')->count() }}</h3>
                            <p>Bots Ativos</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $conversas->where('updated_at', '>=', now()->subDay())->count() }}</h3>
                            <p>Hoje</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-card mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="search-wrapper">
                                    <input type="text" class="form-control search-input" placeholder="Buscar conversas...">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="filter-buttons">
                                    <button class="btn btn-filter active" data-filter="all">
                                        <i class="fas fa-list me-1"></i> Todas
                                    </button>
                                    <button class="btn btn-filter" data-filter="today">
                                        <i class="fas fa-calendar-day me-1"></i> Hoje
                                    </button>
                                    <button class="btn btn-filter" data-filter="week">
                                        <i class="fas fa-calendar-week me-1"></i> Semana
                                    </button>
                                    <button class="btn btn-filter" data-filter="anonymous">
                                        <i class="fas fa-user-secret me-1"></i> Anônimos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversations Table -->
            <div class="conversations-container">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-comments me-2"></i>
                            Lista de Conversas
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 conversations-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="sortable" data-sort="id">
                                            <i class="fas fa-hashtag me-1"></i>ID
                                            <i class="fas fa-sort sort-icon"></i>
                                        </th>
                                        <th>
                                            <i class="fas fa-user me-1"></i>Cliente
                                        </th>
                                        <th>
                                            <i class="fas fa-robot me-1"></i>Bot
                                        </th>
                                        <th>
                                            <i class="fas fa-message me-1"></i>Última Mensagem
                                        </th>
                                        <th class="sortable" data-sort="date">
                                            <i class="fas fa-calendar me-1"></i>Data
                                            <i class="fas fa-sort sort-icon"></i>
                                        </th>
                                        <th class="text-center">
                                            <i class="fas fa-cogs me-1"></i>Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($conversas as $conversa)
                                    <tr class="conversation-row" data-conversation-id="{{ $conversa->id }}" data-date="{{ $conversa->updated_at->format('Y-m-d') }}">
                                        <td>
                                            <div class="id-badge">
                                                #{{ $conversa->id }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar {{ $conversa->user ? 'user-registered' : 'user-anonymous' }}">
                                                    <i class="fas {{ $conversa->user ? 'fa-user' : 'fa-user-secret' }}"></i>
                                                </div>
                                                <div class="user-details">
                                                    <strong>{{ $conversa->user?->name ?? 'Anônimo' }}</strong>
                                                    @if($conversa->user)
                                                        <small class="text-success d-block">
                                                            <i class="fas fa-check-circle me-1"></i>Registrado
                                                        </small>
                                                    @else
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-question-circle me-1"></i>Visitante
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="bot-badge">
                                                <i class="fas fa-robot me-2"></i>
                                                {{ $conversa->bot?->nome ?? 'Bot Padrão' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="message-preview">
                                                <p class="mb-1">{{ Str::limit($conversa->mensagem, 50) }}</p>
                                                <div class="message-indicators">
                                                    <span class="message-count">
                                                        <i class="fas fa-comments me-1"></i>
                                                        {{ $conversa->messages_count ?? 'N/A' }} msgs
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="date-info">
                                                <strong>{{ $conversa->updated_at->format('d/m/Y') }}</strong>
                                                <small class="d-block text-muted">{{ $conversa->updated_at->format('H:i') }}</small>
                                                <small class="time-ago">{{ $conversa->updated_at->diffForHumans() }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.conversations.show', $conversa->id) }}" 
                                                   class="btn btn-sm btn-primary action-btn">
                                                    <i class="fas fa-eye me-1"></i>Ver
                                                </a>
                                                <button class="btn btn-sm btn-outline-secondary action-btn" 
                                                        onclick="quickPreview({{ $conversa->id }})">
                                                    <i class="fas fa-search me-1"></i>Preview
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper mt-4">
                {{ $conversas->links() }}
            </div>

            <!-- Quick Preview Modal -->
             <div class="modal fade" id="quickPreviewModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-eye me-2"></i>Preview Rápido
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="quickPreviewContent">
                            <div class="text-center py-4">
                                <i class="fas fa-spinner fa-spin fa-2x"></i>
                                <p class="mt-2">Carregando conversa...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GSAP CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-right: 16px;
        }

        .stat-icon.bg-success {
            background: linear-gradient(135deg, #11998e, #38ef7d);
        }

        .stat-icon.bg-warning {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }

        .stat-icon.bg-info {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
        }

        .stat-content h3 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
        }

        .stat-content p {
            margin: 0;
            color: #718096;
            font-weight: 500;
        }

        /* Filters */
        .filters-card {
            opacity: 0;
            transform: translateY(20px);
        }

        .search-wrapper {
            position: relative;
        }

        .search-input {
            padding-left: 45px;
            border: 2px solid #e2e8f0;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        .filter-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .btn-filter {
            border: 2px solid #e2e8f0;
            background: white;
            color: #4a5568;
            border-radius: 20px;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }

        .btn-filter:hover, .btn-filter.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        /* Table Styles */
        .conversations-container {
            opacity: 0;
            transform: translateY(30px);
        }

        .conversations-table th {
            background: #f8fafc;
            border: none;
            padding: 16px;
            font-weight: 600;
            color: #4a5568;
            position: relative;
        }

        .sortable {
            cursor: pointer;
            user-select: none;
        }

        .sortable:hover {
            background: #edf2f7;
        }

        .sort-icon {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.5;
        }

        .conversation-row {
            border: none;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateX(-20px);
        }

        .conversation-row:hover {
            background: linear-gradient(90deg, #f8fafc 0%, #edf2f7 100%);
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .conversation-row td {
            padding: 16px;
            border: none;
            vertical-align: middle;
        }

        .id-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
            display: inline-block;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .user-registered {
            background: linear-gradient(135deg, #11998e, #38ef7d);
        }

        .user-anonymous {
            background: linear-gradient(135deg, #a8a8a8, #6c757d);
        }

        .user-details strong {
            color: #2d3748;
            display: block;
        }

        .bot-badge {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
            display: inline-block;
        }

        .message-preview p {
            color: #4a5568;
            margin: 0;
            line-height: 1.4;
        }

        .message-indicators {
            margin-top: 4px;
        }

        .message-count {
            background: #e2e8f0;
            color: #4a5568;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
        }

        .date-info strong {
            color: #2d3748;
        }

        .time-ago {
            color: #a0aec0;
            font-size: 11px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .action-btn {
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-buttons {
                justify-content: flex-start;
                flex-wrap: wrap;
                margin-top: 16px;
            }
            
            .stat-card {
                flex-direction: column;
                text-align: center;
            }
            
            .stat-icon {
                margin-right: 0;
                margin-bottom: 12px;
            }
        }

        /* Loading Animation */
        .loading-row {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Preview Modal Styles */
        .conversation-preview {
            max-width: 100%;
        }

        .preview-header {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .conversation-avatar {
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

        .conversation-stats {
            display: flex;
            gap: 20px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
        }

        .preview-messages {
            padding: 0 10px;
        }

        .preview-message {
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
            opacity: 0;
            transform: translateY(20px);
        }

        .preview-message.user-message {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            margin-left: 20px;
        }

        .preview-message.bot-message {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            margin-right: 20px;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .message-sender {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sender-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .user-avatar {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        .bot-avatar {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            color: white;
        }

        .message-time {
            font-size: 11px;
            opacity: 0.7;
        }

        .message-body {
            line-height: 1.5;
            word-wrap: break-word;
        }

        .preview-footer {
            background: #f8fafc;
            padding: 15px 20px;
            border-radius: 0 0 12px 12px;
            margin: 0 -20px -20px -20px;
        }

        .preview-messages::-webkit-scrollbar {
            width: 6px;
        }

        .preview-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .preview-messages::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        /* Modal Customization */
        .modal-content {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
        }

        .modal-title {
            font-weight: 600;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }
    </style>

    <script>
        // GSAP Animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stats cards
            gsap.from('.stat-card', {
                duration: 0.6,
                y: 30,
                opacity: 0,
                stagger: 0.1,
                ease: "back.out(1.7)"
            });

            // Animate filters
            gsap.to('.filters-card', {
                duration: 0.5,
                y: 0,
                opacity: 1,
                delay: 0.3,
                ease: "power2.out"
            });

            // Animate conversations container
            gsap.to('.conversations-container', {
                duration: 0.6,
                y: 0,
                opacity: 1,
                delay: 0.5,
                ease: "power2.out"
            });

            // Animate table rows
            gsap.to('.conversation-row', {
                duration: 0.4,
                x: 0,
                opacity: 1,
                stagger: 0.05,
                delay: 0.8,
                ease: "power2.out"
            });

            // Hover animations for stat cards
            document.querySelectorAll('.stat-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    gsap.to(this, {duration: 0.3, scale: 1.05, ease: "power2.out"});
                });
                
                card.addEventListener('mouseleave', function() {
                    gsap.to(this, {duration: 0.3, scale: 1, ease: "power2.out"});
                });
            });
        });

        // Search functionality
        const searchInput = document.querySelector('.search-input');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.conversation-row');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    gsap.to(row, {duration: 0.3, opacity: 1, x: 0, ease: "power2.out"});
                } else {
                    gsap.to(row, {duration: 0.3, opacity: 0.3, x: -10, ease: "power2.out"});
                }
            });
        });

        // Filter functionality
        document.querySelectorAll('.btn-filter').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                const rows = document.querySelectorAll('.conversation-row');
                const today = new Date().toISOString().split('T')[0];
                const weekAgo = new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
                
                rows.forEach((row, index) => {
                    let show = true;
                    const rowDate = row.dataset.date;
                    const isAnonymous = row.textContent.includes('Anônimo');
                    
                    switch(filter) {
                        case 'today':
                            show = rowDate === today;
                            break;
                        case 'week':
                            show = rowDate >= weekAgo;
                            break;
                        case 'anonymous':
                            show = isAnonymous;
                            break;
                    }
                    
                    if (show) {
                        gsap.fromTo(row, 
                            {opacity: 0, x: -20}, 
                            {duration: 0.4, opacity: 1, x: 0, delay: index * 0.02, ease: "power2.out"}
                        );
                        row.style.display = '';
                    } else {
                        gsap.to(row, {
                            duration: 0.3, 
                            opacity: 0, 
                            x: -20, 
                            ease: "power2.out",
                            onComplete: () => row.style.display = 'none'
                        });
                    }
                });
            });
        });

        // Quick Preview Modal
          function quickPreview(conversationId) {
            const modal = new bootstrap.Modal(document.getElementById('quickPreviewModal'));
            const previewContent = document.getElementById('quickPreviewContent');

            // Exibir loading
            previewContent.innerHTML = `<div class="text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2">Carregando conversa...</p>
            </div>`;
            modal.show();

            fetch(`/api/conversations/${conversationId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                let messagesHtml = '';
                data.messages.forEach(msg => {
                    const isUser = msg.role === 'user';
                    messagesHtml += `
                        <div class="mb-2 p-2 rounded ${isUser ? 'bg-light text-dark text-end' : 'bg-primary text-white text-start'}">
                            <strong>${isUser ? data.user?.name ?? data.user?.email ?? 'Cliente' : data.bot?.nome ?? 'Bot'}</strong>
                            <div>${msg.body}</div>
                            <small class="text-muted">${new Date(msg.created_at).toLocaleString('pt-BR')}</small>
                        </div>
                    `;
                });

                previewContent.innerHTML = `<div style="max-height:400px; overflow-y:auto;">${messagesHtml}</div>`;
            })
            .catch(err => {
                previewContent.innerHTML = `<div class="alert alert-danger">Erro ao carregar conversa</div>`;
            });
        }
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".sortable").forEach(header => {
        header.addEventListener("click", function () {
            const table = header.closest("table");
            const tbody = table.querySelector("tbody");
            const rows = Array.from(tbody.querySelectorAll("tr"));
            const sortKey = this.dataset.sort;
            const ascending = !this.classList.contains("asc");

            // Remove estado de outros cabeçalhos
            table.querySelectorAll(".sortable").forEach(h => h.classList.remove("asc", "desc"));
            this.classList.add(ascending ? "asc" : "desc");

            rows.sort((a, b) => {
                let aVal, bVal;

                if (sortKey === "id") {
                    aVal = parseInt(a.querySelector("td:first-child").innerText.replace("#", ""));
                    bVal = parseInt(b.querySelector("td:first-child").innerText.replace("#", ""));
                }

                if (sortKey === "date") {
                    aVal = new Date(a.dataset.date);
                    bVal = new Date(b.dataset.date);
                }

                return ascending ? aVal - bVal : bVal - aVal;
            });

            // Reanexa as linhas ordenadas
            rows.forEach(r => tbody.appendChild(r));
        });
    });
});
</script>

</x-admin.layout>