<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiacPublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_returns_ok(): void
    {
        $response = $this->get(route('siac.home'));

        $response->assertOk();
        $response->assertSee('SIAC', false);
    }

    public function test_documents_index_returns_ok(): void
    {
        $response = $this->get(route('siac.documents.index'));

        $response->assertOk();
        $response->assertSee('Documentos del SIAC', false);
    }

    public function test_known_content_page_returns_ok(): void
    {
        if (! is_file(storage_path('app/data/site_content.json'))) {
            $this->markTestSkipped('Falta site_content.json');
        }

        $response = $this->get(route('siac.page', ['slug' => 'que-es-siac']));

        $response->assertOk();
        $response->assertSee('¿Qué es el SIAC', false);
    }

    public function test_unknown_page_returns_404(): void
    {
        $response = $this->get(route('siac.page', ['slug' => 'pagina-inexistente-xyz']));

        $response->assertNotFound();
    }

    public function test_search_page_returns_ok(): void
    {
        $response = $this->get(route('siac.search', ['q' => 'calidad']));

        $response->assertOk();
        $response->assertSee('Búsqueda', false);
    }
}
