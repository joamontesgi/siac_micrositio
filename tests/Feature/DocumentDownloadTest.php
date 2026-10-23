<?php

namespace Tests\Feature;

use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class DocumentDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_download_from_public_fuentes_siac(): void
    {
        $dir = public_path('fuentes-siac');
        File::ensureDirectoryExists($dir);
        $relative = 'fuentes-siac/test-descarga.txt';
        $full = public_path($relative);
        File::put($full, 'contenido de prueba');

        try {
            $doc = Document::query()->create([
                'title' => 'Archivo prueba',
                'document_type' => 'documento',
                'url' => $relative,
                'status' => 'vigente',
            ]);

            $response = $this->get(route('siac.documents.download', $doc));

            $response->assertOk();
            $response->assertDownload('test-descarga.txt');
        } finally {
            if (is_file($full)) {
                File::delete($full);
            }
        }
    }

    public function test_download_missing_file_returns_404(): void
    {
        $doc = Document::query()->create([
            'title' => 'Sin archivo',
            'document_type' => 'documento',
            'url' => 'fuentes-siac/no-existe-12345.bin',
            'status' => 'vigente',
        ]);

        $response = $this->get(route('siac.documents.download', $doc));

        $response->assertNotFound();
    }
}
