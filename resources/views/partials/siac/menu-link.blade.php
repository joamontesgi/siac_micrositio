@php
  $href = isset($item['route'])
    ? (isset($item['params']) ? route($item['route'], $item['params']) : route($item['route']))
    : '#';
@endphp
<a href="{{ $href }}" class="dropdown-item" @if ($href === '#') aria-disabled="true" tabindex="-1" @endif>{{ $item['label'] }}</a>
