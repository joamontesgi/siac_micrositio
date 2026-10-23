#!/usr/bin/env python3
"""Regenera storage/app/data/indicators.json desde el Excel en la raíz del proyecto."""

from __future__ import annotations

import json
from pathlib import Path

import openpyxl

ROOT = Path(__file__).resolve().parents[1]
XLSX = ROOT / "indicadores_UNAL_lineas_estrategicas.xlsx"
OUT = ROOT / "storage" / "app" / "data" / "indicators.json"


def main() -> None:
    if not XLSX.is_file():
        raise SystemExit(f"No se encontró el archivo: {XLSX}")

    wb = openpyxl.load_workbook(XLSX, read_only=True, data_only=True)
    ws = wb["Indicadores"]
    headers: list[str] = []
    indicators: list[dict] = []

    for i, row in enumerate(ws.iter_rows(values_only=True)):
        if i == 0:
            headers = [str(h).strip() if h is not None else "" for h in row]
            continue
        if not any(row):
            continue
        rec: dict = {}
        for h, v in zip(headers, row):
            if not h:
                continue
            if hasattr(v, "isoformat"):
                rec[h] = v.isoformat()
            else:
                rec[h] = v
        if rec.get("ID"):
            indicators.append(rec)

    resumen: list = []
    for row in wb["Resumen"].iter_rows(values_only=True):
        if any(row):
            resumen.append(list(row))

    wb.close()
    OUT.parent.mkdir(parents=True, exist_ok=True)
    payload = {
        "indicators": indicators,
        "resumen": resumen,
        "source": XLSX.name,
    }
    OUT.write_text(json.dumps(payload, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"Exportados {len(indicators)} indicadores -> {OUT}")


if __name__ == "__main__":
    main()
