<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'relation',
        'content',
        'rating',
        'display_order',
        'is_published',
        'is_verified',
        'consultation_id',
        'complaint_id',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'rating' => 'integer',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order')->orderByDesc('rating');
    }
}
