<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class SiacSeeder extends Seeder
{
    public function run()
    {
        $allowedTitles = [
            'indicadores_UNAL_lineas_estrategicas.xlsx',
            'SIAC_Reunión 6 de octubre _ Simulacro_V.2.pptx.pdf',
        ];

        Document::query()->whereNotIn('title', $allowedTitles)->delete();

        $samples = [
            [
                'title' => 'indicadores_UNAL_lineas_estrategicas.xlsx',
                'description' => 'Matriz de indicadores por conjunto, eje, línea estratégica e indicador (hojas Indicadores, Resumen y Leyenda y supuestos).',
                'document_type' => 'matriz_excel',
                'process' => null,
                'institutional_level' => null,
                'year' => 2026,
                'dependency' => null,
                'topic' => null,
                'version' => null,
                'status' => 'vigente',
                'url' => 'fuentes-siac/indicadores_UNAL_lineas_estrategicas.xlsx',
                'published_at' => '2026-09-15',
            ],
            [
                'title' => 'SIAC_Reunión 6 de octubre _ Simulacro_V.2.pptx.pdf',
                'description' => 'Presentación SIAC: importancia, concepto de calidad, modelo de calidad, definición del SIAC, acuerdo normativo, arquitectura y retos BPUN.',
                'document_type' => 'presentacion_pdf',
                'process' => null,
                'institutional_level' => null,
                'year' => 2026,
                'dependency' => null,
                'topic' => null,
                'version' => '2.0',
                'status' => 'vigente',
                'url' => 'fuentes-siac/SIAC_Reunion_6_octubre_Simulacro_V2.pdf',
                'published_at' => '2026-10-06',
            ],
        ];

        foreach ($samples as $row) {
            Document::query()->updateOrCreate(
                ['title' => $row['title']],
                $row
            );
        }
    }
}
