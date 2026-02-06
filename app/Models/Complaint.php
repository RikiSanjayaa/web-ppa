<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    use HasFactory;

    public const STATUS_BARU = 'baru';

    public const STATUS_DIPROSES = 'diproses';

    public const STATUS_SELESAI = 'selesai';

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'alamat',
        'no_hp',
        'email',
        'tempat_kejadian',
        'waktu_kejadian',
        'kronologis_singkat',
        'korban',
        'terlapor',
        'saksi_saksi',
        'status',
        'channel',
        'wa_redirected_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'nik' => 'encrypted',
            'no_hp' => 'encrypted',
            'email' => 'encrypted',
            'waktu_kejadian' => 'datetime',
            'wa_redirected_at' => 'datetime',
        ];
    }

    public static function availableStatuses(): array
    {
        return [
            self::STATUS_BARU,
            self::STATUS_DIPROSES,
            self::STATUS_SELESAI,
        ];
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ComplaintStatusHistory::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['status'] ?? null, fn (Builder $q, string $status) => $q->where('status', $status))
            ->when($filters['date_from'] ?? null, fn (Builder $q, string $dateFrom) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($filters['date_to'] ?? null, fn (Builder $q, string $dateTo) => $q->whereDate('created_at', '<=', $dateTo))
            ->when($filters['q'] ?? null, function (Builder $q, string $term) {
                $q->where(function (Builder $inner) use ($term) {
                    $inner
                        ->where('nama_lengkap', 'like', '%'.$term.'%')
                        ->orWhere('tempat_kejadian', 'like', '%'.$term.'%')
                        ->orWhere('kronologis_singkat', 'like', '%'.$term.'%')
                        ->orWhere('korban', 'like', '%'.$term.'%')
                        ->orWhere('terlapor', 'like', '%'.$term.'%');
                });
            });
    }

    public function getMaskedNoHpAttribute(): ?string
    {
        return $this->maskSensitive($this->no_hp);
    }

    public function getMaskedNikAttribute(): ?string
    {
        return $this->maskSensitive($this->nik);
    }

    private function maskSensitive(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $length = strlen($value);

        if ($length <= 4) {
            return str_repeat('*', $length);
        }

        return substr($value, 0, 2).str_repeat('*', $length - 4).substr($value, -2);
    }
}
