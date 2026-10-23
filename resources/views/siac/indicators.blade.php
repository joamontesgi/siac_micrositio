@extends('layouts.unal')

@section('title', $page['title'].' — SIAC')

@section('breadcrumb')
  @include('partials.siac.breadcrumb', ['crumbs' => $page['breadcrumb'] ?? [$page['title']]])
@endsection

@php
  $estadoBadge = [
      'Por definir' => 'secondary',
      'En definición' => 'warning',
      'Con datos' => 'info',
      'Verificado' => 'success',
  ];
  $conjuntoShort = function (string $name): string {
      if (preg_match('/Conjunto \d+:\s*(.+?)\s*\(\d+\)/u', $name, $m)) {
          return trim($m[1]);
      }
      return $name;
  };
@endphp

@section('content')
  <article class="siac-content" id="siac-indicators-explorer">
    <h1>{{ $page['title'] }}</h1>
    @if (! empty($page['intro']))
      <p class="lead">{{ $page['intro'] }}</p>
    @endif

    <p class="siac-source-note mb-3">
      Datos de <strong>{{ $indicatorSource }}</strong>.
      <a href="{{ $excelDownloadHref }}">Descargar Excel</a>
    </p>

    <div class="row mb-4">
      @foreach ($conjuntoSummary as $block)
        <div class="col-md-4 mb-3">
          <div class="card h-100 border-primary">
            <div class="card-body text-center">
              <p class="display-4 text-primary mb-0">{{ $block['total'] }}</p>
              <p class="card-text small mb-1">{{ $block['label'] }}</p>
              <span class="badge badge-secondary">{{ $block['por_definir'] }} por definir</span>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="card mb-4">
      <div class="card-header bg-primary text-white">
        <strong>Explorar indicadores</strong>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="filter-q">Buscar</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" aria-hidden="true">&#128269;</span>
              </div>
              <input type="search" class="form-control" id="filter-q" data-filter="q"
                     placeholder="ID, indicador, línea, fuente…" autocomplete="off">
            </div>
          </div>
          <div class="form-group col-md-3">
            <label for="filter-conjunto">Conjunto</label>
            <select class="form-control" id="filter-conjunto" data-filter="conjunto">
              <option value="">Todos</option>
              @foreach ($conjuntos as $c)
                <option value="{{ $c }}">{{ $conjuntoShort($c) }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="filter-eje">Eje</label>
            <select class="form-control" id="filter-eje" data-filter="eje">
              <option value="">Todos</option>
              @foreach ($ejes as $e)
                <option value="{{ $e }}">{{ $e }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-2">
            <label for="filter-estado">Estado</label>
            <select class="form-control" id="filter-estado" data-filter="estado">
              <option value="">Todos</option>
              @foreach ($estados as $st)
                <option value="{{ $st }}">{{ $st }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="d-flex flex-wrap align-items-center justify-content-between">
          <p class="mb-0">
            Mostrando <strong id="siac-indicators-visible-count">{{ $indicatorTotal }}</strong>
            de {{ $indicatorTotal }} indicadores
          </p>
          <button type="button" class="btn btn-outline-secondary btn-sm" data-action="clear-filters">
            Limpiar filtros
          </button>
        </div>
      </div>
    </div>

    <ul class="nav nav-tabs nav-fill mb-3" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="tab-todos" data-toggle="tab" href="#pane-todos" role="tab"
           aria-controls="pane-todos" aria-selected="true">Todos</a>
      </li>
      @foreach ($conjuntos as $idx => $conjunto)
        <li class="nav-item">
          <a class="nav-link" id="tab-c{{ $idx }}" data-toggle="tab" href="#pane-c{{ $idx }}" role="tab"
             aria-controls="pane-c{{ $idx }}" aria-selected="false"
             title="{{ $conjunto }}">{{ $conjuntoShort($conjunto) }}</a>
        </li>
      @endforeach
    </ul>

    <div class="tab-content" id="siac-indicators-tab-content">
      <div class="tab-pane fade show active siac-conjunto-pane" id="pane-todos" role="tabpanel" aria-labelledby="tab-todos">
        @include('siac.partials.indicators-accordion', [
          'groups' => $groupedIndicators,
          'paneId' => 'todos',
          'estadoBadge' => $estadoBadge,
        ])
      </div>
      @foreach ($conjuntos as $idx => $conjunto)
        <div class="tab-pane fade siac-conjunto-pane" id="pane-c{{ $idx }}" role="tabpanel" aria-labelledby="tab-c{{ $idx }}">
          @include('siac.partials.indicators-accordion', [
            'groups' => [$conjunto => ($groupedIndicators[$conjunto] ?? [])],
            'paneId' => 'c'.$idx,
            'estadoBadge' => $estadoBadge,
          ])
        </div>
      @endforeach
    </div>

    <div id="siac-indicators-empty" class="alert alert-warning d-none mt-3" role="status">
      Ningún indicador coincide con los filtros actuales.
    </div>
  </article>
@endsection

@push('scripts')
  <script src="{{ asset('js/siac-indicators.js') }}"></script>
@endpush
