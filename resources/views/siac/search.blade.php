@extends('layouts.unal')

@section('title', 'Búsqueda — SIAC')

@section('breadcrumb')
  @include('partials.siac.breadcrumb', ['crumbs' => ['Búsqueda']])
@endsection

@section('content')
  <article class="siac-content siac-page">
    <header class="siac-page-header">
      <p class="siac-page-eyebrow">Micrositio</p>
      <h1>Búsqueda</h1>
      <p class="lead siac-page-lead">Encuentre páginas del SIAC y documentos publicados.</p>
    </header>

    <form action="{{ route('siac.search') }}" method="get" class="siac-search-form" role="search">
      <label for="siac-search-q" class="siac-search-form-label">Término de búsqueda</label>
      <div class="siac-search-form-row">
        <input type="search" class="form-control" id="siac-search-q" name="q" value="{{ $query }}"
          placeholder="Calidad, indicadores, acuerdo…" autocomplete="off" />
        <button type="submit" class="btn btn-primary">Buscar</button>
      </div>
    </form>

    @if ($query === '')
      <p class="text-muted mb-0">Ingrese un término para buscar en páginas y documentos.</p>
    @else
      <h2 class="siac-section-title">Páginas</h2>
      @if ($pageResults->isEmpty())
        <p>No se encontraron páginas.</p>
      @else
        <ul class="siac-search-results">
          @foreach ($pageResults as $hit)
            <li>
              <a href="{{ route('siac.page', ['slug' => $hit['slug']]) }}">{{ $hit['page']['title'] }}</a>
            </li>
          @endforeach
        </ul>
      @endif

      <h2 class="siac-section-title">Documentos</h2>
      @if ($documents->isEmpty())
        <p>No se encontraron documentos.</p>
      @else
        <ul class="siac-search-results">
          @foreach ($documents as $doc)
            <li>
              <span class="siac-search-doc-title">{{ $doc->title }}</span>
              @if ($doc->url)
                <a href="{{ $doc->url }}" class="siac-search-doc-action" target="_blank" rel="noopener">Abrir</a>
              @endif
            </li>
          @endforeach
        </ul>
        <p class="mb-0">
          <a href="{{ route('siac.documents.index', ['q' => $query]) }}">Ver todos en repositorio documental →</a>
        </p>
      @endif
    @endif
  </article>
@endsection
