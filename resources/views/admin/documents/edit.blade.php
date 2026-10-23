@extends('layouts.admin')

@section('admin_title', 'Editar documento')

@section('breadcrumb')
  @include('partials.siac.breadcrumb', ['crumbs' => ['Administración', 'Documentos', 'Editar']])
@endsection

@section('admin_content')
  <h1 class="h3">Editar documento</h1>
  <form method="post" action="{{ route('admin.documents.update', $document) }}" enctype="multipart/form-data" class="card card-body">
    @csrf
    @method('PUT')
    @include('admin.documents._form')
    <button type="submit" class="btn btn-primary">Guardar cambios</button>
    <a href="{{ route('admin.documents.index') }}" class="btn btn-link">Cancelar</a>
  </form>
@endsection
