# Documentación técnica — Micrositio SIAC

## 1. Propósito

Aplicación web institucional del **Sistema Interno de Aseguramiento de la Calidad (SIAC)** de la Universidad Nacional de Colombia. Publica contenidos del simulacro del 6 de octubre, la matriz de indicadores (Excel), documentos clasificados por componentes funcionales y ciclo ACTÚA, y un módulo de administración restringido.

## 2. Arquitectura

```
┌─────────────────────────────────────────────────────────────┐
│  Navegador (plantilla UNAL: header / main / footer)         │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────┐
│  Laravel 9 — routes/web.php                                 │
│  SiacController · DocumentController · Admin · Auth         │
└───────────┬─────────────────────────────┬───────────────────┘
            │                             │
   ┌────────▼────────┐           ┌────────▼────────┐
   │ JSON estático   │           │ MySQL/SQLite    │
   │ site_content    │           │ documents       │
   │ indicators.json │           │ users           │
   └────────┬────────┘           └────────┬────────┘
            │                             │
   ┌────────▼─────────────────────────────▼────────┐
   │ storage/app/public · public/fuentes-siac      │
   │ public/images/simulacro                       │
   └───────────────────────────────────────────────┘
```

## 3. Fuentes de datos

| Fuente | Generación | Consumo |
|--------|------------|---------|
| PDF simulacro 6-oct | Texto en `scripts/build_site_content.py`; imágenes en `scripts/extract_simulacro_images.py` | Páginas «Presentación SIAC» |
| Excel indicadores | `scripts/import_indicators_from_excel.py` → `indicators.json` | `/pagina/matriz-indicadores` |
| SIAC al detalle | `content/siac_al_detalle.json` | Menú y páginas temáticas |
| Documentos | Admin + seeder | `/documentos`, descargas |

Regeneración:

```bash
python scripts/extract_simulacro_images.py
python scripts/build_site_content.py
python scripts/import_indicators_from_excel.py
```

## 4. Rutas principales

| Ruta | Nombre | Descripción |
|------|--------|-------------|
| `GET /` | `siac.home` | Inicio |
| `GET /pagina/{slug}` | `siac.page` | Contenido editorial + diapositivas |
| `GET /documentos` | `siac.documents.index` | Repositorio público |
| `GET /documentos/archivo/{id}` | `siac.documents.download` | Descarga segura |
| `GET /buscar` | `siac.search` | Búsqueda en páginas y documentos |
| `GET/POST /login` | `login` | Acceso administrador |
| `resource /admin/documentos` | `admin.documents.*` | CRUD (auth) |

## 5. Capas de aplicación

- **`App\Support\SiacContentRepository`**: menú, páginas, contacto desde `site_content.json`.
- **`App\Support\SiacIndicatorRepository`**: matriz, filtros, resumen desde `indicators.json`.
- **`App\Models\Document`**: metadatos, etiquetas ACTÚA/componente, `downloadHref()`, scope `filter()`.
- **Vistas**: `resources/views/siac/*`, layout `layouts/unal.blade.php`, estilos `public/css/siac.css`.

## 6. Seguridad

- Admin protegido con middleware `auth`.
- Login con throttle (10 intentos/minuto).
- Descargas vía controlador (no carpeta `public/documentos` que sombreaba rutas).
- Variables sensibles en `.env` (`SIAC_ADMIN_EMAIL`, `SIAC_ADMIN_PASSWORD`).

## 7. Pruebas y calidad

- **Unitarias / integración (PHPUnit)**: `tests/Unit`, `tests/Feature`.
- **Carga (Locust)**: `loadtests/locustfile.py`.
- Informe consolidado: [INFORME_PRUEBAS.md](./INFORME_PRUEBAS.md).

## 8. Documentos relacionados

- [TECNOLOGIAS.md](./TECNOLOGIAS.md)
- [DESPLIEGUE.md](./DESPLIEGUE.md)
- [INFORME_PRUEBAS.md](./INFORME_PRUEBAS.md)
