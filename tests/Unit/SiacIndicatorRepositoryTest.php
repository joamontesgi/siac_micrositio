<?php

namespace Tests\Unit;

use App\Support\SiacIndicatorRepository;
use Tests\TestCase;

class SiacIndicatorRepositoryTest extends TestCase
{
    public function test_indicators_json_provides_matrix_data(): void
    {
        $path = storage_path('app/data/indicators.json');
        if (! is_file($path)) {
            $this->markTestSkipped('Falta indicators.json — ejecute import_indicators_from_excel.py');
        }

        $repo = new SiacIndicatorRepository;

        $this->assertGreaterThan(0, $repo->totalCount());
        $this->assertNotEmpty($repo->conjuntos());
        $this->assertNotEmpty($repo->conjuntoSummary());
    }

    public function test_filter_by_conjunto(): void
    {
        $path = storage_path('app/data/indicators.json');
        if (! is_file($path)) {
            $this->markTestSkipped('Falta indicators.json');
        }

        $repo = new SiacIndicatorRepository;
        $conjuntos = $repo->conjuntos();
        $sample = $conjuntos[0] ?? null;
        if ($sample === null) {
            $this->markTestSkipped('Sin conjuntos en la matriz');
        }

        $filtered = $repo->filter(['conjunto' => $sample]);

        $this->assertNotEmpty($filtered);
        foreach ($filtered as $row) {
            $this->assertSame($sample, (string) ($row['Conjunto'] ?? ''));
        }
    }
}
