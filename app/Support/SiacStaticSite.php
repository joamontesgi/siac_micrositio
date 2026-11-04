<?php

namespace App\Support;

use App\Models\Document;
use Illuminate\Support\Collection;

/** Renders the public site at deploy time, without a database or HTTP server. */
class SiacStaticSite
{
    public const ORIGIN = 'https://static.invalid';

    private SiacContentRepository $content;
    private SiacIndicatorRepository $indicators;

    public function __construct(?SiacContentRepository $content = null, ?SiacIndicatorRepository $indicators = null)
    {
        $this->content = $content ?? new SiacContentRepository;
        $this->indicators = $indicators ?? new SiacIndicatorRepository;
    }

    public function documents(): Collection
    {
        return collect([
            new Document([
                'title' => 'Presentación SIAC — Simulacro 6 de octubre',
                'description' => 'Presentación del Sistema Interno de Aseguramiento de la Calidad.',
                'url' => self::ORIGIN.'/fuentes-siac/SIAC_Reunion_6_octubre_Simulacro_V2.pdf',
            ]),
            new Document([
                'title' => 'Matriz de indicadores UNAL — Líneas estratégicas',
                'description' => 'Indicadores, resumen y leyenda de la matriz de calidad.',
                'url' => self::ORIGIN.'/fuentes-siac/indicadores_UNAL_lineas_estrategicas.xlsx',
            ]),
        ]);
    }

    public function searchIndex(): array
    {
        $entries = [];
        foreach ($this->content->pages() as $slug => $page) {
            $entries[] = [
                'title' => $page['title'],
                'href' => '/pagina/'.$slug,
                'text' => json_encode($page, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            ];
        }
        foreach ($this->documents() as $document) {
            $entries[] = [
                'title' => $document->title,
                'href' => str_replace(self::ORIGIN, '', $document->url),
                'text' => $document->title.' '.$document->description,
            ];
        }

        return $entries;
    }

    /** @return \Generator<string, string> Output path => rendered HTML. */
    public function pages(): \Generator
    {
        $content = $this->content;
        $indicators = $this->indicators;
        if (empty($content->pages()) || $indicators->totalCount() === 0) {
            throw new \RuntimeException('Genere el contenido y los indicadores antes de exportar.');
        }
        $render = fn ($view, $data = []) => str_replace(
            self::ORIGIN, '', view($view, $data)->render()
        );

        yield 'index.html' => $render('siac.home', [
            'recentDocuments' => $this->documents(),
            'homeLead' => $content->homeLead(),
        ]);
        foreach ($content->pages() as $slug => $page) {
            if (! preg_match('/^[a-z0-9-]+$/', $slug)) {
                throw new \RuntimeException('Slug no válido para exportación.');
            }
            $data = compact('slug', 'page');
            if ($slug === 'matriz-indicadores') {
                $data += [
                    'groupedIndicators' => $indicators->groupedByConjuntoAndLinea(),
                    'conjuntos' => $indicators->conjuntos(),
                    'ejes' => $indicators->ejes(),
                    'estados' => $indicators->estados(),
                    'conjuntoSummary' => $indicators->conjuntoSummary(),
                    'indicatorTotal' => $indicators->totalCount(),
                    'indicatorSource' => $indicators->sourceFilename(),
                    'excelDownloadHref' => '/fuentes-siac/indicadores_UNAL_lineas_estrategicas.xlsx',
                ];
            }
            yield 'pagina/'.$slug.'/index.html' => $render(
                $slug === 'matriz-indicadores' ? 'siac.indicators' : 'siac.page', $data
            );
        }
        yield 'documentos/index.html' => $render('siac.static.documents', ['documents' => $this->documents()]);
        yield 'buscar/index.html' => $render('siac.static.search');
        yield '404.html' => $render('siac.static.not-found');
    }
}
