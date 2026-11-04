<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <link rel="shortcut icon" href="{{ asset('unal/images/favicon.ico') }}" type="image/x-icon" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.5, user-scalable=yes" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="author" content="OMD - Unimedios / SIAC UNAL">
  <link rel="stylesheet" type="text/css" href="{{ asset('unal/css/frontend.css') }}" media="all" />
  <link rel="stylesheet" type="text/css" href="{{ asset('unal/css/accesibilidad.css') }}" media="all" />
  <link rel="stylesheet" type="text/css" href="{{ asset('unal/css/bootstrap.min.css') }}" media="all" />
  <link rel="stylesheet" type="text/css" href="{{ asset('unal/css/bootstrap-theme.min.css') }}" media="all" />
  <link rel="stylesheet" type="text/css" href="{{ asset('unal/css/reset.css') }}" media="all" />
  <link rel="stylesheet" type="text/css" href="{{ asset('unal/css/unal.css') }}" media="all" />
  <link rel="stylesheet" type="text/css" href="{{ asset('unal/css/base.css') }}" media="all" />
  <link rel="stylesheet" type="text/css" href="{{ asset('unal/css/tablet.css') }}" media="all" />
  <link rel="stylesheet" type="text/css" href="{{ asset('unal/css/phone.css') }}" media="all" />
  <link rel="stylesheet" type="text/css" href="{{ asset('unal/css/small.css') }}" media="all" />
  <link rel="stylesheet" type="text/css" href="{{ asset('unal/css/printer.css') }}" media="print" />
  <link rel="stylesheet" type="text/css" href="{{ asset('css/siac.css') }}?v={{ filemtime(public_path('css/siac.css')) }}" media="all" />
  <script src="{{ asset('unal/js/jquery.js') }}" type="text/javascript"></script>
  <script src="{{ asset('unal/js/unal.js') }}" type="text/javascript"></script>
  <script src="{{ asset('unal/js/accesibilidad.js') }}" type="text/javascript"></script>
  <script src="{{ asset('unal/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
  <title>@yield('title', config('siac.site_name')) — Universidad Nacional de Colombia</title>
  @stack('head')
</head>
<body class="siac-site @stack('body-class')">
  @include('partials.unal.services')
  @include('partials.unal.header')
  @include('partials.unal.accessibility')

  <main class="detalle">
    <div class="container-fluid">
      @yield('breadcrumb')
      @yield('content')
    </div>
  </main>

  @include('partials.unal.footer')

  <script src="{{ asset('unal/js/gsap-public/minified/gsap.min.js') }}"></script>
  <script src="{{ asset('unal/js/gsap-public/minified/ScrollToPlugin.min.js') }}"></script>
  <script src="{{ asset('unal/js/gsap-public/minified/ScrollTrigger.min.js') }}"></script>
  <script src="{{ asset('unal/js/simpleParallax.min.js') }}"></script>
  <script src="{{ asset('unal/js/scripts.js') }}"></script>
  @stack('scripts')
</body>
</html>
