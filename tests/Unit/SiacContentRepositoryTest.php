<?php

namespace Tests\Unit;

use App\Support\SiacContentRepository;
use Tests\TestCase;

class SiacContentRepositoryTest extends TestCase
{
    public function test_loads_pages_from_site_content_json(): void
    {
        $path = storage_path('app/data/site_content.json');
        if (! is_file($path)) {
            $this->markTestSkipped('Falta storage/app/data/site_content.json — ejecute build_site_content.py');
        }

        $repo = new SiacContentRepository;
        $pages = $repo->pages();

        $this->assertNotEmpty($pages);
        $this->assertArrayHasKey('que-es-siac', $pages);
        $this->assertSame('¿Qué es el SIAC – UNAL?', $pages['que-es-siac']['title']);
    }

    public function test_menu_and_contact_email_are_available(): void
    {
        $path = storage_path('app/data/site_content.json');
        if (! is_file($path)) {
            $this->markTestSkipped('Falta site_content.json');
        }

        $repo = new SiacContentRepository;

        $this->assertNotEmpty($repo->menu());
        $this->assertNotEmpty($repo->contactEmail());
        $this->assertStringContainsString('@', $repo->contactEmail());
    }
}
