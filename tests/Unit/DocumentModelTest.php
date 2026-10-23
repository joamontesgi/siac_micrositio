<?php

namespace Tests\Unit;

use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_label_uses_config(): void
    {
        $doc = new Document(['functional_component' => 'gestion_conocimiento']);

        $this->assertSame('Gestión del conocimiento y la información', $doc->componentLabel());
    }

    public function test_actua_label_for_todas_phase(): void
    {
        $doc = new Document(['actua_phase' => 'todas']);

        $this->assertSame('Todas · Ciclo ACTÚA', $doc->actuaLabel());
    }

    public function test_download_href_uses_route_for_public_relative_url(): void
    {
        $doc = Document::query()->create([
            'title' => 'Ejemplo.pdf',
            'document_type' => 'documento',
            'url' => 'fuentes-siac/ejemplo.pdf',
            'status' => 'vigente',
        ]);

        $href = $doc->downloadHref();

        $this->assertNotNull($href);
        $this->assertStringContainsString('/documentos/archivo/', $href);
    }

    public function test_filter_by_search_query(): void
    {
        Document::query()->create([
            'title' => 'Matriz SIAC',
            'document_type' => 'documento',
            'description' => 'Indicadores UNAL',
            'status' => 'vigente',
        ]);
        Document::query()->create([
            'title' => 'Otro archivo',
            'document_type' => 'documento',
            'status' => 'vigente',
        ]);

        $results = Document::query()->filter(['q' => 'Matriz'])->get();

        $this->assertCount(1, $results);
        $this->assertSame('Matriz SIAC', $results->first()->title);
    }

    public function test_filter_by_functional_component(): void
    {
        Document::query()->create([
            'title' => 'Doc A',
            'document_type' => 'documento',
            'functional_component' => 'evaluacion_institucional',
            'status' => 'vigente',
        ]);
        Document::query()->create([
            'title' => 'Doc B',
            'document_type' => 'documento',
            'functional_component' => 'gestion_cambio',
            'status' => 'vigente',
        ]);

        $results = Document::query()->filter(['functional_component' => 'gestion_cambio'])->get();

        $this->assertCount(1, $results);
        $this->assertSame('Doc B', $results->first()->title);
    }
}
