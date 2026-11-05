{{--
  Layout autónomo de la maqueta ayuda (sin cabecera/pie UNAL).
  El inicio del sitio usa layouts.unal + siac/campana/index.blade.php.
--}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'SIAC')</title>
  <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;600;700&family=Ranchers&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/siac-campana.css') }}?v={{ filemtime(public_path('css/siac-campana.css')) }}">
  @stack('head')
</head>
<body>
  @yield('content')
  @stack('scripts')
</body>
</html>
