<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'functional_component', 'actua_phase']);

        $documents = Document::query()
            ->filter($filters)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.documents.index', [
            'documents' => $documents,
            'filters' => $filters,
            'components' => config('siac.functional_components'),
            'actuaPhases' => config('siac.actua_phases'),
        ]);
    }

    public function create(): View
    {
        return view('admin.documents.create', [
            'document' => new Document(['status' => 'vigente', 'document_type' => 'documento']),
            'components' => config('siac.functional_components'),
            'actuaPhases' => config('siac.actua_phases'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, true);
        $data['file_path'] = $this->storeUpload($request);
        $data['original_filename'] = $request->file('file')->getClientOriginalName();
        $data['document_type'] = $data['document_type'] ?? 'documento';
        $data['url'] = null;

        Document::query()->create($data);

        return redirect()
            ->route('admin.documents.index')
            ->with('status', 'Documento publicado correctamente.');
    }

    public function edit(Document $documento): View
    {
        return view('admin.documents.edit', [
            'document' => $documento,
            'components' => config('siac.functional_components'),
            'actuaPhases' => config('siac.actua_phases'),
        ]);
    }

    public function update(Request $request, Document $documento): RedirectResponse
    {
        $data = $this->validated($request, false);

        if ($request->hasFile('file')) {
            $this->deleteStoredFile($documento);
            $data['file_path'] = $this->storeUpload($request);
            $data['original_filename'] = $request->file('file')->getClientOriginalName();
            $data['url'] = null;
        }

        $documento->update($data);

        return redirect()
            ->route('admin.documents.index')
            ->with('status', 'Documento actualizado.');
    }

    public function destroy(Document $documento): RedirectResponse
    {
        $this->deleteStoredFile($documento);
        $documento->delete();

        return redirect()
            ->route('admin.documents.index')
            ->with('status', 'Documento eliminado.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request, bool $fileRequired): array
    {
        $componentKeys = array_keys(config('siac.functional_components'));
        $actuaKeys = array_keys(config('siac.actua_phases'));

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'functional_component' => ['nullable', 'string', 'in:'.implode(',', $componentKeys)],
            'actua_phase' => ['nullable', 'string', 'in:'.implode(',', $actuaKeys)],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['nullable', 'string', 'max:32'],
            'published_at' => ['nullable', 'date'],
            'file' => array_filter([
                $fileRequired ? 'required' : 'nullable',
                'file',
                'max:'.(int) config('siac.upload.max_kb'),
                'mimes:'.implode(',', config('siac.upload.mimes')),
            ]),
        ];

        $data = $request->validate($rules);

        if (empty($data['functional_component']) && empty($data['actua_phase'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'functional_component' => 'Indique un componente funcional y/o una etapa del ciclo ACTÚA.',
                'actua_phase' => 'Indique un componente funcional y/o una etapa del ciclo ACTÚA.',
            ]);
        }

        return $data;
    }

    protected function storeUpload(Request $request): string
    {
        return $request->file('file')->store('documentos/siac', 'public');
    }

    protected function deleteStoredFile(Document $document): void
    {
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
    }
}
