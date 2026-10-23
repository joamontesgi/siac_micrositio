<div class="breadcrumb-class">
  Está en:
  <a href="{{ route('siac.home') }}" title="Inicio">Inicio</a>
  @foreach ($crumbs as $crumb)
    /<span>{{ $crumb }}</span>
  @endforeach
</div>
