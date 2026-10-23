"""
Pruebas de carga / capacidad del micrositio SIAC con Locust.

Uso (servidor Laravel en ejecución):
  pip install -r loadtests/requirements.txt
  locust -f loadtests/locustfile.py --host=http://127.0.0.1:8000

Modo headless (informe en consola):
  locust -f loadtests/locustfile.py --headless -u 30 -r 5 -t 60s --host=http://127.0.0.1:8000
"""

from __future__ import annotations

import os

from locust import HttpUser, between, task


class SiacVisitante(HttpUser):
    """Simula usuarios públicos consultando páginas frecuentes."""

    wait_time = between(0.3, 1.5)

    @task(5)
    def inicio(self) -> None:
        self.client.get("/", name="GET /")

    @task(4)
    def documentos(self) -> None:
        self.client.get("/documentos", name="GET /documentos")

    @task(3)
    def pagina_que_es_siac(self) -> None:
        self.client.get("/pagina/que-es-siac", name="GET /pagina/que-es-siac")

    @task(2)
    def matriz_indicadores(self) -> None:
        self.client.get("/pagina/matriz-indicadores", name="GET /pagina/matriz-indicadores")

    @task(2)
    def buscar(self) -> None:
        self.client.get("/buscar?q=calidad", name="GET /buscar")

    @task(1)
    def presentacion_simulacro(self) -> None:
        self.client.get("/pagina/presentacion-simulacro", name="GET /pagina/presentacion-simulacro")

    @task(1)
    def asset_css(self) -> None:
        self.client.get("/css/siac.css", name="GET /css/siac.css")


class SiacDocumentosFiltrados(HttpUser):
    """Consultas con filtros en el repositorio de documentos."""

    wait_time = between(0.5, 2.0)
    weight = 1

    @task
    def documentos_filtrados(self) -> None:
        self.client.get(
            "/documentos?q=indicadores&functional_component=evaluacion_institucional",
            name="GET /documentos (filtros)",
        )
