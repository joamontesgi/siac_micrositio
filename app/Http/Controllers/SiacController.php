<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Support\SiacContentRepository;
use App\Support\SiacIndicatorRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiacController extends Controller
{
    public function home(SiacIndicatorRepository $indicators, SiacContentRepository $content): View
    {
        $recentDocuments = Document::query()
            ->orderByDesc('published_at')
            ->get();

        $referenceDocuments = Document::query()
            ->whereIn('title', [
                'indicadores_UNAL_lineas_estrategicas.xlsx',
                'SIAC_Reunión 6 de octubre _ Simulacro_V.2.pptx.pdf',
            ])
            ->orderBy('title')
            ->get();

        return view('siac.home', [
            'recentDocuments' => $recentDocuments,
            'referenceDocuments' => $referenceDocuments,
            'indicatorSummary' => $indicators->conjuntoSummary(),
            'indicatorTotal' => $indicators->totalCount(),
            'indicatorSource' => $indicators->sourceFilename(),
            'homeLead' => $content->homeLead(),
            'sources' => $content->sources(),
        ]);
    }

    public function page(string $slug, Request $request, SiacIndicatorRepository $indicators, SiacContentRepository $content): View
    {
        $page = $content->page($slug);
        abort_unless($page !== null, 404);

        if ($slug === 'matriz-indicadores') {
            $excelSource = Document::query()
                ->where('title', 'indicadores_UNAL_lineas_estrategicas.xlsx')
                ->first();

            return view('siac.indicators', [
                'slug' => $slug,
                'page' => $page,
                'groupedIndicators' => $indicators->groupedByConjuntoAndLinea(),
                'conjuntos' => $indicators->conjuntos(),
                'ejes' => $indicators->ejes(),
                'estados' => $indicators->estados(),
                'conjuntoSummary' => $indicators->conjuntoSummary(),
                'indicatorTotal' => $indicators->totalCount(),
                'indicatorSource' => $indicators->sourceFilename(),
                'excelDownloadHref' => $excelSource?->downloadHref() ?? asset('fuentes-siac/indicadores_UNAL_lineas_estrategicas.xlsx'),
            ]);
        }

        return view('siac.page', [
            'slug' => $slug,
            'page' => $page,
        ]);
    }

    public function search(Request $request, SiacContentRepository $content): View
    {
        $query = trim((string) $request->input('q', ''));

        $documents = collect();
        $pageResults = collect();

        if ($query !== '') {
            $documents = Document::query()->filter(['q' => $query])->orderByDesc('published_at')->limit(20)->get();

            foreach ($content->pages() as $slug => $page) {
                $sectionsText = '';
                foreach ($page['sections'] ?? [] as $section) {
                    $sectionsText .= ' '.($section['heading'] ?? '').' '.($section['body'] ?? '');
                    if (! empty($section['list'])) {
                        $sectionsText .= ' '.implode(' ', $section['list']);
                    }
                }
                foreach ($page['cards'] ?? [] as $card) {
                    $sectionsText .= ' '.($card['heading'] ?? '').' '.($card['body'] ?? '');
                }
                foreach ($page['pillars'] ?? [] as $pillar) {
                    $sectionsText .= ' '.($pillar['heading'] ?? '').' '.($pillar['body'] ?? '');
                }
                foreach ($page['actua'] ?? [] as $step) {
                    $sectionsText .= ' '.($step['name'] ?? '').' '.($step['body'] ?? '').' '.($step['metaphor'] ?? '');
                }
                $haystack = mb_strtolower(
                    ($page['title'] ?? '').' '.($page['subtitle'] ?? '').' '.($page['intro'] ?? '')
                    .' '.($page['search_index'] ?? '').' '.$sectionsText
                );
                if (str_contains($haystack, mb_strtolower($query))) {
                    $pageResults->push(['slug' => $slug, 'page' => $page]);
                }
            }
        }

        return view('siac.search', [
            'query' => $query,
            'documents' => $documents,
            'pageResults' => $pageResults,
        ]);
    }
}
