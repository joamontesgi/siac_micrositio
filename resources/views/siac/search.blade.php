@extends('layouts.unal')

@section('title', 'Búsqueda — SIAC')

@section('breadcrumb')
  @include('partials.siac.breadcrumb', ['crumbs' => ['Búsqueda']])
@endsection

@section('content')
  <h1>Búsqueda global</h1>
  <form action="{{ route('siac.search') }}" method="get" class="siac-search-box">
    <input type="search" name="q" value="{{ $query }}" placeholder="Texto, documento, dependencia, proceso…" />
    <button type="submit" class="btn btn-default mt-2">Buscar</button>
  </form>

  @if ($query === '')
    <p>Ingrese un término para buscar en páginas y documentos.</p>
  @else
    <h2 class="siac-section-title">Páginas</h2>
    @if ($pageResults->isEmpty())
      <p>No se encontraron páginas.</p>
    @else
      <ul>
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
      <ul>
        @foreach ($documents as $doc)
          <li>
            <strong>{{ $doc->title }}</strong>
            @if ($doc->url)
              — <a href="{{ $doc->url }}" target="_blank" rel="noopener">Abrir</a>
            @endif
          </li>
        @endforeach
      </ul>
      <p><a href="{{ route('siac.documents.index', ['q' => $query]) }}">Ver todos en repositorio documental</a></p>
    @endif
  @endif
@endsection
