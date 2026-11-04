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
    <ul class="siac-home-doc-list">
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
