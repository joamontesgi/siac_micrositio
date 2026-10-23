@php
  $href = isset($item['route'])
    ? (isset($item['params']) ? route($item['route'], $item['params']) : route($item['route']))
    : '#';
@endphp
<a href="{{ $href }}" @if($href === '#') class="disabled" @endif>{{ $item['label'] }}</a>
