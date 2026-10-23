<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'title',
        'functional_component',
        'actua_phase',
        'description',
        'document_type',
        'process',
        'institutional_level',
        'year',
        'dependency',
        'topic',
        'version',
        'status',
        'url',
        'file_path',
        'original_filename',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'date',
        'year' => 'integer',
    ];

    public function componentLabel(): ?string
    {
        if ($this->functional_component === null) {
            return null;
        }

        return config('siac.functional_components.'.$this->functional_component.'.label');
    }

    public function actuaLabel(): ?string
    {
        if ($this->actua_phase === null) {
            return null;
        }

        if ($this->actua_phase === 'todas') {
            return 'Todas · Ciclo ACTÚA';
        }

        $meta = config('siac.actua_phases.'.$this->actua_phase);

        return $meta && empty($meta['is_all']) ? ($meta['letter'].'. '.$meta['label']) : null;
    }

    public function downloadHref(): ?string
    {
        if ($this->exists && ($this->file_path || $this->publicRelativePath())) {
            return route('siac.documents.download', $this);
        }

        if ($this->url && (str_starts_with($this->url, 'http://') || str_starts_with($this->url, 'https://'))) {
            return $this->url;
        }

        return null;
    }

    public function publicRelativePath(): ?string
    {
        if ($this->file_path) {
            return 'storage/'.$this->file_path;
        }

        if (! $this->url) {
            return null;
        }

        if (str_contains($this->url, '://')) {
            $path = parse_url($this->url, PHP_URL_PATH);

            return is_string($path) && $path !== '' ? ltrim($path, '/') : null;
        }

        return ltrim($this->url, '/');
    }

    public function scopeFilter($query, array $filters)
    {
        if (! empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function ($builder) use ($q) {
                $builder->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('dependency', 'like', "%{$q}%")
                    ->orWhere('topic', 'like', "%{$q}%");
            });
        }

        if (! empty($filters['functional_component'])) {
            if ($filters['functional_component'] === '_none') {
                $query->whereNull('functional_component');
            } else {
                $query->where('functional_component', $filters['functional_component']);
            }
        }

        if (! empty($filters['actua_phase'])) {
            if ($filters['actua_phase'] === '_none') {
                $query->whereNull('actua_phase');
            } elseif ($filters['actua_phase'] === 'todas') {
                $query->whereNotNull('actua_phase');
            } else {
                $query->where('actua_phase', $filters['actua_phase']);
            }
        }

        foreach (['document_type', 'process', 'institutional_level', 'year', 'dependency'] as $field) {
            if (! empty($filters[$field])) {
                $query->where($field, $filters[$field]);
            }
        }

        return $query;
    }
}
