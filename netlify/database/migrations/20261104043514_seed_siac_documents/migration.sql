-- Datos de database/seeders/SiacSeeder.php
INSERT INTO "documents" ("title", "description", "document_type", "year", "version", "status", "url", "published_at")
VALUES
  ('indicadores_UNAL_lineas_estrategicas.xlsx',
   'Matriz de indicadores por conjunto, eje, línea estratégica e indicador (hojas Indicadores, Resumen y Leyenda y supuestos).',
   'matriz_excel', 2026, NULL, 'vigente', 'fuentes-siac/indicadores_UNAL_lineas_estrategicas.xlsx', '2026-09-15'),
  ('SIAC_Reunión 6 de octubre _ Simulacro_V.2.pptx.pdf',
   'Presentación SIAC: importancia, concepto de calidad, modelo de calidad, definición del SIAC, acuerdo normativo, arquitectura y retos BPUN.',
   'presentacion_pdf', 2026, '2.0', 'vigente', 'fuentes-siac/SIAC_Reunion_6_octubre_Simulacro_V2.pdf', '2026-10-06')
ON CONFLICT ("title") DO UPDATE SET
  "description" = EXCLUDED."description",
  "document_type" = EXCLUDED."document_type",
  "year" = EXCLUDED."year",
  "version" = EXCLUDED."version",
  "status" = EXCLUDED."status",
  "url" = EXCLUDED."url",
  "published_at" = EXCLUDED."published_at",
  "updated_at" = now();
