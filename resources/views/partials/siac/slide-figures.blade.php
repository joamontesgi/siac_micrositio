<div class="siac-slide-panel">
    <p class="siac-slide-panel-label">Diapositiva — simulacro 6 oct 2026</p>
    <div class="siac-slide-figures" aria-label="Diapositivas de la presentación">
        @foreach ($images as $image)
            <figure class="siac-slide-figure">
                <a href="{{ asset($image['src']) }}" target="_blank" rel="noopener" class="siac-slide-figure-link">
                    <img src="{{ asset($image['src']) }}" alt="{{ $image['alt'] ?? ($pageTitle ?? 'Diapositiva') }}"
                        class="siac-slide-image img-fluid" loading="lazy" width="1280" height="720" />
                </a>
                @if (!empty($image['caption']))
                    <figcaption class="siac-slide-caption">{{ $image['caption'] }}</figcaption>
                @endif
            </figure>
        @endforeach
    </div>
    <p class="siac-slide-panel-hint">Clic en la imagen para ampliar.</p>
</div>
