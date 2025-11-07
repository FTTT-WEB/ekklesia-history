#!/bin/bash
# Quick fix: if the hover image frontpage_r2_c2_f2.jpg is missing, copy the existing non-hover image
set -euo pipefail
ROOT_DIR=$(cd "$(dirname "$0")/.." && pwd)
IMG_DIR="$ROOT_DIR/image/frontpage"
SRC="$IMG_DIR/frontpage_r2_c2.jpg"
DST="$IMG_DIR/frontpage_r2_c2_f2.jpg"

if [ -f "$DST" ]; then
  echo "Hover image already present: $DST"
  exit 0
fi

if [ -f "$SRC" ]; then
  cp "$SRC" "$DST"
  echo "Copied $SRC -> $DST"
else
  echo "Source image not found: $SRC" >&2
  exit 2
fi
