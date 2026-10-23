#!/usr/bin/env python3
"""Genera storage/app/data/site_content.json solo desde el PDF del 6-oct y el Excel."""

from __future__ import annotations

import json
from pathlib import Path

import openpyxl

ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / "storage" / "app" / "data" / "site_content.json"
XLSX = ROOT / "indicadores_UNAL_lineas_estrategicas.xlsx"
AL_DETALLE = ROOT / "content" / "siac_al_detalle.json"
SIMULACRO_IMAGES = ROOT / "content" / "simulacro_images.json"

# Texto extraído del PDF «SIAC_Reunión 6 de octubre _ Simulacro_V.2.pptx.pdf»
PDF = {
    "importancia-siac": {
        "title": "¿Por qué es importante el SIAC?",
        "breadcrumb": ["Presentación SIAC", "¿Por qué es importante el SIAC?"],
        "intro": None,
        "sections": [],
    },
    "concepto-calidad": {
        "title": "Concepto de calidad",
        "breadcrumb": ["Presentación SIAC", "Concepto de calidad"],
        "intro": (
            "La calidad en la Universidad Nacional de Colombia se entiende como la "
            "capacidad institucional, construida colectiva y participativamente, de "
            "garantizar coherencia entre su identidad y mandato público como universidad "
            "autónoma y pública de carácter nacional, la dignidad e inclusión de su "
            "comunidad universitaria, el rigor y la pertinencia social de sus funciones "
            "misionales de formación, investigación y extensión, y la solidez de la "
            "gobernanza y la gestión institucional que las sostienen."
        ),
        "sections": [
            {
                "heading": None,
                "body": (
                    "Es un horizonte en permanente construcción, que se fortalece mediante la "
                    "evaluación, el aprendizaje y el mejoramiento continuo, y cuyo propósito es "
                    "cumplir la misión y el proyecto educativo de la Universidad, así como la "
                    "responsabilidad que la sociedad colombiana le ha confiado."
                ),
            }
        ],
    },
    "modelo-calidad": {
        "title": "Modelo de calidad",
        "breadcrumb": ["Presentación SIAC", "Modelo de calidad"],
        "intro": "complementan y fortalecen mutuamente",
        "sections": [],
    },
    "que-es-siac": {
        "title": "¿Qué es el SIAC – UNAL?",
        "breadcrumb": ["Presentación SIAC", "¿Qué es el SIAC – UNAL?"],
        "intro": (
            "El SIAC es un sistema institucional integrado que articula las capacidades, "
            "actores, procesos, instancias e instrumentos de la Universidad para evaluar, "
            "comprender, transformar y mejorar continuamente la calidad de sus funciones "
            "misionales, la vida universitaria y la gobernanza y gestión institucional."
        ),
        "sections": [
            {
                "heading": None,
                "body": (
                    "Su arquitectura organiza y relaciona estos elementos para favorecer la "
                    "autorregulación, el aprendizaje institucional, la toma de decisiones "
                    "informadas y el mejoramiento continuo."
                ),
            }
        ],
    },
    "acuerdo-normativo-siac": {
        "title": "Acuerdo normativo SIAC",
        "breadcrumb": ["Presentación SIAC", "Acuerdo normativo SIAC"],
        "intro": "2024–II | 2025-I",
        "sections": [
            {"heading": "Acuerdo normativo SIAC – UNAL «Propuesta»", "body": None},
            {"heading": "Política de calidad", "body": None},
        ],
    },
    "arquitectura-siac": {
        "title": "Arquitectura del SIAC",
        "breadcrumb": ["Presentación SIAC", "Arquitectura del SIAC"],
        "intro": None,
        "sections": [],
    },
    "retos-bpun": {
        "title": "Retos para el cumplimiento en el BPUN",
        "breadcrumb": ["Presentación SIAC", "Retos para el cumplimiento en el BPUN"],
        "intro": "2024–II | 2025-I",
        "sections": [
            {"heading": "3. Retos para el cumplimiento en el BPUN", "body": None},
        ],
    },
    "contacto": {
        "title": "Contacto",
        "breadcrumb": ["Contacto"],
        "intro": "siac_nal@unal.edu.co",
        "sections": [],
    },
}


def leyenda_sections() -> list[dict]:
    wb = openpyxl.load_workbook(XLSX, read_only=True, data_only=True)
    ws = wb["Leyenda y supuestos"]
    sections: list[dict] = []
    for row in ws.iter_rows(values_only=True):
        if not row or not any(row):
            continue
        a, b = row[0], row[1] if len(row) > 1 else None
        if a is None:
            continue
        a_str = str(a).strip()
        if a_str in ("Cómo usar este libro", "Supuestos", "Ejemplo de llenado (no está en la tabla)"):
            sections.append({"heading": a_str, "body": None, "list": None})
            continue
        if b is None or (isinstance(b, str) and not b.strip()):
            if a_str.isdigit():
                continue
            sections.append({"heading": a_str, "body": None, "list": None})
            continue
        b_str = str(b).strip()
        if a_str.isdigit():
            sections.append({"heading": f"Supuesto {a_str}", "body": b_str, "list": None})
        elif a_str == "Los valores del ejemplo son ilustrativos.":
            sections.append({"heading": None, "body": a_str, "list": None})
        else:
            sections.append({"heading": a_str, "body": b_str, "list": None})
    wb.close()
    return sections


def matriz_intro() -> str:
    for sec in leyenda_sections():
        if sec.get("heading") == "Hoja Indicadores":
            return sec.get("body") or ""
    return ""


def load_al_detalle() -> dict:
    if not AL_DETALLE.is_file():
        return {"pages": {}, "menu": None, "quick_links": []}
    return json.loads(AL_DETALLE.read_text(encoding="utf-8"))


def attach_simulacro_images(pages: dict) -> None:
    if not SIMULACRO_IMAGES.is_file():
        return

    manifest = json.loads(SIMULACRO_IMAGES.read_text(encoding="utf-8"))
    slides = manifest.get("slides") or []

    pages["presentacion-simulacro"] = {
        "title": "Presentación simulacro — 6 de octubre",
        "breadcrumb": ["Presentación SIAC", "Simulacro 6 de octubre"],
        "intro": (
            "Diapositivas de la reunión de simulacro del SIAC (6 de octubre de 2026), "
            "tomadas de la presentación oficial en PDF."
        ),
        "gallery": [
            {
                "src": slide["file"],
                "alt": slide.get("title") or f"Diapositiva {slide['number']}",
                "caption": f"Diapositiva {slide['number']}: {slide.get('title', '')}".strip(": "),
            }
            for slide in slides
        ],
        "sections": [],
    }

    for slide in slides:
        image = {
            "src": slide["file"],
            "alt": slide.get("title") or f"Diapositiva {slide['number']}",
            "caption": slide.get("title"),
        }
        for slug in slide.get("pages") or []:
            if slug == "presentacion-simulacro":
                continue
            if slug not in pages:
                continue
            pages[slug].setdefault("images", []).append(image)


def main() -> None:
    leyenda = leyenda_sections()
    al_detalle = load_al_detalle()
    pages = dict(PDF)
    pages.update(al_detalle.get("pages") or {})
    pages["matriz-indicadores"] = {
        "title": "Indicadores",
        "breadcrumb": ["Matriz de indicadores"],
        "intro": matriz_intro(),
        "sections": [],
    }
    pages["leyenda-matriz"] = {
        "title": "Leyenda y supuestos",
        "breadcrumb": ["Matriz de indicadores", "Leyenda y supuestos"],
        "intro": None,
        "sections": leyenda,
    }

    attach_simulacro_images(pages)

    menu = [
        {"label": "Inicio", "route": "siac.home"},
        {
            "label": "Presentación SIAC",
            "children": [
                {"label": "¿Por qué es importante?", "route": "siac.page", "params": {"slug": "importancia-siac"}},
                {"label": "Concepto de calidad", "route": "siac.page", "params": {"slug": "concepto-calidad"}},
                {"label": "Modelo de calidad", "route": "siac.page", "params": {"slug": "modelo-calidad"}},
                {"label": "¿Qué es el SIAC – UNAL?", "route": "siac.page", "params": {"slug": "que-es-siac"}},
                {"label": "Acuerdo normativo SIAC", "route": "siac.page", "params": {"slug": "acuerdo-normativo-siac"}},
                {"label": "Arquitectura del SIAC", "route": "siac.page", "params": {"slug": "arquitectura-siac"}},
                {"label": "Retos BPUN", "route": "siac.page", "params": {"slug": "retos-bpun"}},
                {
                    "label": "Simulacro 6 de octubre (diapositivas)",
                    "route": "siac.page",
                    "params": {"slug": "presentacion-simulacro"},
                },
            ],
        },
        {
            "label": "Matriz de indicadores",
            "children": [
                {"label": "Indicadores", "route": "siac.page", "params": {"slug": "matriz-indicadores"}},
                {"label": "Leyenda y supuestos", "route": "siac.page", "params": {"slug": "leyenda-matriz"}},
            ],
        },
        {"label": "Fuentes", "route": "siac.documents.index"},
        {"label": "Contacto", "route": "siac.page", "params": {"slug": "contacto"}},
    ]

    al_menu = al_detalle.get("menu")
    if al_menu and al_menu.get("children"):
        menu.insert(
            2,
            {
                "label": al_menu["label"],
                "children": [
                    {
                        "label": child["label"],
                        "route": "siac.page",
                        "params": {"slug": child["slug"]},
                    }
                    for child in al_menu["children"]
                ],
            },
        )

    quick_links = [
        {"label": "¿Qué es el SIAC?", "route": "siac.page", "params": {"slug": "que-es-siac"}},
        {"label": "Concepto de calidad", "route": "siac.page", "params": {"slug": "concepto-calidad"}},
        {"label": "Indicadores", "route": "siac.page", "params": {"slug": "matriz-indicadores"}},
        {"label": "Leyenda matriz", "route": "siac.page", "params": {"slug": "leyenda-matriz"}},
        {"label": "Fuentes", "route": "siac.documents.index"},
        {"label": "Contacto", "route": "siac.page", "params": {"slug": "contacto"}},
    ]
    for link in al_detalle.get("quick_links") or []:
        quick_links.insert(0, {
            "label": link["label"],
            "route": "siac.page",
            "params": {"slug": link["slug"]},
        })

    payload = {
        "sources": {
            "pdf": "SIAC_Reunión 6 de octubre _ Simulacro_V.2.pptx.pdf",
            "excel": XLSX.name,
        },
        "contact_email": "siac_nal@unal.edu.co",
        "home_lead": pages["que-es-siac"]["intro"],
        "menu": menu,
        "quick_links": quick_links,
        "pages": pages,
    }

    OUT.parent.mkdir(parents=True, exist_ok=True)
    OUT.write_text(json.dumps(payload, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"Escrito {OUT} ({len(pages)} páginas)")


if __name__ == "__main__":
    main()
