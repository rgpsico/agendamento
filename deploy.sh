#!/usr/bin/env bash
# Deploy do StudioGest/agendamento na VPS.
# Chamado pelo GitHub Actions (.github/workflows/deploy.yml) a cada push na main,
# mas também pode ser rodado na mão:  bash deploy.sh
#
# Detecta sozinho se o projeto roda em Docker (container agendamento_app ativo)
# ou direto no servidor.

set -euo pipefail

BRANCH="${DEPLOY_BRANCH:-main}"
APP_CONTAINER="${APP_CONTAINER:-agendamento_app}"
WORKER_CONTAINER="${WORKER_CONTAINER:-agendamento_worker}"

cd "$(dirname "$0")"
echo ">> Deploy em $(pwd) — branch $BRANCH"

# 1. Código novo (falha se houver alteração local conflitante, de propósito)
git fetch origin "$BRANCH"
git checkout "$BRANCH"
git pull --ff-only origin "$BRANCH"

# 2. Escolhe como rodar PHP
if command -v docker >/dev/null 2>&1 && docker ps --format '{{.Names}}' | grep -qx "$APP_CONTAINER"; then
  echo ">> Modo Docker ($APP_CONTAINER)"
  run_php() { docker exec "$APP_CONTAINER" "$@"; }
  USE_DOCKER=1
else
  echo ">> Modo direto no servidor"
  run_php() { "$@"; }
  USE_DOCKER=0
fi

# 3. Dependências PHP
run_php composer install --no-dev --optimize-autoloader --no-interaction

# 4. Front (Vite/Vue) — public/build não vai pro git
if [ "$USE_DOCKER" = "1" ]; then
  docker run --rm -v "$(pwd)":/app -w /app node:20-alpine sh -c "npm ci && npm run build"
else
  npm ci && npm run build
fi

# 5. Banco e caches
run_php php artisan migrate --force
run_php php artisan optimize:clear
run_php php artisan optimize

# 6. Reinicia a fila para pegar o código novo
run_php php artisan queue:restart || true
if [ "$USE_DOCKER" = "1" ] && docker ps --format '{{.Names}}' | grep -qx "$WORKER_CONTAINER"; then
  docker restart "$WORKER_CONTAINER" >/dev/null
fi

echo ">> Deploy concluído: $(git log -1 --format='%h %s')"
