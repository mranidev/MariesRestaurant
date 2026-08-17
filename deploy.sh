#!/usr/bin/env bash
#
# Deploy the Maries TastyIgniter app to Wasmer Edge.
#
# Builds a clean staging copy of the project (excluding secrets and runtime
# junk) and runs `wasmer deploy`. Requires the Wasmer CLI (https://wasmer.io)
# and a logged-in account (`wasmer login`).
#
# Usage:  bash deploy.sh [--bump]
#   --bump   bump the package patch version (default; pass --no-bump to skip)

set -euo pipefail

ROOT="$(cd "$(dirname "$0")" && pwd)"
STAGE="${STAGE_DIR:-/tmp/maries-deploy}"

BUMP="--bump"
if [[ "${1:-}" == "--no-bump" ]]; then
  BUMP=""
fi

echo "==> Cleaning staging dir: $STAGE"
rm -rf "$STAGE"
mkdir -p "$STAGE"

echo "==> Copying project to staging (excluding secrets and runtime junk)"
# NOTE: exclude directory CONTENTS ('dir/*'), not the directories themselves —
# the app needs storage/framework/{views,sessions,cache}, storage/igniter/* and
# storage/logs to EXIST at boot (config/view.php resolves them with realpath()).
# IMPORTANT: the Wasmer package builder DROPS empty directories, so each dir that
# must exist in the sandbox keeps at least one file (.gitignore) via the
# --include rules below. Without this, storage/framework/views and
# storage/framework/sessions silently vanish from the deployed package and the
# app 500s with "Please provide a valid cache path".
# The webc upload is fast (~15s), and Wasmer's content-addressed storage skips
# re-uploading unchanged packages on re-deploys. (If the uplink degrades, the
# package is trimmed below — dev-only vendor packages are pruned with a proper
# `composer install --no-dev`, which REGENERATES the autoloader, so no dangling
# autoload refs. Earlier bare rsync-excludes of vendor/ dirs broke the app
# precisely because the autoloader still referenced the removed files.)
rsync -a \
  --exclude '.freebuff/' \
  --exclude '.env' \
  --exclude '.env.testing' \
  --exclude 'node_modules/' \
  --exclude 'deliverable-mobile-*' \
  --exclude 'client-submission-*' \
  --exclude 'screenshot-*' \
  --exclude '/Features/' \
  --exclude '58hpi8*.webp' \
  --exclude 'tastyigniter-redesign.png' \
  --exclude 'tastyigniter-setup-*.zip' \
  --exclude 'mobile-square-check.html' \
  --exclude '.git/' \
  --exclude 'storage/logs/*.log' \
  --exclude 'storage/framework/cache/data/*' \
  --exclude 'storage/framework/cache/*.php' \
  --include 'storage/framework/sessions/.gitignore' \
  --exclude 'storage/framework/sessions/*' \
  --include 'storage/framework/views/.gitignore' \
  --exclude 'storage/framework/views/*' \
  --exclude 'storage/igniter/cache/*' \
  --exclude 'storage/igniter/combiner/*' \
  "$ROOT/" "$STAGE/"

# Belt and braces: guarantee the runtime dirs exist (and are non-empty) in the
# package, regardless of what rsync or the source tree contain.
mkdir -p "$STAGE/storage/framework/views" "$STAGE/storage/framework/sessions" "$STAGE/storage/logs"
touch "$STAGE/storage/framework/views/.gitkeep" "$STAGE/storage/framework/sessions/.gitkeep"

echo "==> Pruning dev-only vendor packages (composer install --no-dev)"
# Requires the machine to have composer (it does). Uses the lockfile + local
# vendor/ so it only REMOVES dev packages and regenerates the autoloader — no
# new downloads unless a package is missing. Keeps the webc upload well under
# the presigned-URL window even on a slow uplink.
# NOTE: artisan package:discover (a post-autoload-dump hook) needs a bootable
# app, so the .env is copied in for the composer step and removed right after.
cp "$ROOT/.env" "$STAGE/.env"
cd "$STAGE"
composer install --no-dev --no-interaction --no-progress --prefer-dist 2>&1 | tail -3
rm "$STAGE/.env"
cd "$ROOT"

# Sanity checks
test ! -f "$STAGE/.env" || { echo "ERROR: .env must not be packaged"; exit 1; }
test -d "$STAGE/storage/framework/views" || { echo "ERROR: storage/framework/views missing"; exit 1; }
test -d "$STAGE/storage/framework/sessions" || { echo "ERROR: storage/framework/sessions missing"; exit 1; }

echo "==> Deploying to Wasmer Edge"
cd "$STAGE"
wasmer deploy --non-interactive --publish-package ${BUMP}

echo "==> Done. Live at https://maries-restaurant.wasmer.app"
# Connected to Wasmer Edge via GitHub (auto-deploy on push to main)
# Auto-deploy test — commit timestamp 2026-08-17T12:46:11Z
