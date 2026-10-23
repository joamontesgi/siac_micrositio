<?php

namespace App\Support;

class SiacContentRepository
{
    protected ?array $data = null;

    public function data(): array
    {
        if ($this->data !== null) {
            return $this->data;
        }

        $path = storage_path('app/data/site_content.json');
        if (! is_file($path)) {
            $this->data = ['pages' => [], 'menu' => [], 'quick_links' => [], 'sources' => []];

            return $this->data;
        }

        $decoded = json_decode((string) file_get_contents($path), true);
        $this->data = is_array($decoded) ? $decoded : ['pages' => [], 'menu' => [], 'quick_links' => [], 'sources' => []];

        return $this->data;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function pages(): array
    {
        return $this->data()['pages'] ?? [];
    }

    public function page(string $slug): ?array
    {
        return $this->pages()[$slug] ?? null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function menu(): array
    {
        return $this->data()['menu'] ?? [];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function quickLinks(): array
    {
        return $this->data()['quick_links'] ?? [];
    }

    public function homeLead(): string
    {
        return (string) ($this->data()['home_lead'] ?? '');
    }

    public function contactEmail(): string
    {
        return (string) ($this->data()['contact_email'] ?? 'siac_nal@unal.edu.co');
    }

    /**
     * @return array{pdf?: string, excel?: string}
     */
    public function sources(): array
    {
        return $this->data()['sources'] ?? [];
    }
}
