@extends('layouts.unal')
@section('title', 'Documentos — SIAC')
@section('breadcrumb')
  @include('partials.siac.breadcrumb', ['crumbs' => ['Documentos']])
@endsection
@section('content')
  <article class="siac-content siac-page">
    <header class="siac-page-header">
      <p class="siac-page-eyebrow">Recursos públicos</p>
      <h1>Documentos</h1>
      <p class="lead">Fuentes de referencia del micrositio SIAC.</p>
    </header>
    <p class="siac-doc-source" data-siac-doc-status role="status" aria-live="polite"></p>
    <ul class="siac-home-doc-list" data-siac-documents>
      @foreach ($documents as $document)
        <li class="siac-home-doc-item">
          <div class="siac-home-doc-body">
            <h2 class="siac-home-doc-title">{{ $document->title }}</h2>
            <p>{{ $document->description }}</p>
          </div>
          <a class="siac-btn siac-btn-primary siac-btn-sm" href="{{ $document->downloadHref() }}">Descargar</a>
        </li>
      @endforeach
    </ul>
  </article>
@endsection
@push('scripts')
  <script src="{{ asset('js/siac-documents.js') }}" defer></script>
@endpush
