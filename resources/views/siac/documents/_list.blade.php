@if ($items->isEmpty())
  <p class="text-muted">No hay documentos en esta categoría.</p>
@else
  <div class="list-group mb-4">
    @foreach ($items as $doc)
      <div class="list-group-item">
        <div class="d-flex flex-wrap justify-content-between align-items-start">
          <div class="mb-2 mb-md-0">
            <h3 class="h6 mb-1">{{ $doc->title }}</h3>
            @if ($doc->description)
              <p class="small text-muted mb-1">{{ $doc->description }}</p>
            @endif
            @if ($label = $doc->componentLabel())
              <span class="badge badge-primary mr-1">{{ $label }}</span>
            @endif
            @if ($actua = $doc->actuaLabel())
              <span class="badge badge-info">ACTÚA · {{ $actua }}</span>
            @endif
            @if ($doc->published_at)
              <small class="text-muted ml-1">{{ $doc->published_at->format('Y-m-d') }}</small>
            @endif
          </div>
          @if ($href = $doc->downloadHref())
            <a href="{{ $href }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">
              Descargar
            </a>
          @endif
        </div>
      </div>
    @endforeach
  </div>
@endif
