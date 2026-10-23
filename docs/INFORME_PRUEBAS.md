# Informe de pruebas â€” Micrositio SIAC

**Generado:** 2026-10-23 10:45  
**Proyecto:** micrositio-siac (Laravel 9)

---

## 1. Resumen ejecutivo

| Tipo | Herramienta | Resultado |
|------|-------------|-----------|
| Unitarias / funcionalidad | PHPUnit (Laravel Test) | OK |
| Carga / capacidad | Locust | OK (0 % errores; muestra 15 usuarios / 20 s) |

---

## 2. Pruebas PHPUnit

### Alcance

- **Unit:** Document, SiacContentRepository, SiacIndicatorRepository
- **Feature:** rutas pÃºblicas, descarga de documentos, autenticaciÃ³n y admin

### Comando

```bash
php artisan test
```

### Salida de la Ãºltima ejecuciÃ³n

```
Warning: TTY mode is not supported on Windows platform.

  [30;42;1m PASS [39;49;22m[39m Tests\Unit\DocumentModelTest[39m
  [32;1mÔ£ô[39;22m[39m [2mcomponent label uses config[22m[39m
  [32;1mÔ£ô[39;22m[39m [2mactua label for todas phase[22m[39m
  [32;1mÔ£ô[39;22m[39m [2mdownload href uses route for public relative url[22m[39m
  [32;1mÔ£ô[39;22m[39m [2mfilter by search query[22m[39m
  [32;1mÔ£ô[39;22m[39m [2mfilter by functional component[22m[39m

  [30;42;1m PASS [39;49;22m[39m Tests\Unit\SiacContentRepositoryTest[39m
  [32;1mÔ£ô[39;22m[39m [2mloads pages from site content json[22m[39m
  [32;1mÔ£ô[39;22m[39m [2mmenu and contact email are available[22m[39m

  [30;42;1m PASS [39;49;22m[39m Tests\Unit\SiacIndicatorRepositoryTest[39m
  [32;1mÔ£ô[39;22m[39m [2mindicators json provides matrix data[22m[39m
  [32;1mÔ£ô[39;22m[39m [2mfilter by conjunto[22m[39m

  [30;42;1m PASS [39;49;22m[39m Tests\Feature\AuthAndAdminTest[39m
  [32;1mÔ£ô[39;22m[39m [2mguest is redirected from admin documents[22m[39m
  [32;1mÔ£ô[39;22m[39m [2mauthenticated user can access admin documents[22m[39m
  [32;1mÔ£ô[39;22m[39m [2mlogin with valid credentials[22m[39m

  [30;42;1m PASS [39;49;22m[39m Tests\Feature\DocumentDownloadTest[39m
  [32;1mÔ£ô[39;22m[39m [2mdownload from public fuentes siac[22m[39m
  [32;1mÔ£ô[39;22m[39m [2mdownload missing file returns 404[22m[39m

  [30;42;1m PASS [39;49;22m[39m Tests\Feature\SiacPublicRoutesTest[39m
  [32;1mÔ£ô[39;22m[39m [2mhome page returns ok[22m[39m
  [32;1mÔ£ô[39;22m[39m [2mdocuments index returns ok[22m[39m
  [32;1mÔ£ô[39;22m[39m [2mknown content page returns ok[22m[39m
  [32;1mÔ£ô[39;22m[39m [2munknown page returns 404[22m[39m
  [32;1mÔ£ô[39;22m[39m [2msearch page returns ok[22m[39m

  [37;1mTests:  [39;22m[32;1m19 passed[39;22m
  [37;1mTime:   [39;22m[39m2.07s[39m



```

---

## 3. Pruebas de carga (Locust)

### Escenarios

- Usuario **SiacVisitante**: inicio, documentos, pÃ¡ginas SIAC, bÃºsqueda, CSS.
- Usuario **SiacDocumentosFiltrados**: listado con query string.

### Comando (headless)

```bash
pip install -r loadtests/requirements.txt
locust -f loadtests/locustfile.py --headless -u 30 -r 5 -t 60s --host=http://127.0.0.1:8000
```

### Interfaz web

```bash
locust -f loadtests/locustfile.py --host=http://127.0.0.1:8000
# Abrir http://localhost:8089
```

### Ãšltima ejecuciÃ³n

Parámetros: `-u 15 -r 3 -t 20s --host=http://127.0.0.1:8000`

| Métrica | Valor |
|---------|--------|
| Peticiones totales | 46 |
| Fallos HTTP | 0 (0,00 %) |
| Tiempo medio agregado | ~3372 ms |
| p50 / p95 agregado | 3900 ms / 4700 ms |

Rutas: `/`, `/documentos`, filtros, páginas SIAC, `/buscar`, CSS.

---

## 4. Criterios de aceptaciÃ³n sugeridos

- PHPUnit: 0 fallos en CI y pre-despliegue.
- Locust (30 usuarios, 60 s): tasa de error HTTP &lt; 1 %; p95 &lt; 3 s en rutas pÃºblicas principales.
- Smoke manual post-despliegue: inicio, documentos, descarga fuente Excel, login admin.

