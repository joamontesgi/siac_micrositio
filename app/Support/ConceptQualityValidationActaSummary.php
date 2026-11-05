<?php

namespace App\Support;

use App\Models\ConceptQualityValidation;
use Illuminate\Support\Collection;

class ConceptQualityValidationActaSummary
{
    /** @var list<string> */
    public const CRITERIA = [
        'Pertinencia institucional',
        'Integralidad de la calidad',
        'Capacidad orientadora',
    ];

    public static function build(?Collection $records = null): string
    {
        $records ??= ConceptQualityValidation::query()
            ->orderBy('submitted_at')
            ->orderBy('id')
            ->get();

        $rows = $records->map(fn (ConceptQualityValidation $r) => $r->toPublicRow());
        $n = $rows->count();

        $lines = [
            'Validación del concepto de calidad — SIAC UNAL',
            'Fecha de consolidación: '.now()->format('d/m/Y H:i'),
            'Participantes que respondieron: '.$n,
            '',
        ];

        foreach (self::CRITERIA as $index => $label) {
            $q = $index + 1;
            $key = 'p'.$q;
            $obsKey = 'o'.$q;
            $si = $rows->where($key, 'Sí')->count();
            $no = $rows->where($key, 'No')->count();
            $pct = $n > 0 ? (int) round($si / $n * 100) : 0;

            $lines[] = $q.'. '.$label.': '.$si.' Sí, '.$no.' No'
                .($n > 0 ? ' ('.$pct.'% de aprobación)' : '');

            foreach ($rows as $row) {
                $obs = trim((string) ($row[$obsKey] ?? ''));
                if ($obs === '') {
                    continue;
                }
                $who = trim((string) ($row['nombre'] ?? '')) ?: 'Participante sin nombre';
                if (! empty($row['dependencia'])) {
                    $who .= ' ('.$row['dependencia'].')';
                }
                $lines[] = '   - '.$who.': '.$obs;
            }
            $lines[] = '';
        }

        return rtrim(implode("\n", $lines))."\n";
    }
}
