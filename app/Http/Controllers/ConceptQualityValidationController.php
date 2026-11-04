<?php

namespace App\Http\Controllers;

use App\Models\ConceptQualityValidation;
use App\Support\ConceptQualityValidationActaSummary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConceptQualityValidationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre' => ['nullable', 'string', 'max:200'],
            'dependencia' => ['nullable', 'string', 'max:200'],
            'rol' => ['nullable', 'string', 'max:120'],
            'p1' => ['required', 'in:Sí,No'],
            'p2' => ['required', 'in:Sí,No'],
            'p3' => ['required', 'in:Sí,No'],
            'o1' => ['nullable', 'string', 'max:5000'],
            'o2' => ['nullable', 'string', 'max:5000'],
            'o3' => ['nullable', 'string', 'max:5000'],
        ]);

        $record = ConceptQualityValidation::query()->create([
            'name' => $data['nombre'] ?? null,
            'dependency' => $data['dependencia'] ?? null,
            'role' => $data['rol'] ?? null,
            'answer_p1' => $data['p1'],
            'answer_p2' => $data['p2'],
            'answer_p3' => $data['p3'],
            'observation_p1' => $data['o1'] ?? null,
            'observation_p2' => $data['o2'] ?? null,
            'observation_p3' => $data['o3'] ?? null,
            'submitted_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'row' => $record->toPublicRow(),
        ], 201);
    }

    public function summary(): JsonResponse
    {
        $rows = ConceptQualityValidation::query()
            ->orderBy('submitted_at')
            ->orderBy('id')
            ->get()
            ->map(fn (ConceptQualityValidation $r) => $r->toPublicRow())
            ->values()
            ->all();

        return response()->json([
            'total' => count($rows),
            'rows' => $rows,
        ]);
    }

    public function actaSummary(Request $request): JsonResponse|Response
    {
        $text = ConceptQualityValidationActaSummary::build();

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json(['text' => $text]);
        }

        $filename = 'resumen_acta_validacion_SIAC_'.now()->format('Y-m-d').'.txt';

        return response($text, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
