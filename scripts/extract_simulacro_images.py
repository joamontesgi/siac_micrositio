#!/usr/bin/env python3
"""Renderiza diapositivas del PDF del simulacro (6 oct) y genera el manifiesto de imágenes."""

from __future__ import annotations

import json
import re
from pathlib import Path

import fitz

ROOT = Path(__file__).resolve().parents[1]
PDF = ROOT / "public" / "fuentes-siac" / "SIAC_Reunion_6_octubre_Simulacro_V2.pdf"
OUT_DIR = ROOT / "public" / "images" / "simulacro"
MANIFEST = ROOT / "content" / "simulacro_images.json"

# Diapositiva (1-based) → slugs de páginas del sitio
SLIDE_PAGES: dict[int, list[str]] = {
    1: ["presentacion-simulacro"],
    2: ["importancia-siac", "presentacion-simulacro"],
    3: ["concepto-calidad", "presentacion-simulacro"],
    4: ["modelo-calidad", "presentacion-simulacro"],
    5: ["que-es-siac", "presentacion-simulacro"],
    6: ["acuerdo-normativo-siac", "presentacion-simulacro"],
    7: ["acuerdo-normativo-siac", "presentacion-simulacro"],
    8: ["acuerdo-normativo-siac", "presentacion-simulacro"],
    9: ["arquitectura-siac", "presentacion-simulacro"],
    10: ["arquitectura-siac", "presentacion-simulacro"],
    11: ["arquitectura-siac", "presentacion-simulacro"],
    12: ["arquitectura-siac", "presentacion-simulacro"],
    13: ["retos-bpun", "presentacion-simulacro"],
    14: ["contacto", "presentacion-simulacro"],
}


def slide_title(page: fitz.Page) -> str:
    text = page.get_text().strip()
    if not text:
        return "Diapositiva"
    lines = [ln.strip() for ln in text.splitlines() if ln.strip()]
    for line in lines[:6]:
        if line.lower().startswith("2024"):
            continue
        if "@" in line:
            continue
        cleaned = re.sub(r"\s+", " ", line)
        if len(cleaned) > 8:
            return cleaned[:120]
    return lines[0][:120] if lines else "Diapositiva"


def main() -> None:
    if not PDF.is_file():
        raise SystemExit(f"No se encontró el PDF: {PDF}")

    OUT_DIR.mkdir(parents=True, exist_ok=True)
    doc = fitz.open(PDF)
    slides: list[dict] = []

    for index in range(doc.page_count):
        number = index + 1
        page = doc[index]
        filename = f"slide-{number:02d}.png"
        out_path = OUT_DIR / filename
        pix = page.get_pixmap(matrix=fitz.Matrix(2, 2), alpha=False)
        pix.save(out_path.as_posix())

        rel = f"images/simulacro/{filename}"
        slides.append(
            {
                "number": number,
                "file": rel,
                "title": slide_title(page),
                "pages": SLIDE_PAGES.get(number, ["presentacion-simulacro"]),
            }
        )

    doc.close()

    payload = {
        "source_pdf": "public/fuentes-siac/SIAC_Reunion_6_octubre_Simulacro_V2.pdf",
        "slide_count": len(slides),
        "slides": slides,
    }
    MANIFEST.parent.mkdir(parents=True, exist_ok=True)
    MANIFEST.write_text(json.dumps(payload, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"Generadas {len(slides)} imágenes en {OUT_DIR}")
    print(f"Manifiesto: {MANIFEST}")


if __name__ == "__main__":
    main()
