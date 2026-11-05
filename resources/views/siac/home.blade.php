@extends('layouts.unal')

@section('title', 'Inicio — SIAC')

@push('body-class')
siac-page-home
@endpush

@section('breadcrumb')
@endsection

@section('content')
  @include('siac.home._content')
@endsection
