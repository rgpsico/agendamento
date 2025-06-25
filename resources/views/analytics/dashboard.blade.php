<x-admin.layout title="Relatórios">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <h3 class="mb-4">Relatórios do Site</h3>

            <form method="GET" class="mb-4">
                <select name="range" class="form-select" style="max-width:200px" onchange="this.form.submit()">
                    <option value="today" {{ request('range')=='today' ? 'selected' : '' }}>Hoje</option>
                    <option value="7days" {{ request('range')=='7days' ? 'selected' : '' }}>Últimos 7 dias</option>
                    <option value="month" {{ request('range')=='month' ? 'selected' : '' }}>Mês atual</option>
                </select>
            </form>

            <div class="row">
                <div class="col-md-4">
                    <canvas id="visitsChart"></canvas>
                </div>
                <div class="col-md-4">
                    <canvas id="whatsappChart"></canvas>
                </div>
                <div class="col-md-4">
                    <canvas id="loadChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const data = @json($chartData);

        new Chart(document.getElementById('visitsChart'), {
            type: 'bar',
            data: { labels: data.labels, datasets: [{ label: 'Visitas', data: data.visits, backgroundColor: '#0d6efd' }] }
        });

        new Chart(document.getElementById('whatsappChart'), {
            type: 'bar',
            data: { labels: data.labels, datasets: [{ label: 'Cliques WhatsApp', data: data.whatsapp, backgroundColor: '#198754' }] }
        });

        new Chart(document.getElementById('loadChart'), {
            type: 'line',
            data: { labels: data.labels, datasets: [{ label: 'Tempo de Carregamento (ms)', data: data.load, borderColor: '#dc3545' }] }
        });
    </script>
</x-admin.layout>
