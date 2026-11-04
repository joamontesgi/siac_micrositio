@extends('layouts.unal')
@section('title', 'Iniciar sesión — SIAC')
@section('breadcrumb')
  @include('partials.siac.breadcrumb', ['crumbs' => ['Mi cuenta']])
@endsection
@section('content')
<section id="siac-auth" class="siac-account" aria-labelledby="auth-title">
  <div class="siac-account-intro">
    <p class="siac-account-label">SIAC · Mi cuenta</p>
    <h1 id="auth-title">Iniciar sesión</h1>
    <p>Accede con tu correo y contraseña.</p>
    <p class="text-muted">Si es tu primer ingreso a este sitio, crea una cuenta y confirma tu correo electrónico.</p>
    <a href="/">Volver al micrositio</a>
  </div>
  <div class="siac-account-panel">
    <p id="auth-status" role="status" aria-live="polite">Preparando el acceso…</p>
    <noscript>Activa JavaScript en tu navegador para iniciar sesión.</noscript>
    <form id="auth-form" hidden>
      <div class="form-group" id="auth-email-group">
        <label for="auth-email">Correo electrónico</label>
        <input class="form-control" id="auth-email" type="email" required autocomplete="username" maxlength="254">
      </div>
      <div class="form-group" id="auth-password-group">
        <label for="auth-password">Contraseña</label>
        <input class="form-control" id="auth-password" type="password" required autocomplete="current-password">
        <small id="auth-password-hint" hidden>Usa al menos 8 caracteres.</small>
      </div>
      <button type="submit" class="btn btn-primary" id="auth-submit">Ingresar</button>
    </form>
    <div id="auth-options" class="siac-account-options" hidden>
      <button type="button" class="btn btn-link" data-auth-mode="login">Iniciar sesión</button>
      <button type="button" class="btn btn-link" data-auth-mode="signup" id="auth-signup" hidden>Crear cuenta</button>
      <button type="button" class="btn btn-link" data-auth-mode="recovery">Olvidé mi contraseña</button>
    </div>
    <div id="auth-session" hidden>
      <p>Sesión iniciada como <strong id="auth-user"></strong>.</p>
      <a href="/" class="btn btn-primary">Ir al micrositio</a>
      <button type="button" class="btn btn-default" id="auth-logout">Cerrar sesión</button>
    </div>
  </div>
</section>
@endsection
