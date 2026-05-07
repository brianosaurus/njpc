#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

SITE_URL="http://localhost:8080"
SITE_TITLE="NJPC"
ADMIN_USER="admin"
ADMIN_PASS="admin"
ADMIN_EMAIL="admin@example.com"

wpcli() { docker compose run --rm wpcli "$@"; }

echo "==> Booting containers"
docker compose up -d db wordpress

echo "==> Waiting for WordPress to respond"
for i in $(seq 1 60); do
  if curl -fsS -o /dev/null "$SITE_URL"; then break; fi
  sleep 2
done

echo "==> Installing core (idempotent)"
if ! wpcli core is-installed >/dev/null 2>&1; then
  wpcli core install \
    --url="$SITE_URL" \
    --title="$SITE_TITLE" \
    --admin_user="$ADMIN_USER" \
    --admin_password="$ADMIN_PASS" \
    --admin_email="$ADMIN_EMAIL" \
    --skip-email
else
  echo "Core already installed."
fi

echo "==> Activating theme"
wpcli theme activate marx-clone

echo "==> Setting permalinks"
wpcli rewrite structure '/%postname%/' --hard

echo "==> Creating Home page if missing"
HOME_ID=$(wpcli post list --post_type=page --name=home --field=ID 2>/dev/null | tr -d '\r' | head -n1 || true)
if [ -z "${HOME_ID:-}" ]; then
  HOME_ID=$(wpcli post create --post_type=page --post_title='Home' --post_name=home --post_status=publish --porcelain | tr -d '\r')
fi
wpcli option update show_on_front page
wpcli option update page_on_front "$HOME_ID"

echo "==> Seeding sample CPT content (idempotent)"
wpcli eval-file /var/www/html/wp-content/themes/marx-clone/inc/seed.php

echo
echo "Done. Site:    $SITE_URL"
echo "      Admin:   $SITE_URL/wp-admin   (user: $ADMIN_USER  pass: $ADMIN_PASS)"
echo "      Edit copy: Appearance → Customize"
echo "      Edit lists: Projects, Perspectives, Service Categories in admin sidebar"
