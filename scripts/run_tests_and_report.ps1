# Ejecuta PHPUnit y opcionalmente Locust; actualiza docs/INFORME_PRUEBAS.md
param(
    [string]$HostUrl = "http://127.0.0.1:8000",
    [switch]$SkipLocust
)

$ErrorActionPreference = "Stop"
$Root = Split-Path -Parent $PSScriptRoot
Set-Location $Root

$ReportPath = Join-Path $Root "docs\INFORME_PRUEBAS.md"
$PhpOut = Join-Path $Root "docs\phpunit-last-run.txt"

Write-Host "=== PHPUnit ==="
php artisan test 2>&1 | Tee-Object -FilePath $PhpOut
$PhpExit = $LASTEXITCODE

$LocustSection = "No ejecutado (use -SkipLocust:`$false y servidor activo)."
if (-not $SkipLocust) {
    Write-Host "=== Locust (headless) ==="
    python -m pip install -q -r loadtests/requirements.txt *>$null
    $locustLog = Join-Path $Root "docs\locust-last-run.txt"
    locust -f loadtests/locustfile.py --headless -u 20 -r 5 -t 30s --host=$HostUrl 2>&1 | Tee-Object -FilePath $locustLog
    $locustExit = $LASTEXITCODE
    if ($locustExit -eq 0 -and (Test-Path $locustLog)) {
        $LocustSection = Get-Content $locustLog -Raw
    } else {
        $LocustSection = "Locust no completado (¿servidor en $HostUrl?). Ver docs/locust-last-run.txt"
    }
}

$Date = Get-Date -Format "yyyy-MM-dd HH:mm"
$PhpBody = Get-Content $PhpOut -Raw

@"
# Informe de pruebas — Micrositio SIAC

**Generado:** $Date  
**Proyecto:** micrositio-siac (Laravel 9)

---

## 1. Resumen ejecutivo

| Tipo | Herramienta | Resultado |
|------|-------------|-----------|
| Unitarias / funcionalidad | PHPUnit (Laravel Test) | $(if ($PhpExit -eq 0) { 'OK' } else { 'FALLÓ — revisar salida' }) |
| Carga / capacidad | Locust | Ver sección 3 |

---

## 2. Pruebas PHPUnit

### Alcance

- **Unit:** `Document`, `SiacContentRepository`, `SiacIndicatorRepository`
- **Feature:** rutas públicas, descarga de documentos, autenticación y admin

### Comando

``````bash
php artisan test
``````

### Salida de la última ejecución

``````
$PhpBody
``````

---

## 3. Pruebas de carga (Locust)

### Escenarios

- Usuario **SiacVisitante**: inicio, documentos, páginas SIAC, búsqueda, CSS.
- Usuario **SiacDocumentosFiltrados**: listado con query string.

### Comando (headless)

``````bash
pip install -r loadtests/requirements.txt
locust -f loadtests/locustfile.py --headless -u 30 -r 5 -t 60s --host=http://127.0.0.1:8000
``````

### Interfaz web

``````bash
locust -f loadtests/locustfile.py --host=http://127.0.0.1:8000
# Abrir http://localhost:8089
``````

### Última ejecución

``````
$LocustSection
``````

---

## 4. Criterios de aceptación sugeridos

- PHPUnit: 0 fallos en CI y pre-despliegue.
- Locust (30 usuarios, 60 s): tasa de error HTTP &lt; 1 %; p95 &lt; 3 s en rutas públicas principales.
- Smoke manual post-despliegue: inicio, documentos, descarga fuente Excel, login admin.

"@ | Set-Content -Path $ReportPath -Encoding UTF8

Write-Host "Informe escrito en $ReportPath"
exit $PhpExit
