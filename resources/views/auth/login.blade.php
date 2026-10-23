@extends('layouts.unal')

@section('title', 'Ingreso administrador — SIAC')

@section('breadcrumb')
  @include('partials.siac.breadcrumb', ['crumbs' => ['Administración', 'Ingreso']])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-5">
      <h1>Administración de documentos</h1>
      <p class="text-muted">Acceso restringido para cargar y clasificar documentos por componente funcional del SIAC.</p>

      <form method="post" action="{{ route('login') }}" class="card card-body">
        @csrf
        <div class="form-group">
          <label for="email">Correo</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                 value="{{ old('email') }}" required autofocus autocomplete="username">
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                 name="password" required autocomplete="current-password">
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1"
                 @checked(old('remember'))>
          <label class="form-check-label" for="remember">Recordarme</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
      </form>

      <p class="mt-3 small text-muted">
        <a href="{{ route('siac.home') }}">Volver al inicio del micrositio</a>
      </p>
    </div>
  </div>
@endsection
