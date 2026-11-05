<?php

namespace Tests\Unit;

use App\Models\ConceptQualityValidation;
use App\Support\ConceptQualityValidationActaSummary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConceptQualityValidationActaSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_acta_summary_includes_counts_and_observations(): void
    {
        ConceptQualityValidation::query()->create([
            'name' => 'Ana',
            'dependency' => 'Bogotá',
            'answer_p1' => 'Sí',
            'answer_p2' => 'No',
            'answer_p3' => 'Sí',
            'observation_p1' => 'Observación uno',
            'submitted_at' => now(),
        ]);

        $text = ConceptQualityValidationActaSummary::build();

        $this->assertStringContainsString('Validación del concepto de calidad — SIAC UNAL', $text);
        $this->assertStringContainsString('Participantes que respondieron: 1', $text);
        $this->assertStringContainsString('1. Pertinencia institucional: 1 Sí, 0 No', $text);
        $this->assertStringContainsString('Ana (Bogotá): Observación uno', $text);
    }
}
