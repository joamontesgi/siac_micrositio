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
      <form id="upload-form" class="siac-upload" enctype="multipart/form-data" aria-labelledby="upload-title">
        <h2 id="upload-title">Subir documento</h2>
        <p class="text-muted">El documento queda publicado en <a href="/documentos">Documentos</a>. Tamaño máximo: 4 MB.</p>
        <div class="form-group">
          <label for="upload-file">Archivo <span aria-hidden="true">*</span></label>
          <input class="form-control" id="upload-file" name="file" type="file" required
                 accept=".pdf,.xlsx,.xls,.docx,.doc,.pptx,.ppt,.csv,.txt,.png,.jpg,.jpeg">
        </div>
        <div class="form-group">
          <label for="upload-title-input">Nombre del documento <span aria-hidden="true">*</span></label>
          <input class="form-control" id="upload-title-input" name="title" type="text" required maxlength="255">
        </div>
        <div class="form-group">
          <label for="upload-description">Descripción</label>
          <textarea class="form-control" id="upload-description" name="description" rows="3" maxlength="2000"></textarea>
        </div>
        <div class="form-group">
          <label for="upload-type">Tipo de documento</label>
          <select class="form-control" id="upload-type" name="documentType">
            <option value="documento">Documento</option>
            <option value="informe_pdf">Informe</option>
            <option value="presentacion_pdf">Presentación</option>
            <option value="matriz_excel">Matriz o tabla de datos</option>
            <option value="otro">Otro</option>
          </select>
        </div>
        <div class="siac-upload-row">
          <div class="form-group">
            <label for="upload-year">Año</label>
            <input class="form-control" id="upload-year" name="year" type="number" min="1900" max="2100" step="1">
          </div>
          <div class="form-group">
            <label for="upload-version">Versión</label>
            <input class="form-control" id="upload-version" name="version" type="text" maxlength="32">
          </div>
        </div>
        <button type="submit" class="btn btn-primary" id="upload-submit">Subir documento</button>
        <p id="upload-status" role="status" aria-live="polite"></p>
      </form>
    </div>
  </div>
</section>
@endsection
