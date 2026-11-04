#!/usr/bin/env python3
"""Convierte ayuda/index.html en CSS, JS y partial Blade para Laravel."""

from __future__ import annotations

import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
HTML = ROOT / "ayuda" / "index.html"
OUT_CSS = ROOT / "public" / "css" / "siac-campana.css"
OUT_JS = ROOT / "public" / "js" / "siac-campana-data.js"
OUT_BODY = ROOT / "resources" / "views" / "siac" / "campana" / "_body.blade.php"

TABS_SHELL = """<div class="campana-tabs-shell" aria-label="Secciones de socialización Fase II">
  <div class="tabsbar">
    <div class="wrap">
      <ul role="tablist" aria-label="Secciones del micrositio">
        <li><button role="tab" id="t-siac" aria-controls="p-siac" aria-selected="true">El SIAC</button></li>
        <li><button role="tab" id="t-calidad" aria-controls="p-calidad" aria-selected="false">Calidad en la UNAL</button></li>
        <li><button role="tab" id="t-normatividad" aria-controls="p-normatividad" aria-selected="false">Normatividad</button></li>
        <li><button role="tab" id="t-sisaica" aria-controls="p-sisaica" aria-selected="false">SISAICA</button></li>
        <li><button role="tab" id="t-participa" aria-controls="p-participa" aria-selected="false">Participa</button></li>
        <li><button role="tab" id="t-sedes" aria-controls="p-sedes" aria-selected="false">Sedes y agenda</button></li>
        <li><button role="tab" id="t-devolucion" aria-controls="p-devolucion" aria-selected="false">Lo dijiste, quedó</button></li>
        <li><button role="tab" id="t-preguntas" aria-controls="p-preguntas" aria-selected="false">Preguntas</button></li>
      </ul>
    </div>
  </div>
</div>
"""


def scope_css_for_unal(css: str) -> str:
    css = css.replace(
        "*{box-sizing:border-box}\n"
        "body{margin:0;background:var(--bg);color:var(--ink);font-family:var(--body);font-size:17px;line-height:1.6;font-synthesis-weight:none;font-synthesis:none}\n"
        "body::before{content:\"\";position:fixed;inset:0 0 auto;height:env(safe-area-inset-top,0px);z-index:60;background:var(--navy-pure)}\n",
        ".siac-campana-wrap *{box-sizing:border-box}\n"
        ".siac-campana-wrap{margin:1.25rem -0.75rem 0;background:var(--bg);color:var(--ink);font-family:var(--body);font-size:17px;line-height:1.6;font-synthesis-weight:none;font-synthesis:none;border-radius:var(--r);overflow:hidden}\n",
    )
    css = css.replace(
        "h1,h2,h3,h4{font-family:var(--ui);",
        ".siac-campana-wrap h1,.siac-campana-wrap h2,.siac-campana-wrap h3,.siac-campana-wrap h4{font-family:var(--ui);",
    )
    css = css.replace(
        ".cover h1,.pagehead h1,.figures b{font-family:var(--display);",
        ".siac-campana-wrap .cover h1,.siac-campana-wrap .pagehead h1,.siac-campana-wrap .figures b{font-family:var(--display);",
    )
    css = css.replace("p{margin:0}", ".siac-campana-wrap p{margin:0}")
    css = css.replace("a{color:var(--teal-dk)}", ".siac-campana-wrap a{color:var(--teal-dk)}")
    css = css.replace("img{display:block}", ".siac-campana-wrap img{display:block}")
    css = css.replace(
        ":where(a,button,input,textarea,select,summary):focus-visible{",
        ".siac-campana-wrap :where(a,button,input,textarea,select,summary):focus-visible{",
    )
    css = css.replace(
        ".wrap{max-width:var(--maxw);",
        ".siac-campana-wrap .wrap{max-width:var(--maxw);",
    )
    css = css.replace("footer{background:", ".siac-campana-wrap footer{background:")
    css = css.replace("footer .wrap{padding-block:", ".siac-campana-wrap footer .wrap{padding-block:")
    css = css.replace("footer h4{", ".siac-campana-wrap footer h4{")
    css = css.replace("footer ul{", ".siac-campana-wrap footer ul{")
    css = css.replace("footer a{", ".siac-campana-wrap footer a{")
    css = css.replace("footer a:hover{", ".siac-campana-wrap footer a:hover{")
    for cls in (".footgrid", ".footbrand", ".sign", ".tags", ".legal"):
        css = css.replace(f"{cls}{{", f".siac-campana-wrap {cls}{{")
    for cls in (".btn", ".btn-main", ".btn-ghost"):
        css = css.replace(f"{cls}{{", f".siac-campana-wrap {cls}{{")
    return css


def strip_ayuda_chrome(body: str) -> str:
    """Quita barra utility y masthead de ayuda; conserva newsbar y pestañas."""
    body = re.sub(r"<div class=\"utility\">.*?</div>\s*", "", body, count=1, flags=re.S)
    body = re.sub(
        r"<header class=\"masthead\">.*?</header>\s*",
        TABS_SHELL + "\n\n",
        body,
        count=1,
        flags=re.S,
    )
    return body


def main() -> None:
    html = HTML.read_text(encoding="utf-8")

    m = re.search(r"<body>\s*<style>(.*?)</style>", html, re.S)
    css = m.group(1).strip() if m else ""
    css = scope_css_for_unal(css)

    extra = """
.siac-campana-wrap .campana-tabs-shell{
  position:sticky;
  top:0;
  z-index:40;
  background:var(--bg);
  border-bottom:1px solid var(--line);
}
/* Herramientas Laravel enlazadas desde la campaña */
.siac-campana-wrap .campana-tools {
  margin-top: 14px;
  padding: 16px 18px;
  background: var(--teal-soft);
  border: 1px solid var(--teal);
  border-radius: var(--r);
}
.siac-campana-wrap .campana-tools ul { margin: 10px 0 0; padding-left: 1.2rem; color: var(--ink); }
.siac-campana-wrap .campana-tools a { font-weight: 600; }
"""

    OUT_CSS.write_text(
        "/* Identificador Cálido SIAC 2026 — importado desde ayuda/index.html */\n"
        + css
        + extra,
        encoding="utf-8",
    )

    start = html.index('<div class="newsbar">')
    end = html.index("<footer>")
    body = html[start:end]

    replacements = [
        ('srcset="siac-movimiento.webp"', "srcset=\"{{ asset('campana/siac-movimiento.webp') }}\""),
        ('src="siac-movimiento.gif"', "src=\"{{ asset('campana/siac-movimiento.gif') }}\""),
        ('src="lockup2.png"', "src=\"{{ asset('campana/lockup2.png') }}\""),
        ('src="keyvisual.png"', "src=\"{{ asset('campana/keyvisual.png') }}\""),
        ('src="campus.jpg"', "src=\"{{ asset('campana/campus.jpg') }}\""),
        ('src="detalle.jpg"', "src=\"{{ asset('campana/detalle.jpg') }}\""),
    ]
    for old, new in replacements:
        body = body.replace(old, new)

    body = strip_ayuda_chrome(body)

    OUT_BODY.parent.mkdir(parents=True, exist_ok=True)
    OUT_BODY.write_text(body, encoding="utf-8")

    script_m = re.search(r"<script>\s*(const ACTS=[\s\S]*?)\s*</script>\s*</body>", html)
    script = script_m.group(1) if script_m else ""
    OUT_JS.write_text(script + "\n", encoding="utf-8")

    print(f"Escrito {OUT_CSS}")
    print(f"Escrito {OUT_BODY}")
    print(f"Escrito {OUT_JS}")


if __name__ == "__main__":
    main()
