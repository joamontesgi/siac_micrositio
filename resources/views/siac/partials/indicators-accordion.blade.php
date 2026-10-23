@foreach ($groups as $conjuntoName => $lineas)
  @if ($conjuntoName !== '')
    <h2 class="h5 text-primary mt-3 mb-2">{{ $conjuntoName }}</h2>
  @endif

  <div class="accordion siac-indicators-accordion" id="accordion-{{ $paneId }}-{{ md5($conjuntoName) }}">
    @foreach ($lineas as $linea => $rows)
      @php
        $collapseId = 'collapse-'.$paneId.'-'.md5($conjuntoName.$linea);
        $headingId = 'heading-'.$collapseId;
        $lineNum = $rows[0]['N.º línea'] ?? '—';
      @endphp
      <div class="card siac-accordion-line mb-2">
        <div class="card-header p-0" id="{{ $headingId }}">
          <h3 class="mb-0">
            <button class="btn btn-link btn-block text-left collapsed siac-accordion-toggle" type="button"
                    data-toggle="collapse" data-target="#{{ $collapseId }}"
                    aria-expanded="false" aria-controls="{{ $collapseId }}">
              <span class="badge badge-primary mr-2">L{{ $lineNum }}</span>
              {{ $linea }}
              <span class="badge badge-light float-right">{{ count($rows) }}</span>
            </button>
          </h3>
        </div>
        <div id="{{ $collapseId }}" class="collapse" aria-labelledby="{{ $headingId }}"
             data-parent="#accordion-{{ $paneId }}-{{ md5($conjuntoName) }}">
          <div class="list-group list-group-flush">
            @foreach ($rows as $row)
              @php
                $estado = $row['Estado'] ?? 'Por definir';
                $badge = $estadoBadge[$estado] ?? 'secondary';
                $search = implode(' ', array_filter([
                    $row['ID'] ?? '',
                    $row['Indicador'] ?? '',
                    $linea,
                    $row['Fuente probable'] ?? '',
                    $row['Eje'] ?? '',
                ]));
                $avance = $row['% avance'] ?? null;
              @endphp
              <div class="list-group-item siac-indicator-item"
                   data-conjunto="{{ $row['Conjunto'] ?? '' }}"
                   data-eje="{{ $row['Eje'] ?? '' }}"
                   data-estado="{{ $estado }}"
                   data-search="{{ $search }}">
                <div class="d-flex flex-wrap justify-content-between align-items-start mb-1">
                  <strong class="text-primary mr-2">{{ $row['ID'] }}</strong>
                  <span class="badge badge-{{ $badge }}">{{ $estado }}</span>
                </div>
                <p class="mb-2">{{ $row['Indicador'] ?? '' }}</p>
                <div class="row small text-muted">
                  <div class="col-md-4"><strong>Eje:</strong> {{ $row['Eje'] ?? '—' }}</div>
                  <div class="col-md-4"><strong>Fuente:</strong> {{ $row['Fuente probable'] ?? '—' }}</div>
                  <div class="col-md-4"><strong>Responsable:</strong> {{ $row['Responsable'] ?? '—' }}</div>
                </div>
                @if (($row['Línea base'] ?? null) !== null || ($row['Meta'] ?? null) !== null || ($row['Valor actual'] ?? null) !== null)
                  <div class="row small mt-2">
                    <div class="col-md-4">Base: {{ $row['Línea base'] ?? '—' }}</div>
                    <div class="col-md-4">Meta: {{ $row['Meta'] ?? '—' }}</div>
                    <div class="col-md-4">Actual: {{ $row['Valor actual'] ?? '—' }}</div>
                  </div>
                @endif
                @if ($avance !== null && $avance !== '')
                  @php $pct = max(0, min(100, round((float) $avance * 100))); @endphp
                  <div class="progress mt-2" style="height: 6px;" aria-label="Avance {{ $pct }}%">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pct }}%;"
                         aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                @endif
                <button type="button" class="btn btn-sm btn-outline-primary mt-2" data-toggle="modal"
                        data-target="#modal-ind-{{ $paneId }}-{{ $row['ID'] }}">
                  Ver detalle
                </button>
                <div class="modal fade" id="modal-ind-{{ $paneId }}-{{ $row['ID'] }}" tabindex="-1" role="dialog"
                     aria-labelledby="modal-label-{{ $paneId }}-{{ $row['ID'] }}" aria-hidden="true">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title h5" id="modal-label-{{ $paneId }}-{{ $row['ID'] }}">{{ $row['ID'] }} — {{ $row['Indicador'] }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <dl class="row mb-0">
                          <dt class="col-sm-4">Conjunto</dt>
                          <dd class="col-sm-8">{{ $row['Conjunto'] ?? '—' }}</dd>
                          <dt class="col-sm-4">Eje</dt>
                          <dd class="col-sm-8">{{ $row['Eje'] ?? '—' }}</dd>
                          <dt class="col-sm-4">Línea estratégica</dt>
                          <dd class="col-sm-8">L{{ $lineNum }} · {{ $linea }}</dd>
                          <dt class="col-sm-4">Fuente probable</dt>
                          <dd class="col-sm-8">{{ $row['Fuente probable'] ?? '—' }}</dd>
                          <dt class="col-sm-4">Responsable</dt>
                          <dd class="col-sm-8">{{ $row['Responsable'] ?? '—' }}</dd>
                          <dt class="col-sm-4">Línea base / Meta / Actual</dt>
                          <dd class="col-sm-8">
                            {{ $row['Línea base'] ?? '—' }} /
                            {{ $row['Meta'] ?? '—' }} /
                            {{ $row['Valor actual'] ?? '—' }}
                          </dd>
                          <dt class="col-sm-4">Estado</dt>
                          <dd class="col-sm-8"><span class="badge badge-{{ $badge }}">{{ $estado }}</span></dd>
                          <dt class="col-sm-4">Fecha de actualización</dt>
                          <dd class="col-sm-8">{{ $row['Fecha de actualización'] ?? '—' }}</dd>
                          <dt class="col-sm-4">Observaciones</dt>
                          <dd class="col-sm-8">{{ $row['Observaciones'] ?? '—' }}</dd>
                        </dl>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endforeach
