# Proyecto Overleaf — PDF documentación SIAC

Este directorio contiene el fuente LaTeX para generar un **PDF único** con:

1. Arquitectura  
2. Tecnologías  
3. Despliegue e instrucciones  
4. Documentación de pruebas **con resultados ejecutados** (PHPUnit + Locust)

## Subir a Overleaf

1. Comprima la carpeta `docs/overleaf/` (debe incluir `main.tex` y `sections/*.tex`).
2. En [Overleaf](https://www.overleaf.com): **New Project → Upload Project**.
3. Compilador: **pdfLaTeX** (por defecto).
4. Botón **Recompile** → descargue el PDF.

Estructura mínima del ZIP:

```
main.tex
sections/arquitectura.tex
sections/tecnologias.tex
sections/despliegue.tex
sections/pruebas.tex
```

## Compilar en local (opcional)

Con MiKTeX o TeX Live instalado:

```bash
cd docs/overleaf
pdflatex main.tex
pdflatex main.tex
```

El PDF quedará en `main.pdf`.

## Actualizar resultados de pruebas

Tras ejecutar pruebas en el repositorio:

```bash
php artisan test
locust -f loadtests/locustfile.py --headless -u 20 -r 5 -t 30s --host=http://127.0.0.1:8000
```

Actualice la sección `sections/pruebas.tex` (tablas y conteos) o ejecute el script de informe y copie métricas a LaTeX.
