<?php

namespace Tests\Unit;

use App\Support\SiacContentRepository;
use App\Support\SiacIndicatorRepository;
use App\Support\SiacStaticSite;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SiacStaticSiteTest extends TestCase
{
    private function site(bool $empty = false): SiacStaticSite
    {
        $content = new class extends SiacContentRepository {
            public function data(): array
            {
                return [
                    'home_lead' => 'La calidad en la UNAL',
                    'pages' => [
                        'que-es-siac' => [
                            'title' => '¿Qué es el SIAC?',
                            'sections' => [['heading' => 'Calidad', 'body' => 'Evaluación institucional']],
                        ],
                        'matriz-indicadores' => ['title' => 'Matriz de indicadores'],
                    ],
                ];
            }
        };
        $indicators = new class extends SiacIndicatorRepository {
            public bool $empty = false;
            public function data(): array
            {
                return ['indicators' => $this->empty ? [] : [[
                    'ID' => 'I-01', 'Indicador' => 'Participación', 'Conjunto' => 'Conjunto 1',
                    'Línea estratégica' => 'Calidad', 'Eje' => 'Evaluación', 'Estado' => 'Por definir',
                ]]];
            }
        };
        $indicators->empty = $empty;

        return new SiacStaticSite($content, $indicators);
    }

    public function test_public_pages_render_without_database_and_use_relative_links(): void
    {
        URL::forceRootUrl(SiacStaticSite::ORIGIN);
        URL::forceScheme('https');
        // Any accidental database access fails: no database is configured for the export.
        config(['database.default' => 'not-configured']);
        $pages = iterator_to_array($this->site()->pages());
        $this->assertCount(7, $pages);
        foreach ($pages as $html) {
            $this->assertStringContainsString('<html lang="es">', $html);
            $this->assertStringNotContainsString(SiacStaticSite::ORIGIN, $html);
            $this->assertStringContainsString('href="/login', $html);
            $this->assertStringNotContainsString('href="/admin', $html);
        }
        $this->assertStringContainsString('href="/fuentes-siac/', $pages['index.html']);
        $this->assertStringContainsString('I-01', $pages['pagina/matriz-indicadores/index.html']);
        $this->assertStringContainsString('/js/siac-search.js', $pages['buscar/index.html']);
        $this->assertStringContainsString('id="siac-auth"', $pages['login/index.html']);
        $this->assertStringContainsString('/js/siac-auth.js', $pages['index.html']);
        $this->assertStringNotContainsString('action="/login', $pages['login/index.html']);
        $this->assertStringContainsString('Página no encontrada', $pages['404.html']);
    }

    public function test_index_includes_page_content_and_downloadable_documents(): void
    {
        $index = $this->site()->searchIndex();
        $this->assertCount(4, $index);
        $this->assertStringContainsString('Evaluación institucional', $index[0]['text']);
        $this->assertSame('/pagina/que-es-siac', $index[0]['href']);
        $this->assertSame('/fuentes-siac/SIAC_Reunion_6_octubre_Simulacro_V2.pdf', $index[2]['href']);
    }

    public function test_missing_source_data_stops_publication(): void
    {
        $this->expectException(\RuntimeException::class);
        iterator_to_array($this->site(true)->pages());
    }
}
