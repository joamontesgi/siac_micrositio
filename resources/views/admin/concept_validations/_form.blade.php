@php
  $v = $validation;
@endphp

<div class="form-row">
  <div class="form-group col-md-4">
    <label for="name">Nombre</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $v->name) }}">
  </div>
  <div class="form-group col-md-4">
    <label for="dependency">Dependencia o sede</label>
    <input type="text" class="form-control" id="dependency" name="dependency" value="{{ old('dependency', $v->dependency) }}">
  </div>
  <div class="form-group col-md-4">
    <label for="role">Estamento o cargo</label>
    <input type="text" class="form-control" id="role" name="role" value="{{ old('role', $v->role) }}">
  </div>
</div>

<div class="form-group">
  <label for="submitted_at">Fecha de envío</label>
  <input type="datetime-local" class="form-control" id="submitted_at" name="submitted_at"
    value="{{ old('submitted_at', optional($v->submitted_at)->format('Y-m-d\TH:i')) }}">
</div>

@foreach ([1 => 'Pertinencia institucional', 2 => 'Integralidad de la calidad', 3 => 'Capacidad orientadora'] as $n => $label)
  @php
    $ans = 'answer_p'.$n;
    $obs = 'observation_p'.$n;
  @endphp
  <fieldset class="border rounded p-3 mb-3">
    <legend class="w-auto px-2 h6">{{ $n }}. {{ $label }}</legend>
    <div class="form-group">
      <label>Respuesta</label>
      <div>
        <label class="mr-3"><input type="radio" name="{{ $ans }}" value="Sí" @checked(old($ans, $v->$ans) === 'Sí') required> Sí</label>
        <label><input type="radio" name="{{ $ans }}" value="No" @checked(old($ans, $v->$ans) === 'No') required> No</label>
      </div>
    </div>
    <div class="form-group mb-0">
      <label for="{{ $obs }}">Observaciones</label>
      <textarea class="form-control" id="{{ $obs }}" name="{{ $obs }}" rows="3">{{ old($obs, $v->$obs) }}</textarea>
    </div>
  </fieldset>
@endforeach
