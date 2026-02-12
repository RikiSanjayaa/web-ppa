<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_klien',
        'permasalahan',
        'rekomendasi',
        'latitude',
        'longitude',
        'ip_address',
        'user_agent',
    ];

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['date_from'] ?? null, fn(Builder $q, string $dateFrom) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($filters['date_to'] ?? null, fn(Builder $q, string $dateTo) => $q->whereDate('created_at', '<=', $dateTo))
            ->when($filters['status'] ?? null, function (Builder $q, string $status) {
                if ($status === 'belum') {
                    $q->whereNull('rekomendasi');
                } elseif ($status === 'sudah') {
                    $q->whereNotNull('rekomendasi');
                }
            })
            ->when($filters['q'] ?? null, function (Builder $q, string $term) {
                $q->where(function (Builder $inner) use ($term) {
                    $inner
                        ->where('nama_klien', 'like', '%' . $term . '%')
                        ->orWhere('permasalahan', 'like', '%' . $term . '%')
                        ->orWhere('rekomendasi', 'like', '%' . $term . '%');
                });
            });
    }
}

