<x-admin.layout title="Relatórios">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <h3 class="mb-4">📊 Relatórios do Site</h3>

            <!-- Filtro -->
            <form method="GET" class="mb-4">
                <select name="range" class="form-select shadow-sm" style="max-width:220px" onchange="this.form.submit()">
                    <option value="today" {{ request('range')=='today' ? 'selected' : '' }}>Hoje</option>
                    <option value="7days" {{ request('range')=='7days' ? 'selected' : '' }}>Últimos 7 dias</option>
                    <option value="month" {{ request('range')=='month' ? 'selected' : '' }}>Mês atual</option>
                </select>
            </form>

            <!-- Gráficos -->
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-lg rounded-4 border-0 p-3 chart-card">
                        <h5 class="mb-3 text-primary"><i class="fas fa-eye me-2"></i> Visitas</h5>
                        <canvas id="visitsChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-lg rounded-4 border-0 p-3 chart-card">
                        <h5 class="mb-3 text-success"><i class="fab fa-whatsapp me-2"></i> Cliques WhatsApp</h5>
                        <canvas id="whatsappChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-lg rounded-4 border-0 p-3 chart-card">
                        <h5 class="mb-3 text-danger"><i class="fas fa-tachometer-alt me-2"></i> Tempo de Carregamento</h5>
                        <canvas id="loadChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dependências -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

    <script>
        const data = @json($chartData);
        console.log(data);
        
        // Configurações padrão
        const chartOptions = {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: "#333",
                    titleColor: "#fff",
                    bodyColor: "#ddd",
                    borderWidth: 1,
                    borderColor: "#555"
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: "rgba(0,0,0,0.05)" } }
            }
        };

        // Chart Visitas
        new Chart(document.getElementById('visitsChart'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Visitas',
                    data: data.visits,
                    backgroundColor: "linear-gradient(180deg, #0d6efd 0%, #66b2ff 100%)",
                    borderRadius: 8
                }]
            },
            options: chartOptions
        });

        // Chart WhatsApp
        new Chart(document.getElementById('whatsappChart'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Cliques WhatsApp',
                    data: data.whatsapp,
                    backgroundColor: "linear-gradient(180deg, #198754 0%, #5cd68d 100%)",
                    borderRadius: 8
                }]
            },
            options: chartOptions
        });

        // Chart Carregamento
        new Chart(document.getElementById('loadChart'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Tempo de Carregamento (ms)',
                    data: data.load,
                    borderColor: "#dc3545",
                    backgroundColor: "rgba(220,53,69,0.2)",
                    fill: true,
                    tension: 0.4
                }]
            },
            options: chartOptions
        });

        // GSAP Animation
        gsap.from(".chart-card", {
            opacity: 0,
            y: 50,
            duration: 1,
            stagger: 0.2,
            ease: "power3.out"
        });
    </script>
</x-admin.layout>
