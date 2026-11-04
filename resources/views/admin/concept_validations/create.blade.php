@extends('layouts.admin')

@section('admin_title', 'Nueva validación')

@section('admin_content')
  <h1 class="h3 mb-3">Registrar respuesta manualmente</h1>

  <form method="post" action="{{ route('admin.concept_validations.store') }}">
    @csrf
    @include('admin.concept_validations._form')
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.concept_validations.index') }}" class="btn btn-link">Cancelar</a>
  </form>
@endsection
