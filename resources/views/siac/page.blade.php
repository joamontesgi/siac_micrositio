@extends('layouts.unal')

@section('title', $page['title'] . ' — SIAC')

@section('breadcrumb')
    @include('partials.siac.breadcrumb', ['crumbs' => $page['breadcrumb'] ?? [$page['title']]])
@endsection

@section('content')
    @php
        $siacSideSlides = !empty($page['images']);
        $siacSlideCompanion = ($page['display_mode'] ?? '') === 'slide_companion';
    @endphp

    <article class="siac-content siac-page">
        <header class="siac-page-header">
            @if (!empty($page['breadcrumb'][0]) && ($page['breadcrumb'][0] ?? '') === 'Presentación SIAC')
                <p class="siac-page-eyebrow">Presentación SIAC</p>
            @elseif (!empty($page['breadcrumb'][0]) && ($page['breadcrumb'][0] ?? '') === 'SIAC al detalle')
                <p class="siac-page-eyebrow">SIAC al detalle</p>
            @elseif (!empty($page['breadcrumb'][0]) && ($page['breadcrumb'][0] ?? '') === 'Matriz de indicadores')
                <p class="siac-page-eyebrow">Matriz de indicadores</p>
            @endif
            <h1>{{ $page['title'] }}</h1>
            @if (!empty($page['subtitle']))
                <p class="text-muted siac-page-subtitle">{{ $page['subtitle'] }}</p>
            @endif
        </header>

        <div @class([
            'row siac-page-split g-4' => $siacSideSlides,
        ])>
            <div @class([
                'siac-page-prose' => $siacSideSlides,
                'col-lg-6' => $siacSideSlides,
            ])>
                @if ($siacSlideCompanion)
                    <div class="siac-slide-companion">
                        @if (!empty($page['intro']))
                            <p class="siac-slide-companion-lead">{{ $page['intro'] }}</p>
                        @endif
                        @include('partials.siac.related-links', ['links' => $page['related_links'] ?? []])
                    </div>
                @else
                    @if (!empty($page['intro']))
                        @if ($slug === 'contacto')
                            <p class="lead"><a href="mailto:{{ $page['intro'] }}">{{ $page['intro'] }}</a></p>
                        @else
                            <p class="lead">{{ $page['intro'] }}</p>
                        @endif
                    @endif
                @endif

                @if (!$siacSlideCompanion)
                    @if (!empty($page['cycle']))
                        <div class="siac-cycle" role="list">
                            @foreach ($page['cycle'] as $index => $step)
                                @if ($index > 0)
                                    <span class="arrow" aria-hidden="true">→</span>
                                @endif
                                <span role="listitem">{{ $step }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if (!empty($page['nav_cards']))
                        <div class="row mb-4 g-3">
                            @foreach ($page['nav_cards'] as $nav)
                                <div class="col-sm-6 col-lg-3">
                                    <a href="{{ route('siac.page', ['slug' => $nav['slug']]) }}"
                                        class="card h-100 siac-nav-card text-decoration-none">
                                        <div class="card-body">
                                            <h2 class="h6 card-title text-primary mb-1">{{ $nav['label'] }}</h2>
                                            @if (!empty($nav['hint']))
                                                <p class="card-text small text-muted mb-0">{{ $nav['hint'] }}</p>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (!empty($page['cards']))
                        <div class="row g-3">
                            @foreach ($page['cards'] as $card)
                                <div class="col-md-4">
                                    <div class="card h-100 border-primary siac-topic-card">
                                        <div class="card-body">
                                            <h2 class="h5 card-title text-primary">{{ $card['heading'] }}</h2>
                                            <p class="card-text">{{ $card['body'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (!empty($page['pillars']))
                        <div class="row siac-pillars g-3">
                            @foreach ($page['pillars'] as $pillar)
                                <div @class([
                                    'col-lg-6' => !$siacSideSlides,
                                    'col-12' => $siacSideSlides,
                                ])>
                                    <div class="card h-100 siac-pillar-card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <span
                                                    class="badge badge-primary siac-pillar-number mr-3">{{ $pillar['number'] }}</span>
                                                <div>
                                                    <h2 class="h5 card-title">{{ $pillar['heading'] }}</h2>
                                                    <p class="card-text mb-0">{{ $pillar['body'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (!empty($page['actua']))
                        <div class="siac-actua-cycle mb-4" role="list">
                            @foreach ($page['actua'] as $step)
                                <div class="card mb-3 siac-actua-step" role="listitem">
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap align-items-start">
                                            <span class="siac-actua-letter" aria-hidden="true">{{ $step['letter'] }}</span>
                                            <div class="flex-grow-1">
                                                <h2 class="h5 text-primary mb-2">{{ $step['name'] }}</h2>
                                                <p class="mb-2">{{ $step['body'] }}</p>
                                                @if (!empty($step['metaphor']))
                                                    <p class="mb-0 small text-muted"><em>En plastilina:</em>
                                                        {{ $step['metaphor'] }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="small text-muted">
                            <a href="{{ route('siac.page', ['slug' => 'gobernanza-siac-detalle']) }}">Gobernanza del SIAC</a>
                            ·
                            <a href="{{ route('siac.page', ['slug' => 'componentes-funcionales-siac']) }}">Componentes funcionales</a>
                        </p>
                    @endif

                    @foreach ($page['sections'] ?? [] as $section)
                        @if (!empty($section['heading']))
                            <h2>{{ $section['heading'] }}</h2>
                        @endif
                        @if (!empty($section['body']))
                            <p>{{ $section['body'] }}</p>
                        @endif
                        @if (!empty($section['list']))
                            <ul class="siac-principles">
                                @foreach ($section['list'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach

                    @if (!empty($page['related_links']))
                        @include('partials.siac.related-links', ['links' => $page['related_links']])
                    @endif
                @endif
            </div>

            @if ($siacSideSlides)
                <aside class="col-lg-6 siac-page-slides" aria-label="Material gráfico de la presentación">
                    @include('partials.siac.slide-figures', [
                        'images' => $page['images'],
                        'pageTitle' => $page['title'],
                    ])
                </aside>
            @endif
        </div>

        @if (!empty($page['gallery']))
            <div class="siac-gallery-section">
                <h2 class="siac-section-title">Diapositivas completas</h2>
                <div class="siac-slide-gallery">
                    @foreach ($page['gallery'] as $image)
                        <figure class="siac-slide-figure siac-slide-figure-gallery">
                            <a href="{{ asset($image['src']) }}" target="_blank" rel="noopener"
                                class="siac-slide-figure-link">
                                <img src="{{ asset($image['src']) }}" alt="{{ $image['alt'] ?? 'Diapositiva' }}"
                                    class="siac-slide-image img-fluid" loading="lazy" />
                            </a>
                            @if (!empty($image['caption']))
                                <figcaption class="siac-slide-caption">{{ $image['caption'] }}</figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>
            </div>
        @endif
    </article>
@endsection
