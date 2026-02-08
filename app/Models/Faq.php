<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
  use HasFactory;

  protected $fillable = [
    'question',
    'answer',
    'order',
    'is_active',
  ];

  protected function casts(): array
  {
    return [
      'is_active' => 'boolean',
      'order' => 'integer',
    ];
  }

  public function scopeActive(Builder $query): Builder
  {
    return $query->where('is_active', true);
  }

  public function scopeOrdered(Builder $query): Builder
  {
    return $query->orderBy('order')->orderBy('id');
  }
}
