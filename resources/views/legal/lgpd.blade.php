@extends('layouts.legal')

@section('title', 'LGPD — PilatesGestão')
@section('page_title', 'LGPD')
@section('page_subtitle', 'Lei Geral de Proteção de Dados — Lei nº 13.709/2018')
@section('nav_lgpd', 'active')

@section('content')

<div class="update-info">
    <strong>Última atualização:</strong> <span class="placeholder-tag">[INSERIR DATA]</span><br>
    Esta página resume nossos compromissos com a LGPD e como você pode exercer seus direitos.
</div>

<h2>1. Nosso compromisso</h2>
<p>
    O PilatesGestão está em conformidade com a Lei Geral de Proteção de Dados (Lei nº 13.709/2018).
    Tratamos dados pessoais com transparência, finalidade legítima, segurança e respeito aos direitos
    do titular.
</p>

<h2>2. Encarregado de Proteção de Dados (DPO)</h2>
<p>
    <strong>Nome:</strong> <span class="placeholder-tag">[NOME COMPLETO]</span><br>
    <strong>E-mail:</strong> <a href="mailto:dpo@pilatesgestao.com.br">dpo@pilatesgestao.com.br</a><br>
    O DPO é o canal direto entre você, o PilatesGestão e a Autoridade Nacional de Proteção de Dados (ANPD).
</p>

<h2>3. Seus direitos (Art. 18 da LGPD)</h2>
<p>Como titular de dados, você tem direito a:</p>
<ul>
    <li><strong>Confirmação</strong> da existência de tratamento dos seus dados;</li>
    <li><strong>Acesso</strong> aos seus dados;</li>
    <li><strong>Correção</strong> de dados incompletos, inexatos ou desatualizados;</li>
    <li><strong>Anonimização, bloqueio ou eliminação</strong> de dados desnecessários, excessivos ou tratados em desconformidade;</li>
    <li><strong>Portabilidade</strong> dos dados a outro fornecedor de serviço ou produto;</li>
    <li><strong>Eliminação</strong> dos dados pessoais tratados com base em consentimento;</li>
    <li><strong>Informação</strong> sobre as entidades públicas e privadas com as quais o PilatesGestão compartilhou seus dados;</li>
    <li><strong>Informação</strong> sobre a possibilidade de não fornecer consentimento e suas consequências;</li>
    <li><strong>Revogação</strong> do consentimento a qualquer momento;</li>
    <li><strong>Revisão</strong> de decisões tomadas unicamente com base em tratamento automatizado que afetem seus interesses.</li>
</ul>

<h2>4. Como exercer seus direitos</h2>
<p>
    Envie um e-mail para <a href="mailto:dpo@pilatesgestao.com.br">dpo@pilatesgestao.com.br</a>
    contendo:
</p>
<ul>
    <li>Seu nome completo;</li>
    <li>E-mail cadastrado na Plataforma;</li>
    <li>Solicitação detalhada (qual direito deseja exercer);</li>
    <li>Documento de identificação para confirmação de identidade.</li>
</ul>
<p>
    Responderemos em até <strong>15 (quinze) dias</strong>, conforme exigido pela LGPD. Em casos
    de maior complexidade, este prazo poderá ser prorrogado mediante justificativa.
</p>

<h2>5. Bases legais que utilizamos</h2>
<p>
    O tratamento de dados pessoais pelo PilatesGestão é fundamentado nas seguintes bases legais
    previstas na LGPD (detalhamento na <a href="{{ url('/privacidade') }}">Política de Privacidade</a>):
</p>
<ul>
    <li><strong>Execução de contrato</strong> (art. 7º, V): prestação do serviço contratado;</li>
    <li><strong>Cumprimento de obrigação legal</strong> (art. 7º, II): emissão de notas fiscais, escrituração contábil;</li>
    <li><strong>Legítimo interesse</strong> (art. 7º, IX): segurança, prevenção a fraude, melhoria do produto;</li>
    <li><strong>Consentimento</strong> (art. 7º, I): marketing direto e cookies não essenciais.</li>
</ul>

<h2>6. Categorias de dados tratados</h2>
<ul>
    <li>Dados cadastrais e de identificação;</li>
    <li>Dados de contato (e-mail, telefone);</li>
    <li>Dados financeiros e de pagamento (processados pelos gateways);</li>
    <li>Dados de navegação e uso da Plataforma;</li>
    <li>Eventualmente, <strong>dados pessoais sensíveis</strong> relacionados à saúde do aluno (alergias, restrições, lesões), quando inseridos pelo Usuário Empresa para fins de orientação das aulas.</li>
</ul>
<p>
    O tratamento de dados sensíveis observa as regras específicas do art. 11 da LGPD, mediante
    consentimento ou outras hipóteses legais aplicáveis.
</p>

<h2>7. Incidentes de segurança</h2>
<p>
    Em caso de incidente de segurança que possa acarretar risco ou dano relevante aos titulares,
    o PilatesGestão comunicará a ANPD e os titulares afetados em prazo razoável, conforme o
    art. 48 da LGPD. A comunicação incluirá, no mínimo:
</p>
<ul>
    <li>Descrição da natureza dos dados afetados;</li>
    <li>Informações sobre os titulares envolvidos;</li>
    <li>Medidas técnicas e de segurança utilizadas;</li>
    <li>Riscos relacionados ao incidente;</li>
    <li>Medidas adotadas para reverter ou mitigar os efeitos do prejuízo.</li>
</ul>

<h2>8. Reclamações</h2>
<p>Caso considere que seus direitos não foram adequadamente atendidos, você pode:</p>
<ul>
    <li>Entrar em contato com nosso DPO (<a href="mailto:dpo@pilatesgestao.com.br">dpo@pilatesgestao.com.br</a>); ou</li>
    <li>Reclamar diretamente à Autoridade Nacional de Proteção de Dados (ANPD) através do site
        <a href="https://www.gov.br/anpd/" target="_blank" rel="noopener">gov.br/anpd</a>.</li>
</ul>

<h2>9. Documentos relacionados</h2>
<ul>
    <li><a href="{{ url('/privacidade') }}">Política de Privacidade completa</a></li>
    <li><a href="{{ url('/termos') }}">Termos de Uso</a></li>
</ul>

@endsection
