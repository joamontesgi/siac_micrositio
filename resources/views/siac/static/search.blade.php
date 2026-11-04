@extends('layouts.unal')
@section('title', 'Búsqueda — SIAC')
@section('breadcrumb')
  @include('partials.siac.breadcrumb', ['crumbs' => ['Búsqueda']])
@endsection
@section('content')
  <article class="siac-content siac-page">
    <header class="siac-page-header"><h1>Búsqueda</h1><p class="lead">Encuentre páginas y documentos del SIAC.</p></header>
    <form action="/buscar" method="get" class="siac-search-form" role="search">
      <label for="siac-search-q">Término de búsqueda</label>
      <div class="siac-search-form-row">
        <input type="search" id="siac-search-q" name="q" class="form-control" placeholder="Calidad, indicadores, acuerdo…">
        <button class="btn btn-primary" type="submit">Buscar</button>
      </div>
    </form>
    <p id="search-status" role="status" aria-live="polite">Ingrese un término para buscar en páginas y documentos.</p>
    <ul id="search-results" class="siac-search-results"></ul>
    <noscript>Active JavaScript para buscar. También puede consultar las páginas desde el menú y la sección de documentos.</noscript>
  </article>
@endsection
@push('scripts')
  <script src="/js/siac-search.js" defer></script>
@endpush
