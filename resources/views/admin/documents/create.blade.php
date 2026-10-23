@extends('layouts.admin')

@section('admin_title', 'Nuevo documento')

@section('breadcrumb')
  @include('partials.siac.breadcrumb', ['crumbs' => ['Administración', 'Documentos', 'Nuevo']])
@endsection

@section('admin_content')
  <h1 class="h3">Nuevo documento</h1>
  <form method="post" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" class="card card-body">
    @csrf
    @include('admin.documents._form')
    <button type="submit" class="btn btn-primary">Publicar documento</button>
    <a href="{{ route('admin.documents.index') }}" class="btn btn-link">Cancelar</a>
  </form>
@endsection
