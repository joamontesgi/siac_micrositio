<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConceptQualityValidation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ConceptQualityValidationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['q']);

        $validations = ConceptQualityValidation::query()
            ->filter($filters)
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.concept_validations.index', [
            'validations' => $validations,
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.concept_validations.create', [
            'validation' => new ConceptQualityValidation([
                'answer_p1' => 'Sí',
                'answer_p2' => 'Sí',
                'answer_p3' => 'Sí',
                'submitted_at' => now(),
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        ConceptQualityValidation::query()->create($data);

        return redirect()
            ->route('admin.concept_validations.index')
            ->with('status', 'Respuesta registrada.');
    }

    public function edit(ConceptQualityValidation $validacion): View
    {
        return view('admin.concept_validations.edit', [
            'validation' => $validacion,
        ]);
    }

    public function update(Request $request, ConceptQualityValidation $validacion): RedirectResponse
    {
        $validacion->update($this->validated($request));

        return redirect()
            ->route('admin.concept_validations.index')
            ->with('status', 'Respuesta actualizada.');
    }

    public function destroy(ConceptQualityValidation $validacion): RedirectResponse
    {
        $validacion->delete();

        return redirect()
            ->route('admin.concept_validations.index')
            ->with('status', 'Respuesta eliminada.');
    }

    public function destroyAll(Request $request): RedirectResponse
    {
        $request->validate(['confirm' => ['required', 'in:VACIAR']]);
        ConceptQualityValidation::query()->delete();

        return redirect()
            ->route('admin.concept_validations.index')
            ->with('status', 'Se eliminaron todas las respuestas de validación.');
    }

    public function export(): StreamedResponse
    {
        $filename = 'validacion_concepto_calidad_SIAC_'.now()->format('Y-m-d_His').'.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, [
                'Marca de tiempo', 'Nombre', 'Dependencia', 'Estamento',
                'P1 Pertinencia', 'Obs P1', 'P2 Integralidad', 'Obs P2',
                'P3 Capacidad orientadora', 'Obs P3',
            ], ';');

            ConceptQualityValidation::query()
                ->orderBy('submitted_at')
                ->orderBy('id')
                ->cursor()
                ->each(function (ConceptQualityValidation $row) use ($out) {
                    $r = $row->toPublicRow();
                    fputcsv($out, [
                        $r['ts'], $r['nombre'], $r['dependencia'], $r['rol'],
                        $r['p1'], $r['o1'], $r['p2'], $r['o2'], $r['p3'], $r['o3'],
                    ], ';');
                });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $content = file_get_contents($request->file('csv')->getRealPath());
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content ?? '') ?? '';
        $lines = preg_split('/\r\n|\r|\n/', $content);
        $imported = 0;

        foreach ($lines as $index => $line) {
            if ($line === '') {
                continue;
            }
            $cols = str_getcsv($line, ';');
            if ($index === 0 && isset($cols[0]) && stripos($cols[0], 'marca') !== false) {
                continue;
            }
            if (count($cols) < 10) {
                continue;
            }

            ConceptQualityValidation::query()->create([
                'submitted_at' => $cols[0] ?: now(),
                'name' => $cols[1] ?: null,
                'dependency' => $cols[2] ?: null,
                'role' => $cols[3] ?: null,
                'answer_p1' => in_array($cols[4], ['Sí', 'No'], true) ? $cols[4] : 'Sí',
                'observation_p1' => $cols[5] ?: null,
                'answer_p2' => in_array($cols[6], ['Sí', 'No'], true) ? $cols[6] : 'Sí',
                'observation_p2' => $cols[7] ?: null,
                'answer_p3' => in_array($cols[8], ['Sí', 'No'], true) ? $cols[8] : 'Sí',
                'observation_p3' => $cols[9] ?: null,
            ]);
            $imported++;
        }

        return redirect()
            ->route('admin.concept_validations.index')
            ->with('status', "Importación completada: {$imported} fila(s).");
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:200'],
            'dependency' => ['nullable', 'string', 'max:200'],
            'role' => ['nullable', 'string', 'max:120'],
            'answer_p1' => ['required', 'in:Sí,No'],
            'answer_p2' => ['required', 'in:Sí,No'],
            'answer_p3' => ['required', 'in:Sí,No'],
            'observation_p1' => ['nullable', 'string', 'max:5000'],
            'observation_p2' => ['nullable', 'string', 'max:5000'],
            'observation_p3' => ['nullable', 'string', 'max:5000'],
            'submitted_at' => ['nullable', 'date'],
        ]);

        $data['submitted_at'] = $data['submitted_at'] ?? now();

        return $data;
    }
}
