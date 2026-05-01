@extends('layouts.legal')

@section('title', 'Termos de Uso — PilatesGestão')
@section('page_title', 'Termos de Uso')
@section('page_subtitle', 'Condições gerais para uso da plataforma PilatesGestão')
@section('nav_termos', 'active')

@section('content')

<div class="update-info">
    <strong>Última atualização:</strong> <span class="placeholder-tag">[INSERIR DATA]</span><br>
    Estes Termos podem ser revisados periodicamente. Avisaremos sobre mudanças relevantes com 30 dias de antecedência.
</div>

<p>
    Bem-vindo ao <strong>PilatesGestão</strong>, plataforma operada por
    <span class="placeholder-tag">[RAZÃO SOCIAL]</span>, inscrita no CNPJ sob nº
    <span class="placeholder-tag">[CNPJ]</span>, com sede em
    <span class="placeholder-tag">[ENDEREÇO COMPLETO]</span>
    ("PilatesGestão", "nós" ou "nossa").
</p>

<p>
    Estes Termos de Uso ("Termos") regulam o acesso e a utilização da plataforma SaaS PilatesGestão, disponível em
    <a href="https://pilatesgestao.com.br">pilatesgestao.com.br</a> ("Plataforma"). Ao se cadastrar ou utilizar
    a Plataforma, você ("Usuário") declara ter lido, compreendido e aceitado integralmente estes Termos.
</p>

<h2>1. Definições</h2>
<ul>
    <li><strong>Plataforma:</strong> software de gestão para estúdios de pilates oferecido na modalidade SaaS.</li>
    <li><strong>Usuário Empresa:</strong> pessoa jurídica ou física que contrata um plano para gerir seu estúdio.</li>
    <li><strong>Usuário Aluno:</strong> pessoa cadastrada pelo Usuário Empresa para acesso à área do aluno.</li>
    <li><strong>Conteúdo do Cliente:</strong> dados, informações e materiais inseridos pelo Usuário Empresa na Plataforma.</li>
</ul>

<h2>2. Objeto</h2>
<p>
    A Plataforma oferece ferramentas para gestão de agenda, alunos, instrutores, cobrança, financeiro e site
    institucional para estúdios de pilates, conforme as funcionalidades disponíveis em cada plano contratado e
    detalhadas em <a href="{{ url('/#planos') }}">pilatesgestao.com.br/#planos</a>.
</p>

<h2>3. Cadastro e Conta</h2>
<p>3.1. Para usar a Plataforma, o Usuário deve criar uma conta fornecendo informações verdadeiras, completas e atualizadas.</p>
<p>3.2. O Usuário é o único responsável pela confidencialidade de sua senha e por todas as atividades realizadas em sua conta.</p>
<p>3.3. O Usuário deve notificar imediatamente o PilatesGestão sobre qualquer uso não autorizado de sua conta.</p>
<p>3.4. O Usuário Empresa é responsável pela veracidade e legalidade dos dados de seus alunos cadastrados na Plataforma e pela obtenção do consentimento necessário, quando aplicável.</p>

<h2>4. Planos, Preços e Pagamentos</h2>
<p>4.1. Os planos vigentes e seus respectivos valores estão descritos em <a href="{{ url('/#planos') }}">pilatesgestao.com.br/#planos</a>. Os valores podem ser reajustados anualmente conforme variação do IPCA ou outro índice oficial.</p>
<p>4.2. As cobranças dos planos pagos são mensais e recorrentes, processadas por gateways terceirizados (Stripe e/ou Asaas).</p>
<p>4.3. O atraso superior a 7 (sete) dias no pagamento poderá acarretar a suspensão do acesso à Plataforma até a regularização.</p>
<p>4.4. <strong>Reembolso:</strong> o Usuário pode cancelar a qualquer momento sem multa. Solicitações de reembolso feitas em até 7 (sete) dias após a renovação serão atendidas de forma proporcional aos dias não utilizados.</p>

<h2>5. Período de Teste Gratuito</h2>
<p>5.1. O plano Profissional pode ser testado por 14 (quatorze) dias sem cobrança e sem necessidade de cartão de crédito.</p>
<p>5.2. Ao final do período, sem ativação paga, a conta será migrada automaticamente para o plano Básico (gratuito, com limitações) ou desativada.</p>

<h2>6. Uso Permitido e Vedações</h2>
<p>O Usuário concorda em <strong>não</strong>:</p>
<ul>
    <li>Revender, sublicenciar ou compartilhar acesso à Plataforma com terceiros não autorizados;</li>
    <li>Utilizar a Plataforma para envio de spam ou comunicação não solicitada;</li>
    <li>Tentar burlar limites técnicos, realizar engenharia reversa ou acessar áreas restritas;</li>
    <li>Inserir conteúdo ilegal, ofensivo, que viole direitos de terceiros ou contrarie a legislação aplicável;</li>
    <li>Utilizar a Plataforma para finalidade diversa daquela para a qual foi concebida.</li>
</ul>

<h2>7. Propriedade Intelectual</h2>
<p>7.1. A Plataforma, incluindo seu código-fonte, design, marcas, logotipos e demais elementos de identidade visual, é de propriedade exclusiva do PilatesGestão, protegida pela legislação de propriedade intelectual.</p>
<p>7.2. O Conteúdo do Cliente permanece de propriedade do Usuário Empresa, que concede ao PilatesGestão licença não-exclusiva, gratuita e revogável para hospedar, processar e exibir tais dados estritamente para a prestação do serviço contratado.</p>

<h2>8. Disponibilidade e Manutenção</h2>
<p>8.1. Empregamos esforços razoáveis para manter a Plataforma disponível 24 horas por dia, 7 dias por semana.</p>
<p>8.2. <strong>Não há SLA contratual de uptime garantido</strong> para os planos Básico e Profissional. O plano Enterprise pode incluir SLA específico mediante contrato individual.</p>
<p>8.3. Manutenções programadas serão comunicadas com antecedência sempre que possível, preferencialmente em horários de baixo uso.</p>
<p>8.4. Não nos responsabilizamos por indisponibilidades causadas por terceiros (provedores de hospedagem, gateways de pagamento, força maior, ataques cibernéticos).</p>

<h2>9. Limitação de Responsabilidade</h2>
<p>9.1. A Plataforma é fornecida no estado em que se encontra ("as is"). Não garantimos que estará livre de erros, bugs ou interrupções.</p>
<p>9.2. Não nos responsabilizamos por danos indiretos, lucros cessantes, perda de oportunidade de negócio ou perdas decorrentes do uso de serviços de terceiros integrados à Plataforma (gateways de pagamento, provedores de e-mail e SMS, serviços de IA).</p>
<p>9.3. Em qualquer hipótese, a responsabilidade total do PilatesGestão fica limitada ao valor efetivamente pago pelo Usuário nos 12 (doze) meses anteriores ao evento que originou a reclamação.</p>

<h2>10. Cancelamento e Exclusão de Dados</h2>
<p>10.1. O Usuário pode cancelar a conta a qualquer momento via Plataforma ou mediante solicitação por e-mail.</p>
<p>10.2. Após o cancelamento, os dados ficam disponíveis para exportação por 30 (trinta) dias.</p>
<p>10.3. Encerrado esse prazo, os dados serão excluídos definitivamente, salvo obrigação legal de retenção (notas fiscais, registros contábeis).</p>
<p>10.4. O PilatesGestão pode encerrar a conta do Usuário, mediante notificação prévia, em caso de violação grave destes Termos.</p>

<h2>11. Alterações dos Termos</h2>
<p>Reservamo-nos o direito de modificar estes Termos a qualquer momento. Mudanças relevantes serão comunicadas com 30 (trinta) dias de antecedência por e-mail ou aviso na Plataforma. O uso continuado após a vigência das alterações implica aceitação tácita.</p>

<h2>12. Lei Aplicável e Foro</h2>
<p>
    Estes Termos são regidos pela legislação brasileira. Fica eleito o foro da comarca de
    <span class="placeholder-tag">[CIDADE/UF]</span>, com renúncia expressa a qualquer outro,
    por mais privilegiado que seja, para dirimir eventuais controvérsias.
</p>

<h2>13. Contato</h2>
<p>
    Dúvidas sobre estes Termos: <a href="mailto:contato@pilatesgestao.com.br">contato@pilatesgestao.com.br</a>
</p>

@endsection
