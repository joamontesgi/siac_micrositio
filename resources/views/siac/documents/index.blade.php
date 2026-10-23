@extends('layouts.unal')

@section('title', 'Documentos — SIAC')

@section('breadcrumb')
  @include('partials.siac.breadcrumb', ['crumbs' => ['Documentos']])
@endsection

@section('content')
  <h1>Documentos del SIAC</h1>
  <p>
    Repositorio por
    <a href="{{ route('siac.page', ['slug' => 'componentes-funcionales-siac']) }}">componentes funcionales</a>
    y por
    <a href="{{ route('siac.page', ['slug' => 'ciclo-actua']) }}">ciclo ACTÚA</a>.
  </p>

  @auth
    <p class="mb-3">
      <a href="{{ route('admin.documents.index') }}" class="btn btn-sm btn-primary">Administrar documentos</a>
    </p>
  @else
    <p class="small text-muted mb-3">
      <a href="{{ route('login') }}">Ingreso administrador</a> (carga y clasificación de documentos).
    </p>
  @endauth

  @php
    $hasActiveFilters = ! empty($filters['q']) || ! empty($filters['functional_component']) || ! empty($filters['actua_phase']);
  @endphp

  <form method="get" action="{{ route('siac.documents.index') }}" class="siac-doc-filters mb-4" aria-label="Filtrar documentos">
    <p class="siac-doc-filters-heading">Buscar y filtrar</p>
    <div class="siac-doc-filters-grid">
      <div class="siac-doc-field siac-doc-field-search">
        <label for="q" class="siac-doc-filters-label">Buscar</label>
        <input type="search" class="form-control siac-doc-input" id="q" name="q"
               value="{{ $filters['q'] ?? '' }}"
               placeholder="Título, descripción o nombre de archivo…"
               autocomplete="off">
      </div>
      <div class="siac-doc-field">
        <label for="functional_component" class="siac-doc-filters-label">Componente funcional</label>
        <select class="form-control siac-doc-input siac-doc-select" id="functional_component" name="functional_component">
          <option value="">Todos los componentes</option>
          @foreach ($components as $key => $meta)
            <option value="{{ $key }}" @selected(($filters['functional_component'] ?? '') === $key)>
              {{ $meta['number'] }}. {{ $meta['short'] }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="siac-doc-field">
        <label for="actua_phase" class="siac-doc-filters-label">Ciclo ACTÚA</label>
        <select class="form-control siac-doc-input siac-doc-select" id="actua_phase" name="actua_phase">
          <option value="">Todas las fases</option>
          @foreach ($actuaPhases as $key => $meta)
            <option value="{{ $key }}" @selected(($filters['actua_phase'] ?? '') === $key)>
              {{ $meta['letter'] }}. {{ $meta['short'] }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="siac-doc-field siac-doc-filters-actions">
        <span class="siac-doc-filters-label siac-doc-filters-label-spacer" aria-hidden="true">&nbsp;</span>
        <div class="siac-doc-filters-buttons">
          <button type="submit" class="btn btn-primary siac-doc-filters-submit">Filtrar</button>
          @if ($hasActiveFilters)
            <a href="{{ route('siac.documents.index') }}" class="btn btn-outline-primary siac-doc-filters-clear">Limpiar</a>
          @endif
        </div>
      </div>
    </div>
  </form>

  @php
    $showAll = empty($filters['functional_component']) && empty($filters['actua_phase']) && empty($filters['q']);
  @endphp

  @if ($showAll)
    <ul class="nav nav-pills mb-3" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#view-componentes" role="tab">Componentes funcionales</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#view-actua" role="tab">Ciclo ACTÚA</a>
      </li>
      @if ($uncategorized->isNotEmpty())
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#view-fuentes" role="tab">Fuentes</a>
        </li>
      @endif
    </ul>

    <div class="tab-content">
      <div class="tab-pane fade show active" id="view-componentes" role="tabpanel">
        <ul class="nav nav-tabs nav-fill mb-3" role="tablist">
          @foreach ($components as $key => $meta)
            <li class="nav-item">
              <a class="nav-link @if ($loop->first) active @endif" data-toggle="tab" href="#docs-{{ $key }}" role="tab">
                {{ $meta['number'] }}. {{ $meta['short'] }}
                <span class="badge badge-light">{{ $byComponent[$key]->count() }}</span>
              </a>
            </li>
          @endforeach
        </ul>
        <div class="tab-content">
          @foreach ($components as $key => $meta)
            <div class="tab-pane fade @if ($loop->first) show active @endif" id="docs-{{ $key }}" role="tabpanel">
              <h2 class="h5 text-primary">{{ $meta['label'] }}</h2>
              @include('siac.documents._list', ['items' => $byComponent[$key]])
            </div>
          @endforeach
        </div>
      </div>

      <div class="tab-pane fade" id="view-actua" role="tabpanel">
        <ul class="nav nav-tabs nav-fill mb-3" role="tablist">
          @foreach ($actuaPhases as $key => $meta)
            <li class="nav-item">
              <a class="nav-link @if ($loop->first) active @endif" data-toggle="tab" href="#docs-actua-{{ $key }}" role="tab">
                <span class="siac-actua-tab-letter">{{ $meta['letter'] }}</span>
                {{ $meta['short'] }}
                <span class="badge badge-light">{{ ($byActua[$key] ?? collect())->count() }}</span>
              </a>
            </li>
          @endforeach
        </ul>
        <div class="tab-content">
          @foreach ($actuaPhases as $key => $meta)
            <div class="tab-pane fade @if ($loop->first) show active @endif" id="docs-actua-{{ $key }}" role="tabpanel">
              <h2 class="h5 text-primary">
                @if (! empty($meta['is_all']))
                  {{ $meta['label'] }}
                @else
                  {{ $meta['letter'] }}. {{ $meta['label'] }}
                @endif
              </h2>
              @include('siac.documents._list', ['items' => $byActua[$key] ?? collect()])
            </div>
          @endforeach
        </div>
      </div>

      @if ($uncategorized->isNotEmpty())
        <div class="tab-pane fade" id="view-fuentes" role="tabpanel">
          <h2 class="h5 text-primary">Fuentes de referencia del micrositio</h2>
          @include('siac.documents._list', ['items' => $uncategorized])
        </div>
      @endif
    </div>
  @else
    <h2 class="h5">Resultados ({{ $documents->count() }})</h2>
    @include('siac.documents._list', ['items' => $documents])
  @endif
@endsection
