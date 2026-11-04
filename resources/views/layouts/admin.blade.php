@extends('layouts.unal')

@section('title', trim($__env->yieldContent('admin_title')).' — Admin SIAC')

@section('content')
  <div class="siac-admin-bar d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
      <a href="{{ route('admin.documents.index') }}" class="btn btn-sm btn-outline-primary">Documentos</a>
      <a href="{{ route('admin.concept_validations.index') }}" class="btn btn-sm btn-outline-primary">Validación concepto</a>
      <a href="{{ route('siac.documents.index') }}" class="btn btn-sm btn-link">Ver sitio público</a>
    </div>
    <div class="small">
      {{ auth()->user()->name }}
      <form method="post" action="{{ route('logout') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-secondary ml-2">Cerrar sesión</button>
      </form>
    </div>
  </div>

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @yield('admin_content')
@endsection
