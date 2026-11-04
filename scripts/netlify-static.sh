#!/usr/bin/env bash
set -euo pipefail
python -m pip install -r scripts/requirements-static.txt
python scripts/build_site_content.py
python scripts/import_indicators_from_excel.py
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
php scripts/export_static.php
