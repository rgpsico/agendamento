@extends('layouts.legal')

@section('title', 'Política de Privacidade — PilatesGestão')
@section('page_title', 'Política de Privacidade')
@section('page_subtitle', 'Como tratamos seus dados pessoais')
@section('nav_privacidade', 'active')

@section('content')

<div class="update-info">
    <strong>Última atualização:</strong> <span class="placeholder-tag">[INSERIR DATA]</span><br>
    Esta Política está em conformidade com a Lei Geral de Proteção de Dados (Lei nº 13.709/2018 — LGPD).
</div>

<p>
    O PilatesGestão respeita sua privacidade e está comprometido com a proteção dos seus dados pessoais.
    Esta Política descreve quais dados coletamos, para quais finalidades, com quem compartilhamos e quais
    são seus direitos como titular.
</p>

<h2>1. Quem somos (Controlador)</h2>
<p>
    <strong>Razão Social:</strong> <span class="placeholder-tag">[RAZÃO SOCIAL]</span><br>
    <strong>CNPJ:</strong> <span class="placeholder-tag">[CNPJ]</span><br>
    <strong>Endereço:</strong> <span class="placeholder-tag">[ENDEREÇO COMPLETO]</span><br>
    <strong>Encarregado de Proteção de Dados (DPO):</strong>
    <span class="placeholder-tag">[NOME]</span> —
    <a href="mailto:dpo@pilatesgestao.com.br">dpo@pilatesgestao.com.br</a>
</p>

<h2>2. Quais dados coletamos</h2>

<h3>2.1. Dados que você nos fornece</h3>
<ul>
    <li><strong>Cadastro de Empresa:</strong> nome, e-mail, telefone, CPF/CNPJ, endereço, dados do estúdio.</li>
    <li><strong>Cadastro de Aluno:</strong> nome, e-mail, telefone, data de nascimento e, eventualmente, dados de saúde relevantes (alergias, restrições físicas, lesões) inseridos pelo Usuário Empresa.</li>
    <li><strong>Dados de pagamento:</strong> processados diretamente pelos gateways (Stripe, Asaas). Não armazenamos número completo de cartão de crédito em nossos servidores.</li>
    <li><strong>Conteúdo do estúdio:</strong> agendas, modalidades, planos, mensalidades, fotos, comunicações, avaliações.</li>
</ul>

<h3>2.2. Dados coletados automaticamente</h3>
<ul>
    <li>Endereço IP, dispositivo, navegador, sistema operacional;</li>
    <li>Páginas acessadas, ações realizadas, datas e horários;</li>
    <li>Cookies e identificadores similares (ver seção 9).</li>
</ul>

<h2>3. Para que usamos seus dados</h2>
<p>Tratamos seus dados com base nas seguintes finalidades e bases legais (LGPD):</p>

<table>
    <thead>
        <tr><th>Finalidade</th><th>Base Legal (LGPD)</th></tr>
    </thead>
    <tbody>
        <tr><td>Prestar o serviço contratado</td><td>Execução de contrato</td></tr>
        <tr><td>Processar pagamentos e emitir notas fiscais</td><td>Execução de contrato e obrigação legal</td></tr>
        <tr><td>Cumprir obrigações fiscais e regulatórias</td><td>Obrigação legal</td></tr>
        <tr><td>Atendimento e suporte ao cliente</td><td>Execução de contrato</td></tr>
        <tr><td>Comunicações operacionais sobre o produto</td><td>Legítimo interesse</td></tr>
        <tr><td>Marketing e ofertas comerciais</td><td>Consentimento</td></tr>
        <tr><td>Melhoria do produto e analytics</td><td>Legítimo interesse</td></tr>
        <tr><td>Prevenção a fraude e segurança</td><td>Legítimo interesse</td></tr>
    </tbody>
</table>

<h2>4. Compartilhamento de dados</h2>
<p>Compartilhamos dados estritamente com prestadores necessários para a operação da Plataforma:</p>
<ul>
    <li><strong>Gateways de pagamento:</strong> Stripe, Asaas (processamento de cobranças)</li>
    <li><strong>Provedor de hospedagem em nuvem:</strong> <span class="placeholder-tag">[NOME DO PROVEDOR]</span></li>
    <li><strong>Provedores de e-mail e SMS:</strong> envio de notificações e comunicações transacionais</li>
    <li><strong>Google:</strong> autenticação OAuth e Google Calendar (apenas se ativado pelo Usuário)</li>
    <li><strong>Provedores de IA:</strong> DeepSeek e/ou OpenAI, apenas dados estritamente necessários para o atendimento via chatbot e geração de conteúdo</li>
    <li><strong>Autoridades públicas:</strong> mediante ordem judicial ou exigência legal</li>
</ul>
<p><strong>Não vendemos seus dados a terceiros.</strong></p>

<h2>5. Transferência internacional</h2>
<p>
    Alguns provedores (como Stripe, OpenAI e Google) podem armazenar dados em servidores localizados fora
    do Brasil. Nesses casos, adotamos cláusulas contratuais e mecanismos previstos pela LGPD (art. 33)
    para garantir nível de proteção equivalente ao oferecido no território nacional.
</p>

<h2>6. Por quanto tempo guardamos seus dados</h2>
<p>
    Mantemos seus dados durante a vigência do contrato e por até 5 (cinco) anos após o encerramento, para
    cumprimento de obrigações fiscais, contábeis e legais aplicáveis. Dados utilizados para marketing são
    mantidos enquanto houver consentimento válido, podendo ser revogado a qualquer momento.
</p>

<h2>7. Seus direitos como titular</h2>
<p>Você pode, a qualquer momento, solicitar:</p>
<ul>
    <li>Confirmação da existência de tratamento;</li>
    <li>Acesso aos seus dados;</li>
    <li>Correção de dados incompletos, inexatos ou desatualizados;</li>
    <li>Anonimização, bloqueio ou eliminação de dados desnecessários ou excessivos;</li>
    <li>Portabilidade dos dados;</li>
    <li>Eliminação de dados tratados com base em consentimento;</li>
    <li>Informação sobre as entidades com as quais compartilhamos seus dados;</li>
    <li>Revogação do consentimento.</li>
</ul>
<p>
    Para exercer qualquer desses direitos, envie um e-mail para
    <a href="mailto:dpo@pilatesgestao.com.br">dpo@pilatesgestao.com.br</a>.
    Responderemos em até 15 (quinze) dias.
</p>

<h2>8. Segurança dos dados</h2>
<p>Adotamos medidas técnicas e organizacionais razoáveis para proteger seus dados, incluindo:</p>
<ul>
    <li>Criptografia em trânsito (HTTPS/TLS);</li>
    <li>Controle de acesso por níveis de permissão;</li>
    <li>Isolamento multi-tenant entre clientes;</li>
    <li>Backups regulares e monitoramento de integridade;</li>
    <li>Treinamento periódico da equipe em proteção de dados.</li>
</ul>
<p>
    Apesar dos esforços, nenhum sistema é 100% seguro. Em caso de incidente que possa acarretar risco
    relevante aos titulares, comunicaremos a ANPD e os titulares afetados em prazo razoável.
</p>

<h2>9. Cookies</h2>
<p>Utilizamos cookies para diferentes finalidades:</p>
<ul>
    <li><strong>Essenciais:</strong> indispensáveis ao funcionamento (login, sessão, segurança);</li>
    <li><strong>Desempenho:</strong> métricas anônimas de uso (Google Analytics);</li>
    <li><strong>Funcionalidade:</strong> lembrar preferências do Usuário.</li>
</ul>
<p>Você pode gerenciar ou bloquear cookies pelo seu navegador. A desativação de cookies essenciais pode prejudicar o funcionamento da Plataforma.</p>

<h2>10. Alterações desta Política</h2>
<p>
    Mudanças relevantes nesta Política serão comunicadas por e-mail ou por aviso na Plataforma com 30 (trinta)
    dias de antecedência.
</p>

<h2>11. Contato</h2>
<p>
    Para qualquer dúvida sobre esta Política ou sobre o tratamento dos seus dados:<br>
    DPO: <a href="mailto:dpo@pilatesgestao.com.br">dpo@pilatesgestao.com.br</a><br>
    Geral: <a href="mailto:contato@pilatesgestao.com.br">contato@pilatesgestao.com.br</a>
</p>

@endsection
