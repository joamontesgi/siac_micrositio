@extends('layouts.admin')

@section('admin_title', 'Documentos')

@section('breadcrumb')
  @include('partials.siac.breadcrumb', ['crumbs' => ['Administración', 'Documentos']])
@endsection

@section('admin_content')
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Documentos del SIAC</h1>
    <a href="{{ route('admin.documents.create') }}" class="btn btn-primary">Nuevo documento</a>
  </div>

  <form method="get" class="card card-body mb-3">
    <div class="form-row">
      <div class="form-group col-md-5">
        <label for="q">Buscar</label>
        <input type="search" class="form-control" id="q" name="q" value="{{ $filters['q'] ?? '' }}">
      </div>
      <div class="form-group col-md-3">
        <label for="functional_component">Componente</label>
        <select class="form-control" id="functional_component" name="functional_component">
          <option value="">Todos</option>
          @foreach ($components as $key => $meta)
            <option value="{{ $key }}" @selected(($filters['functional_component'] ?? '') === $key)>
              {{ $meta['short'] }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="form-group col-md-3">
        <label for="actua_phase">ACTÚA</label>
        <select class="form-control" id="actua_phase" name="actua_phase">
          <option value="">Todas</option>
          @foreach ($actuaPhases as $key => $meta)
            <option value="{{ $key }}" @selected(($filters['actua_phase'] ?? '') === $key)>
              {{ $meta['letter'] }}. {{ $meta['short'] }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="form-group col-md-1 d-flex align-items-end">
        <button type="submit" class="btn btn-secondary btn-block">Filtrar</button>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>Nombre</th>
          <th>Clasificación</th>
          <th>Archivo</th>
          <th>Publicación</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($documents as $doc)
          <tr>
            <td>
              <strong>{{ $doc->title }}</strong>
              @if ($doc->description)
                <br><small class="text-muted">{{ Str::limit($doc->description, 80) }}</small>
              @endif
            </td>
            <td>
              @if ($doc->componentLabel())
                <span class="badge badge-primary d-block mb-1">{{ $doc->componentLabel() }}</span>
              @endif
              @if ($doc->actuaLabel())
                <span class="badge badge-info">ACTÚA · {{ $doc->actuaLabel() }}</span>
              @endif
              @if (! $doc->componentLabel() && ! $doc->actuaLabel())
                —
              @endif
            </td>
            <td>
              @if ($href = $doc->downloadHref())
                <a href="{{ $href }}" target="_blank" rel="noopener">{{ $doc->original_filename ?? 'Enlace' }}</a>
              @else
                —
              @endif
            </td>
            <td>{{ optional($doc->published_at)->format('Y-m-d') ?? '—' }}</td>
            <td class="text-nowrap">
              <a href="{{ route('admin.documents.edit', $doc) }}" class="btn btn-sm btn-outline-primary">Editar</a>
              <form method="post" action="{{ route('admin.documents.destroy', $doc) }}" class="d-inline"
                    onsubmit="return confirm('¿Eliminar este documento?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5">No hay documentos. <a href="{{ route('admin.documents.create') }}">Cargar el primero</a>.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $documents->links() }}
@endsection
