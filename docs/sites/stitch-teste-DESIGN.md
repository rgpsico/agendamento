# Design System: Escola de Surf — modelo "Stitch Teste"

Gerado seguindo a skill `taste-design` do google-labs-code/stitch-skills (método DESIGN.md),
sem o servidor MCP do Stitch. View: `resources/views/site/stitch_teste.blade.php`.

## 1. Visual Theme & Atmosphere
Editorial de praia ao amanhecer: areia clara, tinta de carvão e um único azul-oceano.
Densidade 4 (equilibrada, com ar), variância 7 (hero assimétrico, zig-zag nos serviços),
movimento 5 (revelações suaves em cascata, só transform/opacity).

## 2. Color Palette & Roles
- **Areia Clara** (#F4F1EA) — fundo principal
- **Superfície** (#FBFAF6) — blocos e cartões
- **Carvão** (#1C1B19) — texto principal (nunca #000)
- **Pedra** (#6B675F) — texto secundário, metadados
- **Linha** (#E2DDD2) — bordas de 1px
- **Oceano** (#1F6F78) — único destaque: CTA, links, foco. Pode ser trocado pela cor primária do site.

## 3. Typography Rules
- **Display:** Outfit 600, tracking -0.03em, tamanho com clamp()
- **Destaque editorial:** Fraunces itálico, só em uma palavra do título
- **Body:** Outfit 400, entrelinha 1.65, máx. 62ch
- **Mono:** JetBrains Mono — preços, duração, nível
- **Proibido:** Inter, serifas genéricas

## 4. Component Stylings
- **Botões:** chapados, sem brilho; -1px no :active; um CTA primário por seção
- **Serviços:** linhas em zig-zag (imagem/texto alternando), não 3 cartões iguais
- **Metadados:** chips em mono com borda fina
- **Formulário:** rótulo acima, erro abaixo, foco em Oceano

## 5. Layout Principles
Grid CSS, largura máx. 1240px. Hero dividido (texto à esquerda, imagem à direita) com uma
miniatura inline dentro do título. Abaixo de 768px tudo em coluna única; alvos de toque ≥ 44px.
Seções com `min-height: 100dvh` só no hero.

## 6. Motion & Interaction
Revelação em cascata com IntersectionObserver (translateY 18px → 0, opacity), curva
cubic-bezier(.2,.8,.2,1). Respeita `prefers-reduced-motion`.

## 7. Anti-Patterns (Banned)
Sem emojis, sem números inventados (só dados reais da empresa), sem "Eleve / Sem esforço",
sem "role para baixo", sem setas pulando, sem gradiente em título, sem preto puro.
