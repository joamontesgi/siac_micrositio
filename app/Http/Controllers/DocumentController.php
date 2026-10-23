<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'functional_component', 'actua_phase']);
        $components = config('siac.functional_components');
        $actuaPhases = config('siac.actua_phases');

        $documents = Document::query()
            ->filter($filters)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get();

        $byComponent = [];
        foreach (array_keys($components) as $key) {
            $byComponent[$key] = $documents->where('functional_component', $key)->values();
        }

        $byActua = [
            'todas' => $documents->filter(fn ($doc) => $doc->actua_phase !== null)->values(),
        ];
        foreach ($actuaPhases as $key => $meta) {
            if (! empty($meta['is_all'])) {
                continue;
            }
            $byActua[$key] = $documents->where('actua_phase', $key)->values();
        }

        $uncategorized = $documents
            ->filter(fn ($doc) => $doc->functional_component === null && $doc->actua_phase === null)
            ->values();

        return view('siac.documents.index', [
            'documents' => $documents,
            'byComponent' => $byComponent,
            'byActua' => $byActua,
            'uncategorized' => $uncategorized,
            'filters' => $filters,
            'components' => $components,
            'actuaPhases' => $actuaPhases,
        ]);
    }

    public function download(Document $documento): BinaryFileResponse
    {
        if ($documento->file_path && Storage::disk('public')->exists($documento->file_path)) {
            $name = $documento->original_filename ?: basename($documento->file_path);

            return Storage::disk('public')->download($documento->file_path, $name);
        }

        $relative = $documento->publicRelativePath();
        if ($relative && is_file(public_path($relative))) {
            $name = $documento->original_filename ?: basename($relative);

            return response()->download(public_path($relative), $name);
        }

        abort(404, 'Archivo no encontrado en el servidor.');
    }
}
