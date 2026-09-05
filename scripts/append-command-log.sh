#!/usr/bin/env bash
# Append a dated command entry to docs/COMMANDS.md
# Usage: ./scripts/append-command-log.sh "feature/01-scaffold-laravel" "php artisan migrate" "Ran default migrations"
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
LOG="$ROOT/docs/COMMANDS.md"
BRANCH="${1:-unknown}"
CMD="${2:-}"
NOTE="${3:-}"
TS="$(date -u +"%Y-%m-%dT%H:%M:%SZ")"

if [[ -z "$CMD" ]]; then
  echo "Usage: $0 <branch> <command> [note]" >&2
  exit 1
fi

{
  echo ""
  echo "### $TS ($BRANCH)"
  echo ""
  echo '```bash'
  echo "$CMD"
  echo '```'
  if [[ -n "$NOTE" ]]; then
    echo ""
    echo "$NOTE"
  fi
} >> "$LOG"

echo "Logged to docs/COMMANDS.md"
