#!/usr/bin/env bash
# Déploiement EduMaison Curriculum depuis git (cf. MANIFESTE.md §2).
# Usage (sur le VPS) : bash docker/deploy.sh <sha-de-master>
# Sauvegarde vérifiée -> build isolé -> bascule -> contrôles ; rollback auto si échec.
set -Eeuo pipefail

SHA="${1:?usage: deploy.sh <sha>}"
APP=/opt/edumaison-curriculum
TS=$(date -u +%Y%m%dT%H%M%SZ)
BK=/home/david/edumaison-backups/deploy-$TS
BUILD=/tmp/edumaison-build-$TS
URL=https://edumaison.kamgangdavid.com
SWITCHED=0

log() { printf '\n== %s\n' "$*"; }

rollback() {
  log "ÉCHEC — rollback"
  if [ "$SWITCHED" = 1 ]; then
    cd "$APP"
    git reset -q --hard "$PREV_SHA"
    tar xzf "$BK/code.tgz" -C "$APP"
    rm -rf "$APP/vendor" && tar xzf "$BK/vendor.tgz" -C "$APP"
    if [ -d "$APP/public/react-before-deploy-$TS" ]; then
      rm -rf "$APP/public/react" && mv "$APP/public/react-before-deploy-$TS" "$APP/public/react"
    fi
    docker exec curriculum-app kill -USR2 1 || true
    echo "Code, vendor et bundle restaurés depuis $BK."
  fi
  rm -rf "$BUILD"
  echo "Sauvegarde conservée : $BK"
}
trap rollback ERR

cd "$APP"
git fetch -q origin
SHA=$(git rev-parse --verify "$SHA^{commit}")
git merge-base --is-ancestor "$SHA" origin/master || { echo "$SHA n'est pas dans origin/master"; exit 1; }
PREV_SHA=$(git rev-parse HEAD)

log "1. Sauvegarde -> $BK"
mkdir -p "$BK"
docker exec curriculum-postgres sh -c 'pg_dump -U "$POSTGRES_USER" -Fc "$POSTGRES_DB"' > "$BK/db.dump"
docker exec -i curriculum-postgres pg_restore -l < "$BK/db.dump" | grep -c ' TABLE DATA ' | xargs -I{} echo "dump : {} tables de données"
[ -s "$BK/db.dump" ]
tar czf "$BK/code.tgz" --exclude=./vendor --exclude=./node_modules --exclude='./public/react*' --exclude=./storage .
tar czf "$BK/vendor.tgz" vendor
sha256sum "$BK"/* > "$BK/SHA256SUMS"
echo "$PREV_SHA" > "$BK/PREVIOUS_HEAD"
ls -la "$BK"

log "2. Build React isolé ($SHA)"
mkdir -p "$BUILD"
git archive "$SHA" | tar x -C "$BUILD"
(cd "$BUILD" && npm ci --no-audit --no-fund --loglevel=error && npx vite build --logLevel warn)
[ -s "$BUILD/public/react/index.html" ] && [ -s "$BUILD/public/react/mama.html" ]

log "3. Bascule du code"
SWITCHED=1
git reset -q --hard "$SHA"
mv public/react "public/react-before-deploy-$TS"
cp -a "$BUILD/public/react" public/react
git checkout -q -- public/react/manifest.json 2>/dev/null || true
docker exec curriculum-app sh -c 'cd /var/www/html && composer install --no-dev --no-interaction --optimize-autoloader --quiet'
PENDING=$(docker exec curriculum-app php artisan migrate:status | grep -c Pending || true)
[ "$PENDING" = 0 ] || { echo "$PENDING migration(s) en attente : arrêt"; false; }
docker exec curriculum-app php artisan route:list --path=api > /dev/null
docker exec curriculum-app kill -USR2 1
docker exec curriculum-app php artisan queue:restart > /dev/null
sleep 3

log "4. Contrôles"
for p in /app /mama; do
  for i in 1 2 3; do
    code=$(curl -s -o /dev/null -w '%{http_code}' "$URL$p")
    [ "$code" = 200 ] || { echo "$p -> $code"; false; }
  done
  echo "$p : 200 x3"
done
code=$(curl -s -o /dev/null -w '%{http_code}' -H 'Accept: application/json' "$URL/api/family-auth/me")
[ "$code" = 401 ] || { echo "/api/family-auth/me -> $code (attendu 401)"; false; }
echo "API protégée : 401"
for a in $(grep -oE '/react/assets/[^"]+' public/react/index.html public/react/mama.html | cut -d: -f2 | sort -u); do
  code=$(curl -s -o /dev/null -w '%{http_code}' "$URL$a"); [ "$code" = 200 ] || { echo "$a -> $code"; false; }
done
echo "assets : 200"
docker ps --filter name=curriculum --format '{{.Names}} {{.Status}}'
docker exec curriculum-app composer audit --no-dev --locked 2>/dev/null | tail -1 || true

trap - ERR
rm -rf "$BUILD"
log "OK — déployé $SHA (précédent : $PREV_SHA). Sauvegarde : $BK"
