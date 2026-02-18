#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${1:-}"
ARTIFACT="${2:-}"
KEEP_RELEASES="${3:-5}"

if [[ -z "$APP_DIR" || -z "$ARTIFACT" ]]; then
  echo "Usage: $0 /path/to/app /path/to/release.tgz [keep_releases]"
  exit 1
fi

RELEASES_DIR="$APP_DIR/releases"
SHARED_DIR="$APP_DIR/shared"
CURRENT_LINK="$APP_DIR/current"

mkdir -p "$RELEASES_DIR" "$SHARED_DIR" "$SHARED_DIR/storage" "$SHARED_DIR/bootstrap/cache"

NEW_RELEASE="$RELEASES_DIR/$(date +%Y%m%d%H%M%S)"
mkdir -p "$NEW_RELEASE"

tar -xzf "$ARTIFACT" -C "$NEW_RELEASE"

if [[ -f "$SHARED_DIR/.env" ]]; then
  ln -s "$SHARED_DIR/.env" "$NEW_RELEASE/.env"
fi

rm -rf "$NEW_RELEASE/storage"
ln -s "$SHARED_DIR/storage" "$NEW_RELEASE/storage"

rm -rf "$NEW_RELEASE/bootstrap/cache"
mkdir -p "$SHARED_DIR/bootstrap/cache"
ln -s "$SHARED_DIR/bootstrap/cache" "$NEW_RELEASE/bootstrap/cache"

chmod -R ug+rw "$SHARED_DIR/storage" "$SHARED_DIR/bootstrap/cache"

ln -sfn "$NEW_RELEASE" "$CURRENT_LINK"

if [[ -x "$(command -v php)" && -f "$NEW_RELEASE/artisan" ]]; then
  php "$NEW_RELEASE/artisan" config:clear || true
  php "$NEW_RELEASE/artisan" route:clear || true
  php "$NEW_RELEASE/artisan" view:clear || true
fi

if ls -1dt "$RELEASES_DIR"/* >/dev/null 2>&1; then
  ls -1dt "$RELEASES_DIR"/* | tail -n +"$((KEEP_RELEASES + 1))" | xargs -r rm -rf
fi

rm -f "$ARTIFACT"
