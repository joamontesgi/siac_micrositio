<div class="siac-home">
  <header class="siac-home-hero">
    <p class="siac-home-kicker">Universidad Nacional de Colombia</p>
    <h1 class="siac-home-title">SIAC</h1>
    <p class="siac-home-subtitle">Sistema Interno de Aseguramiento de la Calidad</p>
    @if (! empty($homeLead))
      <p class="siac-home-lead">{{ $homeLead }}</p>
    @endif
    <div class="siac-home-hero-actions">
      <a href="{{ route('siac.page', ['slug' => 'siac-al-detalle']) }}" class="siac-btn siac-btn-primary">SIAC al detalle</a>
      <a href="{{ route('siac.documents.index') }}" class="siac-btn siac-btn-outline">Documentos</a>
      <a href="{{ route('siac.page', ['slug' => 'matriz-indicadores']) }}" class="siac-btn siac-btn-outline">Indicadores</a>
    </div>
  </header>

  <nav class="siac-home-nav" aria-label="Accesos rápidos">
    @foreach (config('siac.quick_links') as $link)
      @php
        $href = isset($link['params']) ? route($link['route'], $link['params']) : route($link['route']);
      @endphp
      <a href="{{ $href }}" class="siac-home-nav-link">{{ $link['label'] }}</a>
    @endforeach
  </nav>

  <div class="row siac-home-columns">
    <div class="col-lg-5 mb-4">
      <section class="siac-home-panel">
        <h2 class="siac-home-panel-heading">Documentos y recursos</h2>
        <p class="siac-home-panel-intro">
          Clasificados por componentes funcionales y ciclo ACTÚA.
          @auth
            <a href="{{ route('admin.documents.index') }}" class="siac-home-inline-link">Administrar</a>
          @endauth
        </p>

        @if ($recentDocuments->isEmpty())
          <p class="siac-home-muted">Aún no hay documentos publicados.</p>
          <a href="{{ route('siac.documents.index') }}" class="siac-btn siac-btn-primary siac-btn-sm">Ir a documentos</a>
        @else
          <ul class="siac-home-doc-list">
            @foreach ($recentDocuments as $doc)
              <li class="siac-home-doc-item">
                <div class="siac-home-doc-body">
                  <h3 class="siac-home-doc-title">{{ $doc->title }}</h3>
                  @if ($doc->componentLabel() || $doc->actuaLabel())
                    <p class="siac-home-doc-meta">
                      @if ($doc->componentLabel())
                        <span>{{ $doc->componentLabel() }}</span>
                      @endif
                      @if ($doc->actuaLabel())
                        <span>{{ $doc->actuaLabel() }}</span>
                      @endif
                    </p>
                  @endif
                </div>
                @if ($href = $doc->downloadHref())
                  <a href="{{ $href }}" class="siac-btn siac-btn-primary siac-btn-sm">Descargar</a>
                @endif
              </li>
            @endforeach
          </ul>
          <a href="{{ route('siac.documents.index') }}" class="siac-home-inline-link siac-home-more">Ver todos los documentos →</a>
        @endif
      </section>
    </div>
  </div>
</div>
