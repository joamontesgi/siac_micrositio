@extends('layouts.admin')

@section('admin_title', 'Validación concepto de calidad')

@section('breadcrumb')
  @include('partials.siac.breadcrumb', ['crumbs' => ['Administración', 'Validación concepto']])
@endsection

@section('admin_content')
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Validación del concepto de calidad</h1>
    <a href="{{ route('admin.concept_validations.create') }}" class="btn btn-primary">Nueva respuesta</a>
  </div>

  <div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ route('admin.concept_validations.export') }}" class="btn btn-outline-secondary btn-sm">Exportar CSV</a>
    <a href="{{ route('siac.concept_validation.acta') }}" class="btn btn-outline-secondary btn-sm">Descargar resumen para acta</a>
    <a href="{{ route('siac.page', ['slug' => 'concepto-calidad']) }}" class="btn btn-outline-secondary btn-sm">Ver formulario público</a>
  </div>

  <form method="get" class="card card-body mb-3">
    <div class="form-row align-items-end">
      <div class="form-group col-md-10 mb-0">
        <label for="q">Buscar</label>
        <input type="search" class="form-control" id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nombre, dependencia o estamento">
      </div>
      <div class="form-group col-md-2 mb-0">
        <button type="submit" class="btn btn-secondary btn-block">Filtrar</button>
      </div>
    </div>
  </form>

  <form method="post" action="{{ route('admin.concept_validations.import') }}" enctype="multipart/form-data" class="card card-body mb-3">
    @csrf
    <div class="form-row align-items-end">
      <div class="form-group col-md-8 mb-0">
        <label for="csv">Importar respuestas (CSV con separador ;)</label>
        <input type="file" class="form-control-file" id="csv" name="csv" accept=".csv,text/csv" required>
      </div>
      <div class="form-group col-md-4 mb-0">
        <button type="submit" class="btn btn-outline-primary btn-block">Importar</button>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped table-bordered table-sm">
      <thead class="thead-dark">
        <tr>
          <th>Fecha</th>
          <th>Participante</th>
          <th>P1</th>
          <th>P2</th>
          <th>P3</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($validations as $item)
          <tr>
            <td class="text-nowrap">{{ $item->submitted_at?->format('d/m/Y H:i') }}</td>
            <td>
              <strong>{{ $item->name ?: '—' }}</strong>
              @if ($item->dependency)
                <br><span class="small text-muted">{{ $item->dependency }}</span>
              @endif
              @if ($item->role)
                <br><span class="small text-muted">{{ $item->role }}</span>
              @endif
            </td>
            <td>{{ $item->answer_p1 }}</td>
            <td>{{ $item->answer_p2 }}</td>
            <td>{{ $item->answer_p3 }}</td>
            <td class="text-nowrap">
              <a href="{{ route('admin.concept_validations.edit', $item) }}" class="btn btn-sm btn-outline-primary">Editar</a>
              <form method="post" action="{{ route('admin.concept_validations.destroy', $item) }}" class="d-inline" onsubmit="return confirm('¿Eliminar esta respuesta?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted py-4">No hay respuestas registradas.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $validations->links() }}

  @if ($validations->total() > 0)
    <form method="post" action="{{ route('admin.concept_validations.destroy_all') }}" class="mt-4 card card-body border-danger" onsubmit="return confirm('¿Eliminar TODAS las respuestas? Esta acción no se puede deshacer.');">
      @csrf
      @method('DELETE')
      <label for="confirm">Vaciar consolidado (escriba VACIAR)</label>
      <div class="form-row">
        <div class="col-md-4">
          <input type="text" class="form-control" id="confirm" name="confirm" placeholder="VACIAR" required autocomplete="off">
        </div>
        <div class="col-md-4">
          <button type="submit" class="btn btn-danger">Vaciar todas las respuestas</button>
        </div>
      </div>
    </form>
  @endif
@endsection
