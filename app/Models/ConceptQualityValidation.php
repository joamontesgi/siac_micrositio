<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ConceptQualityValidation extends Model
{
    protected $fillable = [
        'name',
        'dependency',
        'role',
        'answer_p1',
        'answer_p2',
        'answer_p3',
        'observation_p1',
        'observation_p2',
        'observation_p3',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (! empty($filters['q'])) {
            $q = '%'.mb_strtolower(trim($filters['q'])).'%';
            $query->where(function (Builder $inner) use ($q) {
                $inner->whereRaw('LOWER(name) LIKE ?', [$q])
                    ->orWhereRaw('LOWER(dependency) LIKE ?', [$q])
                    ->orWhereRaw('LOWER(role) LIKE ?', [$q]);
            });
        }

        return $query;
    }

    /** @return array<string, mixed> */
    public function toPublicRow(): array
    {
        return [
            'ts' => $this->submitted_at?->toIso8601String() ?? $this->created_at->toIso8601String(),
            'nombre' => $this->name ?? '',
            'dependencia' => $this->dependency ?? '',
            'rol' => $this->role ?? '',
            'p1' => $this->answer_p1,
            'p2' => $this->answer_p2,
            'p3' => $this->answer_p3,
            'o1' => $this->observation_p1 ?? '',
            'o2' => $this->observation_p2 ?? '',
            'o3' => $this->observation_p3 ?? '',
        ];
    }

    public static function allowedAnswers(): array
    {
        return ['Sí', 'No'];
    }
}
