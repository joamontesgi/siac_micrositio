<?php

$sitePath = __DIR__.'/../storage/app/data/site_content.json';
$site = is_file($sitePath)
    ? (json_decode((string) file_get_contents($sitePath), true) ?: [])
    : [];

return [
    'site_name' => 'SIAC — Sistema Interno de Aseguramiento de la Calidad',
    'site_url_label' => 'siac.unal.edu.co',
    'contact_email' => $site['contact_email'] ?? 'siac_nal@unal.edu.co',
    'sources' => $site['sources'] ?? [],

    'menu' => $site['menu'] ?? [],
    'quick_links' => $site['quick_links'] ?? [],

    /**
     * Cuatro componentes funcionales del SIAC (documentos administrables).
     */
    'functional_components' => [
        'evaluacion_institucional' => [
            'number' => 1,
            'label' => 'Evaluación institucional de la calidad',
            'short' => 'Evaluación institucional',
        ],
        'participacion_dialogo' => [
            'number' => 2,
            'label' => 'Participación y diálogo',
            'short' => 'Participación y diálogo',
        ],
        'gestion_conocimiento' => [
            'number' => 3,
            'label' => 'Gestión del conocimiento y la información',
            'short' => 'Gestión del conocimiento',
        ],
        'gestion_cambio' => [
            'number' => 4,
            'label' => 'Gestión del cambio y planeación estratégica',
            'short' => 'Gestión del cambio',
        ],
    ],

    'actua_phases' => [
        'todas' => [
            'letter' => '★',
            'label' => 'Todas las etapas del ciclo ACTÚA',
            'short' => 'Todas',
            'is_all' => true,
        ],
        'apreciar' => [
            'letter' => 'A',
            'label' => 'Apreciar la calidad',
            'short' => 'Apreciar',
        ],
        'construir' => [
            'letter' => 'C',
            'label' => 'Construir aprendizajes',
            'short' => 'Construir',
        ],
        'trazar' => [
            'letter' => 'T',
            'label' => 'Trazar respuestas',
            'short' => 'Trazar',
        ],
        'unir' => [
            'letter' => 'U',
            'label' => 'Unir capacidades',
            'short' => 'Unir',
        ],
        'aprender' => [
            'letter' => 'A',
            'label' => 'Aprender del cambio',
            'short' => 'Aprender',
        ],
    ],

    'document_types' => [
        'matriz_excel' => 'Matriz Excel',
        'presentacion_pdf' => 'Presentación PDF',
        'documento' => 'Documento',
    ],

    'processes' => [],
    'levels' => [],

    'upload' => [
        'max_kb' => 20480,
        'mimes' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'odt', 'ods', 'txt', 'csv'],
    ],
];
