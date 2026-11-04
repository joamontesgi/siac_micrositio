# Micrositio SIAC — Laravel 9

Aplicación web del **Sistema Interno de Aseguramiento de la Calidad (SIAC)** de la Universidad Nacional de Colombia.

**Todo el contenido del sitio proviene únicamente de dos fuentes:**

1. `SIAC_Reunión 6 de octubre _ Simulacro_V.2.pptx.pdf` — textos de presentación del SIAC.
2. `indicadores_UNAL_lineas_estrategicas.xlsx` — matriz de indicadores, resumen y leyenda.

## Requisitos

- PHP 8.0+
- Composer
- Python 3 + `openpyxl` (para regenerar datos desde el Excel)

## Instalación

```bash
cd micrositio-siac
composer install
cp .env.example .env   # si no existe
php artisan key:generate
python scripts/extract_simulacro_images.py
python scripts/build_site_content.py
python scripts/import_indicators_from_excel.py
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

### Administración de documentos

1. Configure en `.env` las variables `SIAC_ADMIN_EMAIL` y `SIAC_ADMIN_PASSWORD` (ver `.env.example`).
2. Ejecute `php artisan db:seed --class=AdminUserSeeder` si ya migró antes.
3. Ingrese en `/login` y cargue archivos en `/admin/documentos`, asignando uno de los **cuatro componentes funcionales** del SIAC.
4. Los archivos se guardan en `storage/app/public/documentos/siac/` (acceso vía enlace simbólico `public/storage`).

Abrir [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Documentación

| Documento | Contenido |
|-----------|-----------|
| [docs/DOCUMENTACION.md](docs/DOCUMENTACION.md) | Arquitectura, rutas, fuentes de datos |
| [docs/TECNOLOGIAS.md](docs/TECNOLOGIAS.md) | Stack técnico |
| [docs/DESPLIEGUE.md](docs/DESPLIEGUE.md) | Instalación y despliegue en producción |
| [docs/INFORME_PRUEBAS.md](docs/INFORME_PRUEBAS.md) | Resultados PHPUnit y Locust |
| [docs/overleaf/](docs/overleaf/) | LaTeX → PDF (Overleaf): arquitectura, tecnologías, despliegue, pruebas |
| [docs/SIAC-micrositio-documentacion-overleaf.zip](docs/SIAC-micrositio-documentacion-overleaf.zip) | ZIP para importar en Overleaf |

### Pruebas

```bash
php artisan test
pip install -r loadtests/requirements.txt
locust -f loadtests/locustfile.py --headless -u 30 -r 5 -t 60s --host=http://127.0.0.1:8000
```

Informe automatizado (Windows): `powershell -File scripts/run_tests_and_report.ps1`

## Regenerar contenido desde las fuentes

Tras actualizar el PDF (texto en `scripts/build_site_content.py`) o el Excel:

```bash
python scripts/extract_simulacro_images.py
python scripts/build_site_content.py
python scripts/import_indicators_from_excel.py
php artisan config:clear
php artisan db:seed --class=SiacSeeder
```

## Estructura

| Ruta | Descripción |
|------|-------------|
| `/` | Inicio (texto del PDF + resumen Excel) |
| `/pagina/{slug}` | Páginas derivadas del PDF o de la leyenda Excel |
| `/pagina/matriz-indicadores` | Tabla de indicadores (Excel) |
| `/documentos` | Documentos por componente funcional (público) |
| `/login` | Ingreso administrador |
| `/admin/documentos` | CRUD de documentos (requiere sesión) |
| `/buscar` | Búsqueda en páginas y fuentes |

- **Contenido editorial:** `storage/app/data/site_content.json` (PDF 6-oct + Excel + `content/siac_al_detalle.json` vía `scripts/build_site_content.py`).
- **Indicadores:** `storage/app/data/indicators.json` (generado por `scripts/import_indicators_from_excel.py`).
- **Copias públicas:** `public/fuentes-siac/` (no usar `public/documentos/`: reserva la ruta `/documentos` de Laravel).

## Notas

- Diapositivas del PDF sin texto extraíble (gráficos) no aportan contenido adicional al sitio.
- Los cinco motivos de importancia del SIAC y textos ampliados de presentación se mantienen en `scripts/build_site_content.py` (complementan lo extraído del PDF del simulacro).

## Publicación del micrositio público en Netlify

Netlify publica una versión estática; PHP se utiliza únicamente durante el despliegue para renderizar las mismas plantillas del sitio Laravel. `netlify.toml` configura el comando de exportación y publica exclusivamente `dist/`, sin exponer el código PHP ni la configuración de la aplicación.

Cada despliegue regenera el contenido y los indicadores desde las fuentes del repositorio. Incluye inicio, páginas informativas, matriz de indicadores con filtros, búsqueda en el navegador y descarga del PDF y Excel de referencia. Los cambios de contenido se publican mediante un nuevo despliegue.

El inicio de sesión, la carga de archivos y la administración de documentos pertenecen a la aplicación Laravel y no se incluyen en esta publicación pública. No se necesita una base de datos para generar el sitio estático. Las direcciones desconocidas muestran una página 404 con enlaces al inicio y a la búsqueda.
