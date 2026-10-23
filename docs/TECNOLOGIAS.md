# Tecnologías utilizadas — Micrositio SIAC

## Backend

| Tecnología | Versión | Uso |
|------------|---------|-----|
| **PHP** | 8.0+ | Runtime |
| **Laravel** | 9.x | Framework MVC, rutas, auth, Eloquent, storage |
| **Composer** | 2.x | Dependencias PHP |
| **MySQL / MariaDB** | 5.7+ / 10.x | Producción (documentos, usuarios) |
| **SQLite** | 3 | Pruebas automatizadas (`:memory:`) |

## Frontend (contenido)

| Tecnología | Uso |
|------------|-----|
| **Blade** | Plantillas Laravel |
| **Bootstrap 4** | Grid, formularios, componentes |
| **Plantilla UNAL** | Cabecera, pie, accesibilidad (`public/unal/`) |
| **CSS propio** | Paleta SIAC en `public/css/siac.css` |
| **JavaScript** | Filtros de indicadores (`public/js/siac-indicators.js`), jQuery/Bootstrap UNAL |

## Datos y scripts

| Herramienta | Uso |
|-------------|-----|
| **Python 3** | `build_site_content.py`, `import_indicators_from_excel.py`, `extract_simulacro_images.py` |
| **openpyxl** | Lectura del Excel de indicadores |
| **PyMuPDF (fitz)** | Renderizado de diapositivas del PDF del simulacro |

## Pruebas

| Herramienta | Uso |
|-------------|-----|
| **PHPUnit** | 9.x — pruebas unitarias y de funcionalidad |
| **Locust** | 2.x — pruebas de carga y capacidad HTTP |

## Infraestructura típica

| Entorno | Stack |
|---------|--------|
| Desarrollo local | Laragon / `php artisan serve`, Apache opcional |
| Producción | Apache o Nginx + PHP-FPM, TLS, document root `public/` |

## Dependencias PHP (composer.json)

- `laravel/framework` ^9.0  
- `guzzlehttp/guzzle` ^7.2  
- `laravel/sanctum` ^2.14 (API tokens, reservado)  
- Dev: `phpunit/phpunit` ^9.5, `fakerphp/faker`, `laravel/sail`
