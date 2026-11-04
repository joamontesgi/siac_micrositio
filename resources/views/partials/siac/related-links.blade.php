@if (!empty($links))
    <nav class="siac-related-links" aria-label="Enlaces relacionados">
        <p class="siac-related-links-label">Profundizar</p>
        <ul class="siac-related-links-list">
            @foreach ($links as $link)
                <li>
                    @if (($link['slug'] ?? '') === '__documents__')
                        <a href="{{ route('siac.documents.index') }}">{{ $link['label'] }}</a>
                    @else
                        <a href="{{ route('siac.page', ['slug' => $link['slug']]) }}">{{ $link['label'] }}</a>
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>
@endif
