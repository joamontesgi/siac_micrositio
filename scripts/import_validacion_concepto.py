#!/usr/bin/env python3
"""Extrae CSS y JS de validacion-concepto-calidad.html para Laravel."""

from __future__ import annotations

import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
HTML = ROOT / "validacion-concepto-calidad.html"
OUT_CSS = ROOT / "public" / "css" / "siac-validacion-concepto.css"
OUT_JS = ROOT / "public" / "js" / "siac-validacion-concepto.js"


def scope_css(css: str) -> str:
    css = css.replace(":root{", ".siac-validacion-concepto{")
    css = css.replace(
        "@media (prefers-color-scheme:dark){",
        "@media (prefers-color-scheme:dark){.siac-validacion-concepto{",
    )
    # body rules → wrapper
    css = css.replace("html,body{margin:0}", "")
    css = css.replace(
        "body{background:var(--papel);color:var(--tinta);font-family:var(--texto);font-size:17px;line-height:1.6;-webkit-font-smoothing:antialiased}",
        ".siac-validacion-concepto{background:var(--papel);color:var(--tinta);font-family:var(--texto);font-size:17px;line-height:1.6;-webkit-font-smoothing:antialiased;margin-top:2rem;border-radius:6px;overflow:hidden}",
    )
    prefixes = (
        ".wrap", ".cabecera", ".portada", ".instrumento", ".panel", ".pie",
        ".concepto", ".pregunta", ".opciones", ".opcion", ".acciones", ".aviso",
        ".identidad", "label.campo", "input[type=text]", "select", "textarea",
        "button", "details", ".toast", "h1", "h2", "h3", ".sello", ".titulo-bloque",
        ".lede", ".num", ".criterio", ".enunciado", ".obs", ".primario", ".secundario",
        ".panel-nota", ".cabeza-res", ".conteo", ".tally", ".fila", ".barra",
        ".leyenda", ".observaciones", ".obs-item", ".vacio", ".peligro", ".marca",
        ".eyebrow", ".logos",
    )
    for p in prefixes:
        css = re.sub(rf"(?<!\.){re.escape(p)}\{{", f".siac-validacion-concepto {p}{{", css)
    css = css.replace(".siac-validacion-concepto .siac-validacion-concepto ", ".siac-validacion-concepto ")
    return css


def main() -> None:
    html = HTML.read_text(encoding="utf-8")
    css_m = re.search(r"<style>(.*?)</style>", html, re.S)
    css = scope_css(css_m.group(1).strip() if css_m else "")

    OUT_CSS.write_text(
        "/* Validación concepto de calidad — scoped para layout UNAL */\n" + css,
        encoding="utf-8",
    )
    print("Nota: tras regenerar, revise el bloque «No heredar tema oscuro» al final del CSS.")

    js_m = re.search(r"<script>\s*(\(function\(\).*?)\s*</script>\s*</body>", html, re.S)
    OUT_JS.write_text((js_m.group(1) if js_m else "") + "\n", encoding="utf-8")

    print(f"Escrito {OUT_CSS}")
    print(f"Escrito {OUT_JS}")


if __name__ == "__main__":
    main()
