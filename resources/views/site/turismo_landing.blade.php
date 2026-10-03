<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Teste Grátis — Sistema de Gestão para Passeios e Turismo</title>
  <meta name="description" content="Gerencie seus passeios e turismo sem complicação. Agenda de saídas, reservas, cobranças e clientes em um só lugar. Teste grátis por 14 dias.">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --blue-dark:   #115e59;
      --blue-mid:    #0d9488;
      --blue-light:  #ccfbf1;
      --blue-pale:   #f0fdfa;
      --cyan:        #f59e0b;
      --white:       #ffffff;
      --gray-text:   #4a5568;
      --gray-subtle: #e2e8f0;
      --radius:      14px;
      --shadow:      0 4px 24px rgba(13, 148, 136, 0.14);
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--blue-pale);
      color: var(--gray-text);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
    }

    /* ── Onda decorativa no topo ── */
    .top-strip {
      background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 100%);
      color: var(--white);
      text-align: center;
      font-size: 0.78rem;
      font-weight: 500;
      padding: 10px 16px;
      letter-spacing: 0.02em;
      position: relative;
    }

    /* ── Hero ── */
    .hero {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 52px 20px 44px;
      position: relative;
    }

    /* ondas SVG de fundo */
    .hero::before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 120px;
      background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 120'%3E%3Cpath fill='%230e86c8' fill-opacity='0.07' d='M0,64L60,58.7C120,53,240,43,360,48C480,53,600,75,720,80C840,85,960,75,1080,64C1200,53,1320,43,1380,37.3L1440,32L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z'/%3E%3C/svg%3E") no-repeat bottom;
      background-size: cover;
      pointer-events: none;
    }

    .hero-inner {
      max-width: 860px;
      width: 100%;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 48px;
      align-items: center;
      position: relative;
      z-index: 1;
    }

    .hero-copy { display: flex; flex-direction: column; gap: 20px; }

    /* ── Badge ── */
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--blue-light);
      color: var(--blue-dark);
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      padding: 6px 14px;
      border-radius: 100px;
      width: fit-content;
    }

    .badge::before {
      content: '';
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--blue-mid);
      animation: pulse 1.8s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50%       { opacity: 0.5; transform: scale(1.4); }
    }

    h1 {
      font-size: clamp(1.85rem, 4vw, 2.6rem);
      font-weight: 800;
      color: #1a202c;
      line-height: 1.2;
    }

    h1 span { color: var(--blue-mid); }

    .subtitle {
      font-size: 1rem;
      line-height: 1.65;
      color: var(--gray-text);
    }

    /* ── Benefícios ── */
    .benefits { display: flex; flex-direction: column; gap: 12px; }

    .benefit-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }

    .benefit-icon {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      background: var(--blue-light);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-size: 1.1rem;
    }

    .benefit-text strong {
      display: block;
      font-size: 0.9rem;
      font-weight: 700;
      color: #1a202c;
    }

    .benefit-text span {
      font-size: 0.82rem;
      color: var(--gray-text);
    }

    /* ── Card do formulário ── */
    .form-card {
      background: var(--white);
      border-radius: var(--radius);
      padding: 36px 32px;
      box-shadow: var(--shadow);
      border: 1px solid var(--gray-subtle);
    }

    .form-card h2 {
      font-size: 1.2rem;
      font-weight: 700;
      color: #1a202c;
      margin-bottom: 6px;
    }

    .form-card > div > p {
      font-size: 0.83rem;
      color: var(--gray-text);
      margin-bottom: 24px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 14px;
      margin-bottom: 20px;
    }

    .input-wrap { position: relative; }

    .input-wrap svg {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #a0aec0;
      pointer-events: none;
    }

    input {
      width: 100%;
      padding: 13px 14px 13px 42px;
      border: 1.5px solid var(--gray-subtle);
      border-radius: 10px;
      font-family: 'Poppins', sans-serif;
      font-size: 0.9rem;
      color: #1a202c;
      background: #f8fafc;
      transition: border-color 0.2s, box-shadow 0.2s;
      outline: none;
    }

    input::placeholder { color: #a0aec0; }

    input:focus {
      border-color: var(--blue-mid);
      box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
      background: var(--white);
    }

    /* ── Botão CTA ── */
    .btn-cta {
      width: 100%;
      padding: 15px;
      background: linear-gradient(135deg, var(--blue-mid), var(--blue-dark));
      color: var(--white);
      font-family: 'Poppins', sans-serif;
      font-size: 1rem;
      font-weight: 700;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-cta:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(13, 148, 136, 0.38);
    }

    .btn-cta:active { transform: translateY(0); }

    .btn-cta.loading { opacity: 0.75; pointer-events: none; }

    .btn-cta .spinner {
      width: 18px;
      height: 18px;
      border: 2.5px solid rgba(255,255,255,0.4);
      border-top-color: white;
      border-radius: 50%;
      animation: spin 0.7s linear infinite;
      display: none;
    }

    .btn-cta.loading .spinner { display: block; }
    .btn-cta.loading .btn-label { display: none; }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── Nota de privacidade ── */
    .privacy-note {
      text-align: center;
      font-size: 0.75rem;
      color: #a0aec0;
      margin-top: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
    }

    /* ── Estado de sucesso ── */
    .success-state {
      display: none;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 14px;
      padding: 12px 0;
    }

    .success-icon {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      background: var(--blue-light);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
    }

    .success-state h3 {
      font-size: 1.15rem;
      font-weight: 700;
      color: #1a202c;
    }

    .success-state p {
      font-size: 0.85rem;
      color: var(--gray-text);
    }

    /* ── Rodapé ── */
    footer {
      text-align: center;
      padding: 20px 16px 28px;
      font-size: 0.78rem;
      color: #a0aec0;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    footer svg { color: var(--blue-mid); flex-shrink: 0; }

    /* ── Responsivo ── */
    @media (max-width: 680px) {
      .hero { padding: 36px 16px 28px; }

      .hero-inner {
        grid-template-columns: 1fr;
        gap: 32px;
      }

      .form-card { order: -1; padding: 28px 22px; }

      .form-card h2 { font-size: 1.1rem; }
    }
  </style>
</head>
<body>

  <div class="top-strip">
    🌴 Oferta por tempo limitado — 14 dias totalmente grátis, sem cartão de crédito
    &nbsp;·&nbsp; <a href="https://admin.rjpasseios.com.br" style="color:#fff;text-decoration:underline;font-weight:600;">Já sou cliente: entrar</a>
  </div>

  <main class="hero">
    <div class="hero-inner">

      <!-- Copy -->
      <div class="hero-copy">
        <span class="badge">Grátis por 14 dias</span>

        <h1>
          Gerencie seus passeios e turismo
          <span>sem complicação</span>
        </h1>

        <p class="subtitle">
          Agenda de passeios, reservas, cobranças automáticas e controle de clientes — tudo em um só lugar.
          Configure em minutos e foque no que você faz de melhor: entregar experiências incríveis.
        </p>

        <div class="benefits">
          <div class="benefit-item">
            <div class="benefit-icon">🗺️</div>
            <div class="benefit-text">
              <strong>Agenda de passeios e saídas</strong>
              <span>Clientes reservam sozinhos por data, horário e vagas disponíveis.</span>
            </div>
          </div>

          <div class="benefit-item">
            <div class="benefit-icon">💳</div>
            <div class="benefit-text">
              <strong>Cobranças no automático</strong>
              <span>PIX, boleto e cartão — sem planilha, sem esquecimento.</span>
            </div>
          </div>

          <div class="benefit-item">
            <div class="benefit-icon">🤖</div>
            <div class="benefit-text">
              <strong>Atendimento pelo WhatsApp</strong>
              <span>Bot responde dúvidas e reservas 24 horas por dia.</span>
            </div>
          </div>

          <div class="benefit-item">
            <div class="benefit-icon">📊</div>
            <div class="benefit-text">
              <strong>Controle financeiro completo</strong>
              <span>Veja receitas, despesas e inadimplentes em tempo real.</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Chamada pro funil -->
      <div class="form-card">
        <h2>Descubra em 1 minuto se serve pro seu negócio 🌴</h2>
        <p>Responda 8 perguntas rápidas e já abra o seu teste grátis de 14 dias.</p>
        <a href="/comecar" class="btn-cta" id="btn-comecar" style="display:flex;align-items:center;justify-content:center;text-decoration:none;">
          <span class="btn-label">Quero começar agora →</span>
        </a>
        <p class="privacy-note">Sem cartão de crédito. Cancele quando quiser.</p>
      </div>

    </div>
  </main>

  <footer>
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
    </svg>
    Sem cartão de crédito. Cancele quando quiser.
  </footer>

  <script>
    // Leva junto a origem do anúncio (utm_source, utm_medium, utm_campaign) pro funil
    document.getElementById('btn-comecar').href = '/comecar' + window.location.search;
  </script>
  @include('validador._pixel')

</body>
</html>
