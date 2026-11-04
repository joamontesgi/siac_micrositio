@extends('layouts.unal')

@section('title', 'Inicio — SIAC')

@push('body-class')
siac-page-home siac-page-campana
@endpush

@push('head')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Asap:ital,wght@0,400;0,600;0,700;1,400&family=Ranchers&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/siac-campana.css') }}?v={{ filemtime(public_path('css/siac-campana.css')) }}">
@endpush

@section('breadcrumb')
@endsection

@section('content')
  @include('siac.home._content')

  <div class="siac-campana-wrap" id="siac-campana">
    @include('siac.campana._body')
    @include('siac.campana._tools')
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('js/siac-campana-data.js') }}?v={{ filemtime(public_path('js/siac-campana-data.js')) }}" defer></script>
@endpush
