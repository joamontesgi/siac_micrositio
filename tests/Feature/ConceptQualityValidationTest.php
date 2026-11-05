<?php

namespace Tests\Feature;

use App\Models\ConceptQualityValidation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConceptQualityValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_submit_validation(): void
    {
        $response = $this->postJson(route('siac.concept_validation.store'), [
            'nombre' => 'Ana Prueba',
            'dependencia' => 'Bogotá',
            'rol' => 'Docente',
            'p1' => 'Sí',
            'p2' => 'No',
            'p3' => 'Sí',
            'o1' => 'Comentario uno',
        ]);

        $response->assertCreated();
        $this->assertDatabaseCount('concept_quality_validations', 1);
    }

    public function test_summary_lists_saved_rows(): void
    {
        ConceptQualityValidation::query()->create([
            'name' => 'Luis',
            'answer_p1' => 'Sí',
            'answer_p2' => 'Sí',
            'answer_p3' => 'No',
            'submitted_at' => now(),
        ]);

        $response = $this->getJson(route('siac.concept_validation.summary'));

        $response->assertOk();
        $response->assertJsonPath('total', 1);
        $response->assertJsonPath('rows.0.nombre', 'Luis');
    }

    public function test_guest_cannot_access_admin_validations(): void
    {
        $response = $this->get(route('admin.concept_validations.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_acta_summary_json_endpoint(): void
    {
        ConceptQualityValidation::query()->create([
            'name' => 'Moderador',
            'answer_p1' => 'Sí',
            'answer_p2' => 'Sí',
            'answer_p3' => 'No',
            'submitted_at' => now(),
        ]);

        $response = $this->getJson(route('siac.concept_validation.acta', ['format' => 'json']));

        $response->assertOk();
        $response->assertJsonStructure(['text']);
        $this->assertStringContainsString('Participantes que respondieron: 1', $response->json('text'));
    }

    public function test_admin_can_crud_validation(): void
    {
        $user = User::factory()->create();
        $record = ConceptQualityValidation::query()->create([
            'name' => 'Original',
            'answer_p1' => 'Sí',
            'answer_p2' => 'Sí',
            'answer_p3' => 'Sí',
            'submitted_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('admin.concept_validations.index'))
            ->assertOk();

        $this->actingAs($user)
            ->put(route('admin.concept_validations.update', $record), [
                'name' => 'Actualizado',
                'answer_p1' => 'No',
                'answer_p2' => 'Sí',
                'answer_p3' => 'Sí',
            ])
            ->assertRedirect(route('admin.concept_validations.index'));

        $this->assertSame('Actualizado', $record->fresh()->name);
        $this->assertSame('No', $record->fresh()->answer_p1);

        $this->actingAs($user)
            ->delete(route('admin.concept_validations.destroy', $record))
            ->assertRedirect(route('admin.concept_validations.index'));

        $this->assertDatabaseMissing('concept_quality_validations', ['id' => $record->id]);
    }
}
