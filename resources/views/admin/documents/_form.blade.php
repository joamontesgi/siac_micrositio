@php
  /** @var \App\Models\Document $document */
  $isEdit = $document->exists;
@endphp

<div class="form-group">
  <label for="title">Nombre del documento <span class="text-danger">*</span></label>
  <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $document->title) }}" required maxlength="255">
</div>

<p class="small text-muted">
  Clasifique el documento con al menos una opción: <strong>componente funcional</strong> y/o <strong>etapa ACTÚA</strong>
  (puede elegir ambas si aplica).
</p>

<div class="form-row">
  <div class="form-group col-md-6">
    <label for="functional_component">Componente funcional del SIAC</label>
    <select class="form-control @error('functional_component') is-invalid @enderror" id="functional_component" name="functional_component">
      <option value="">— Ninguno —</option>
      @foreach ($components as $key => $meta)
        <option value="{{ $key }}" @selected(old('functional_component', $document->functional_component) === $key)>
          {{ $meta['number'] }}. {{ $meta['label'] }}
        </option>
      @endforeach
    </select>
    @error('functional_component')
      <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
  </div>
  <div class="form-group col-md-6">
    <label for="actua_phase">Etapa del ciclo ACTÚA</label>
    <select class="form-control @error('actua_phase') is-invalid @enderror" id="actua_phase" name="actua_phase">
      <option value="">— Ninguna —</option>
      @foreach ($actuaPhases as $key => $meta)
        <option value="{{ $key }}" @selected(old('actua_phase', $document->actua_phase) === $key)>
          @if (! empty($meta['is_all']))
            {{ $meta['short'] }} — {{ $meta['label'] }}
          @else
            {{ $meta['letter'] }}. {{ $meta['label'] }}
          @endif
        </option>
      @endforeach
    </select>
    @error('actua_phase')
      <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
  </div>
</div>

<div class="form-group">
  <label for="description">Descripción</label>
  <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $document->description) }}</textarea>
</div>

<div class="form-row">
  <div class="form-group col-md-6">
    <label for="published_at">Fecha de publicación</label>
    <input type="date" class="form-control" id="published_at" name="published_at"
           value="{{ old('published_at', optional($document->published_at)->format('Y-m-d')) }}">
  </div>
  <div class="form-group col-md-6">
    <label for="status">Estado</label>
    <input type="text" class="form-control" id="status" name="status" value="{{ old('status', $document->status ?? 'vigente') }}">
  </div>
</div>

<div class="form-group">
  <label for="file">
    Archivo @if (! $isEdit)<span class="text-danger">*</span>@endif
  </label>
  <input type="file" class="form-control-file" id="file" name="file" @if (! $isEdit) required @endif>
  <small class="form-text text-muted">
    PDF, Word, Excel, PowerPoint u otros formatos admitidos (máx. {{ (int) config('siac.upload.max_kb') / 1024 }} MB).
  </small>
  @if ($isEdit && $document->file_path)
    <p class="small mt-2 mb-0">
      Archivo actual:
      <a href="{{ $document->downloadHref() }}" target="_blank" rel="noopener">{{ $document->original_filename ?? 'Descargar' }}</a>
      (deje vacío para conservarlo)
    </p>
  @endif
</div>
