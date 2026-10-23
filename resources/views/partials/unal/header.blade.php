<header id="unalTop">
  <div class="logo">
    <a href="https://unal.edu.co">
      <svg width="93%" height="93%">
        <image xlink:href="{{ asset('unal/images/escudoUnal.svg') }}" width="100%" height="100%" class="hidden-print" alt="Escudo de la Universidad Nacional de Colombia" />
      </svg>
      <img src="{{ asset('unal/images/escudoUnal_black.png') }}" class="d-none d-print-block" alt="Escudo de la Universidad Nacional de Colombia" />
    </a>
  </div>
  <div class="seal">
    <img class="hidden-print" alt="Escudo de la República de Colombia" src="{{ asset('unal/images/sealColombia.png') }}" width="66" height="66" />
    <img class="d-none d-print-block" alt="Escudo de la República de Colombia" src="{{ asset('unal/images/sealColombia_black.png') }}" width="66" height="66" />
  </div>

  <div class="firstMenu d-none d-md-block">
    <div class="content-fluid">
      <nav class="navbar navbar-expand-md nav navbar-dark">
        <div class="collapse navbar-collapse navbar-default" id="navbarSupportedContent">
          <nav id="profiles">
            <ul class="mr-auto nav justify-content-end">
              <li class="nav-item"><a href="https://aspirantes.unal.edu.co">Aspirantes</a></li>
              <li class="nav-item"><a href="https://estudiantes.unal.edu.co">Estudiantes</a></li>
              <li class="nav-item"><a href="https://egresados.unal.edu.co">Egresados</a></li>
              <li class="nav-item"><a href="https://docentes.unal.edu.co">Docentes</a></li>
              <li class="nav-item"><a href="https://administrativos.unal.edu.co">Administrativos</a></li>
            </ul>
          </nav>
        </div>
        <ul class="socialLinks d-none d-md-block">
          <li><a href="https://www.facebook.com/UNALOficial" target="_blank" class="facebook" title="Página oficial en Facebook"></a></li>
          <li><a href="https://twitter.com/UNALOficial" target="_blank" class="twitter" title="Cuenta oficial en Twitter"></a></li>
          <li><a href="https://www.youtube.com/channel/UCnE6Zj2llVxcvL5I38B0Ceg" target="_blank" class="youtube" title="Canal oficial de Youtube"></a></li>
        </ul>
        <div class="collapse btn-group languageMenu d-none d-md-block">
          <div class="btn btn-default dropdown-toggle" data-toggle="dropdown">es<span class="caret"></span></div>
          <ul class="dropdown-menu dropdown-menu-right">
            <li><a href="#">EN - English</a></li>
          </ul>
        </div>
      </nav>
    </div>
  </div>

  <div id="bs-navbar" class="navigation d-none d-md-block">
    <div class="site-url" id="subdominio">
      <a href="{{ route('siac.home') }}">{{ config('siac.site_url_label') }}</a>
    </div>
    <div class="buscador" id="buscador">
      <form action="{{ route('siac.search') }}" method="get" class="siac-search-inline">
        <input type="search" name="q" placeholder="Buscar en el SIAC…" aria-label="Buscar" value="{{ request('q') }}" />
      </form>
    </div>
    <div class="navbar-dark mainMenu" id="main_menu_container">
      @foreach (config('siac.menu') as $item)
        @if (! empty($item['children']))
          <div class="btn-group">
            <div class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              {{ $item['label'] }}<span class="caret"></span>
            </div>
            <ul class="dropdown-menu">
              @foreach ($item['children'] as $child)
                <li>
                  @include('partials.siac.menu-link', ['item' => $child])
                </li>
              @endforeach
            </ul>
          </div>
        @else
          <div class="btn-group">
            @php
              $href = isset($item['route'])
                ? (isset($item['params']) ? route($item['route'], $item['params']) : route($item['route']))
                : '#';
            @endphp
            <a href="{{ $href }}" class="btn btn-default">{{ $item['label'] }}</a>
          </div>
        @endif
      @endforeach
      <div class="btn-group menu_sedes">
        <div class="btn btn-default dropdown-toggle" data-toggle="dropdown">Sedes<span class="caret"></span></div>
        <ul class="dropdown-menu" id="sedes">
          <li><a class="dropdown-item" href="https://bogota.unal.edu.co" target="_blank">Bogotá</a><span class="caret-right"></span></li>
          <li><a class="dropdown-item" href="https://medellin.unal.edu.co" target="_blank">Medellín</a><span class="caret-right"></span></li>
          <li><a class="dropdown-item" href="https://manizales.unal.edu.co" target="_blank">Manizales</a><span class="caret-right"></span></li>
          <li><a class="dropdown-item" href="https://palmira.unal.edu.co" target="_blank">Palmira</a><span class="caret-right"></span></li>
        </ul>
      </div>
    </div>
  </div>

  <nav class="navbar navbar-light light-blue lighten-4 main_menu">
    <a class="navbar-brand d-block d-md-none" href="{{ route('siac.home') }}">SIAC</a>
    <button class="navbar-toggler collapsed d-block d-md-none" type="button" data-toggle="collapse" data-target="#navbar_content" aria-controls="navbar_content" aria-expanded="false" aria-label="Toggle navigation" id="btn_hamburguer"></button>
    <div class="collapse navbar-collapse" id="navbar_content">
      <div class="site-url" id="container_subdominio_mobil">
        <a href="{{ route('siac.home') }}">{{ config('siac.site_url_label') }}</a>
      </div>
      <form action="{{ route('siac.search') }}" method="get" class="buscador px-3 pb-2">
        <input type="search" name="q" class="form-control" placeholder="Buscar…" value="{{ request('q') }}" />
      </form>
      <ul class="navbar-nav px-3">
        @foreach (config('siac.menu') as $item)
          @if (! empty($item['children']))
            <li class="nav-item"><strong class="nav-link">{{ $item['label'] }}</strong></li>
            @foreach ($item['children'] as $child)
              <li class="nav-item pl-3">
                @include('partials.siac.menu-link', ['item' => $child])
              </li>
            @endforeach
          @else
            <li class="nav-item">
              @include('partials.siac.menu-link', ['item' => $item])
            </li>
          @endif
        @endforeach
      </ul>
    </div>
  </nav>
</header>
