<h2>Nova mensagem do site</h2>

<p><b>Nome:</b> {{ $dados['nome'] }}</p>
<p><b>Email:</b> {{ $dados['email'] }}</p>
<p><b>Telefone:</b> {{ $dados['telefone'] ?? '-' }}</p>
<p><b>Serviço:</b> {{ $dados['servico'] }}</p>
<p><b>Mensagem:</b></p>
<p>{{ $dados['mensagem'] }}</p>
