@extends('layouts.admin')

@section('admin_title', 'Editar validación')

@section('admin_content')
  <h1 class="h3 mb-3">Editar respuesta</h1>

  <form method="post" action="{{ route('admin.concept_validations.update', $validation) }}">
    @csrf
    @method('PUT')
    @include('admin.concept_validations._form')
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="{{ route('admin.concept_validations.index') }}" class="btn btn-link">Cancelar</a>
  </form>
@endsection
