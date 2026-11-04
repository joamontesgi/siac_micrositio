(function () {
  "use strict";

  var cfgEl = document.getElementById("siac-validacion-config");
  if (!cfgEl) return;

  var STORE_URL = cfgEl.dataset.storeUrl || "";
  var SUMMARY_URL = cfgEl.dataset.summaryUrl || "";
  var ACTA_URL = cfgEl.dataset.actaUrl || "";
  var CSRF = cfgEl.dataset.csrf || "";

  var CRIT = [
    "Pertinencia institucional",
    "Integralidad de la calidad",
    "Capacidad orientadora",
  ];
  var rows = [];

  var $ = function (id) {
    return document.getElementById(id);
  };
  function esc(s) {
    return String(s == null ? "" : s).replace(/[&<>"]/g, function (c) {
      return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" }[c];
    });
  }
  function toast(msg) {
    var t = document.createElement("div");
    t.className = "toast";
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function () {
      t.remove();
    }, 3200);
  }
  function aviso(html) {
    var a = $("aviso");
    if (!a) return;
    a.innerHTML = html;
    a.hidden = !html;
  }

  var CAB = [
    "Marca de tiempo",
    "Nombre",
    "Dependencia",
    "Estamento",
    "P1 Pertinencia",
    "Obs P1",
    "P2 Integralidad",
    "Obs P2",
    "P3 Capacidad orientadora",
    "Obs P3",
  ];

  function aCSV(lista) {
    function c(v) {
      return '"' + String(v == null ? "" : v).replace(/"/g, '""') + '"';
    }
    var l = [CAB.map(c).join(";")];
    lista.forEach(function (r) {
      l.push(
        [
          r.ts,
          r.nombre,
          r.dependencia,
          r.rol,
          r.p1,
          r.o1,
          r.p2,
          r.o2,
          r.p3,
          r.o3,
        ]
          .map(c)
          .join(";")
      );
    });
    return "\ufeff" + l.join("\r\n");
  }

  function descargar(nombre, texto, tipo) {
    var blob = new Blob([texto], {
      type: (tipo || "text/csv") + ";charset=utf-8",
    });
    var url = URL.createObjectURL(blob);
    var a = document.createElement("a");
    a.href = url;
    a.download = nombre;
    document.body.appendChild(a);
    a.click();
    setTimeout(function () {
      URL.revokeObjectURL(url);
      a.remove();
    }, 400);
  }

  function render() {
    var n = rows.length;
    if ($("conteo")) {
      $("conteo").textContent =
        n === 0
          ? "Sin respuestas"
          : n + (n === 1 ? " respuesta" : " respuestas");
    }
    if ($("vacio")) $("vacio").hidden = n > 0;
    if ($("tally")) $("tally").hidden = n === 0;
    for (var q = 1; q <= 3; q++) {
      var si = 0,
        no = 0;
      for (var i = 0; i < n; i++) {
        var v = rows[i]["p" + q];
        if (v === "Sí") si++;
        else if (v === "No") no++;
      }
      if ($("c" + q)) $("c" + q).textContent = si + " Sí · " + no + " No";
      if ($("b" + q + "si"))
        $("b" + q + "si").style.width = (n ? (si / n) * 100 : 0) + "%";
      if ($("b" + q + "no"))
        $("b" + q + "no").style.width = (n ? (no / n) * 100 : 0) + "%";
    }
    var obs = [];
    rows.forEach(function (r) {
      for (var k = 1; k <= 3; k++) {
        if (r["o" + k]) {
          obs.push({
            q: k,
            autor: r.nombre || "Participante sin nombre",
            dep: r.dependencia,
            texto: r["o" + k],
          });
        }
      }
    });
    obs.sort(function (a, b) {
      return a.q - b.q;
    });
    if ($("observaciones")) {
      $("observaciones").innerHTML = obs
        .map(function (o) {
          return (
            '<div class="obs-item"><div class="obs-meta">' +
            o.q +
            " · " +
            esc(CRIT[o.q - 1]) +
            " — " +
            esc(o.autor) +
            (o.dep ? ", " + esc(o.dep) : "") +
            '</div><p class="obs-texto">' +
            esc(o.texto) +
            "</p></div>"
          );
        })
        .join("");
    }
  }

  function copiarTexto(texto) {
    if (!texto) {
      toast("No hay texto para copiar");
      return Promise.reject();
    }
    if (navigator.clipboard && window.isSecureContext) {
      return navigator.clipboard.writeText(texto).then(function () {
        toast("Resumen copiado al portapapeles");
      });
    }
    return new Promise(function (resolve, reject) {
      var ta = document.createElement("textarea");
      ta.value = texto;
      ta.setAttribute("readonly", "");
      ta.style.position = "fixed";
      ta.style.left = "-9999px";
      document.body.appendChild(ta);
      ta.select();
      try {
        var ok = document.execCommand("copy");
        document.body.removeChild(ta);
        if (ok) {
          toast("Resumen copiado al portapapeles");
          resolve();
        } else {
          reject(new Error("copy"));
        }
      } catch (e) {
        document.body.removeChild(ta);
        reject(e);
      }
    });
  }

  function obtenerResumenActa() {
    if (ACTA_URL) {
      return fetch(ACTA_URL, {
        headers: { Accept: "application/json" },
        credentials: "same-origin",
      })
        .then(function (res) {
          if (!res.ok) throw new Error("acta");
          return res.json();
        })
        .then(function (data) {
          return data.text || "";
        });
    }
    return Promise.reject(new Error("no-url"));
  }

  function leerFormulario() {
    return {
      nombre: $("nombre").value.trim(),
      dependencia: $("dependencia").value.trim(),
      rol: $("rol").value,
      p1: (document.querySelector('input[name=p1]:checked') || {}).value || "",
      p2: (document.querySelector('input[name=p2]:checked') || {}).value || "",
      p3: (document.querySelector('input[name=p3]:checked') || {}).value || "",
      o1: $("o1").value.trim(),
      o2: $("o2").value.trim(),
      o3: $("o3").value.trim(),
    };
  }

  function cargarResumen() {
    if (!SUMMARY_URL) return Promise.resolve();
    return fetch(SUMMARY_URL, {
      headers: { Accept: "application/json" },
      credentials: "same-origin",
    })
      .then(function (res) {
        if (!res.ok) throw new Error("resumen");
        return res.json();
      })
      .then(function (data) {
        rows = Array.isArray(data.rows) ? data.rows : [];
        render();
      })
      .catch(function () {
        toast("No se pudo cargar el consolidado");
      });
  }

  var form = $("form");
  if (form) {
    form.addEventListener("submit", function (ev) {
      ev.preventDefault();
      var r = leerFormulario();
      if (!r.p1 || !r.p2 || !r.p3) {
        aviso(
          "Responda <b>Sí</b> o <b>No</b> en las tres preguntas antes de enviar."
        );
        return;
      }
      if (!STORE_URL) {
        aviso("El envío al servidor no está configurado.");
        return;
      }
      $("enviar").disabled = true;
      fetch(STORE_URL, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-CSRF-TOKEN": CSRF,
        },
        credentials: "same-origin",
        body: JSON.stringify(r),
      })
        .then(function (res) {
          if (!res.ok) throw new Error("store");
          return res.json();
        })
        .then(function (data) {
          window.__miRespuesta = data.row || r;
          $("descargarMia").hidden = false;
          $("otra").hidden = false;
          aviso(
            "Validación guardada en el micrositio. Gracias por participar."
          );
          return cargarResumen();
        })
        .catch(function () {
          $("enviar").disabled = false;
          aviso(
            "No se pudo guardar la respuesta. Intente de nuevo en unos segundos."
          );
        });
    });
  }

  if ($("descargarMia")) {
    $("descargarMia").addEventListener("click", function () {
      var r = window.__miRespuesta;
      if (!r) return;
      var quien = (r.nombre || "participante")
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, "_")
        .replace(/^_|_$/g, "");
      descargar(
        "validacion_calidad_" + (quien || "participante") + ".csv",
        aCSV([r])
      );
    });
  }

  if ($("otra")) {
    $("otra").addEventListener("click", function () {
      if (form) form.reset();
      aviso("");
      $("enviar").disabled = false;
      $("otra").hidden = true;
      $("descargarMia").hidden = true;
      $("nombre").focus();
    });
  }

  if ($("csv")) {
    $("csv").addEventListener("click", function () {
      if (!rows.length) {
        toast("Todavía no hay respuestas que descargar");
        return;
      }
      descargar("validacion_concepto_calidad_SIAC.csv", aCSV(rows));
    });
  }

  if ($("acta")) {
    $("acta").addEventListener("click", function () {
      var btn = $("acta");
      btn.disabled = true;
      obtenerResumenActa()
        .then(function (texto) {
          if (!texto.trim()) {
            toast("Aún no hay respuestas para el acta");
            return;
          }
          return copiarTexto(texto);
        })
        .catch(function () {
          toast("No se pudo generar el resumen; use Descargar en administración");
        })
        .finally(function () {
          btn.disabled = false;
        });
    });
  }

  cargarResumen();
})();
