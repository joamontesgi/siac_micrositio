<?php

namespace App\Support;

class SiacIndicatorRepository
{
    protected ?array $data = null;

    public function data(): array
    {
        if ($this->data !== null) {
            return $this->data;
        }

        $path = storage_path('app/data/indicators.json');
        if (! is_file($path)) {
            $this->data = ['indicators' => [], 'resumen' => [], 'source' => ''];

            return $this->data;
        }

        $decoded = json_decode((string) file_get_contents($path), true);
        $this->data = is_array($decoded) ? $decoded : ['indicators' => [], 'resumen' => [], 'source' => ''];

        return $this->data;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function all(): array
    {
        return $this->data()['indicators'] ?? [];
    }

    /**
     * @param  array<string, string|null>  $filters
     * @return list<array<string, mixed>>
     */
    public function filter(array $filters): array
    {
        $conjunto = trim((string) ($filters['conjunto'] ?? ''));
        $eje = trim((string) ($filters['eje'] ?? ''));
        $query = mb_strtolower(trim((string) ($filters['q'] ?? '')));

        return array_values(array_filter($this->all(), function (array $row) use ($conjunto, $eje, $query) {
            if ($conjunto !== '' && (string) ($row['Conjunto'] ?? '') !== $conjunto) {
                return false;
            }
            if ($eje !== '' && (string) ($row['Eje'] ?? '') !== $eje) {
                return false;
            }
            if ($query === '') {
                return true;
            }

            $haystack = mb_strtolower(implode(' ', [
                $row['ID'] ?? '',
                $row['Indicador'] ?? '',
                $row['Línea estratégica'] ?? '',
                $row['Fuente probable'] ?? '',
            ]));

            return str_contains($haystack, $query);
        }));
    }

    /**
     * @return list<string>
     */
    public function conjuntos(): array
    {
        $values = array_unique(array_map(fn ($row) => (string) ($row['Conjunto'] ?? ''), $this->all()));
        sort($values);

        return array_values(array_filter($values));
    }

    /**
     * @return list<string>
     */
    public function ejes(): array
    {
        $values = array_unique(array_map(fn ($row) => (string) ($row['Eje'] ?? ''), $this->all()));
        sort($values);

        return array_values(array_filter($values));
    }

    /**
     * @return list<array{label: string, total: int, por_definir: int}>
     */
    public function conjuntoSummary(): array
    {
        $rows = $this->data()['resumen'] ?? [];
        $out = [];

        foreach ($rows as $row) {
            if (! is_array($row) || empty($row[0]) || ! is_string($row[0])) {
                continue;
            }
            if ($row[0] === 'Conjunto' || $row[0] === 'Resumen de avance por conjunto') {
                continue;
            }
            if ($row[0] === 'Total' || ! str_starts_with($row[0], 'Conjunto')) {
                continue;
            }

            $out[] = [
                'label' => $row[0],
                'total' => (int) ($row[1] ?? 0),
                'por_definir' => (int) ($row[2] ?? 0),
            ];
        }

        return $out;
    }

    public function totalCount(): int
    {
        return count($this->all());
    }

    public function sourceFilename(): string
    {
        return (string) ($this->data()['source'] ?? 'indicadores_UNAL_lineas_estrategicas.xlsx');
    }

    /**
     * @return list<string>
     */
    public function estados(): array
    {
        $values = array_unique(array_map(fn ($row) => (string) ($row['Estado'] ?? ''), $this->all()));
        sort($values);

        return array_values(array_filter($values));
    }

    /**
     * Conjunto → línea estratégica → filas.
     *
     * @return array<string, array<string, list<array<string, mixed>>>>
     */
    public function groupedByConjuntoAndLinea(): array
    {
        $groups = [];
        foreach ($this->all() as $row) {
            $conjunto = (string) ($row['Conjunto'] ?? '');
            $linea = (string) ($row['Línea estratégica'] ?? '');
            $groups[$conjunto][$linea][] = $row;
        }

        return $groups;
    }
}
