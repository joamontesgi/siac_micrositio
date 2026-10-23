# Guía de despliegue — Micrositio SIAC

## 1. Requisitos del servidor

- PHP **8.0+** con extensiones: `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`
- **Composer** 2.x
- **MySQL/MariaDB** (recomendado en producción)
- Servidor web con **document root** apuntando a `micrositio-siac/public`
- Python 3 (solo en máquina de build, para regenerar JSON/imágenes desde fuentes)

## 2. Instalación inicial

```bash
cd micrositio-siac
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

Configure `.env`:

```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://siac.unal.edu.co

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siac_micrositio
DB_USERNAME=...
DB_PASSWORD=...

SIAC_ADMIN_EMAIL=admin@unal.edu.co
SIAC_ADMIN_PASSWORD=...   # contraseña segura; cambiar tras primer login
```

Contenido estático generado desde fuentes (en servidor de build o local):

```bash
python scripts/extract_simulacro_images.py
python scripts/build_site_content.py
python scripts/import_indicators_from_excel.py
```

Base de datos y archivos:

```bash
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder --force
php artisan db:seed --class=SiacSeeder --force
php artisan storage:link
```

Optimización Laravel:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 3. Apache (ejemplo)

```apache
<VirtualHost *:443>
    ServerName siac.unal.edu.co
    DocumentRoot "C:/ruta/micrositio-siac/public"

    <Directory "C:/ruta/micrositio-siac/public">
        AllowOverride All
        Require all granted
    </Directory>

    SSLEngine on
    # Certificados TLS...
</VirtualHost>
```

**Importante:** no crear la carpeta física `public/documentos/` (conflicto con la ruta Laravel `/documentos`). Los estáticos de fuentes van en `public/fuentes-siac/`.

## 4. Nginx (ejemplo)

```nginx
server {
    listen 443 ssl;
    server_name siac.unal.edu.co;
    root /var/www/micrositio-siac/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 5. Laragon (desarrollo)

1. Document root del virtual host: `...\micrositio-siac\public`
2. `php artisan serve` alternativo: `http://127.0.0.1:8000`
3. Tras cambios en `.env`: `php artisan config:clear`

## 6. Permisos (Linux)

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache
```

## 7. Despliegue continuo (checklist)

1. `git pull` en servidor (o artefacto CI)
2. `composer install --no-dev --optimize-autoloader`
3. Regenerar JSON/imágenes si cambiaron PDF/Excel
4. `php artisan migrate --force`
5. `php artisan config:cache && php artisan route:cache && php artisan view:cache`
6. Reiniciar PHP-FPM si aplica
7. Smoke test: `/`, `/documentos`, `/pagina/que-es-siac`, login admin

## 8. Rollback

- Restaurar snapshot de BD y `storage/app/public/documentos/siac/`
- Revertir commit y repetir pasos 2–5 sin migraciones destructivas

## 9. Monitoreo recomendado

- Logs: `storage/logs/laravel.log`
- HTTP 5xx / tiempo de respuesta en `/` y `/documentos`
- Espacio en disco para cargas en `storage/app/public`
