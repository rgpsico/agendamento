<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Integração de Pagamentos</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Integração de Pagamentos</h1>
        <p>Escolha o gateway de pagamento para integrar:</p>

        <div class="mt-4">
            <button id="integrarAsaas" class="bg-blue-500 text-white px-4 py-2 rounded">Integrar com Asaas</button>
        </div>

        <div id="resultado" class="mt-4 hidden">
            <p><strong>Status:</strong> <span id="status"></span></p>
            <p><strong>Wallet ID:</strong> <span id="walletId"></span></p>
            <p><strong>Customer ID:</strong> <span id="customerId"></span></p>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.getElementById('integrarAsaas').addEventListener('click', function() {
            fetch('/integrar/asaas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    professor_id: {{ Auth::user()->professor->id ?? 'null' }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('status').textContent = 'Integração bem-sucedida!';
                    document.getElementById('walletId').textContent = data.walletId || 'Não disponível';
                    document.getElementById('customerId').textContent = data.customerId;
                    document.getElementById('resultado').classList.remove('hidden');
                } else {
                    document.getElementById('status').textContent = 'Erro: ' + data.message;
                    document.getElementById('resultado').classList.remove('hidden');
                }
            })
            .catch(error => {
                document.getElementById('status').textContent = 'Erro na requisição: ' + error.message;
                document.getElementById('resultado').classList.remove('hidden');
            });
        });
    </script>
</body>
</html>