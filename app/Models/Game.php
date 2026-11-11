<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'price_per_match',
        'fast_track_price_per_match',
        'id_format_hint',
        'badge_color',
        'is_active',
    ];

    protected $casts = [
        'price_per_match' => 'decimal:2',
        'fast_track_price_per_match' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getEffectiveFastTrackPriceAttribute(): float
    {
        if ($this->fast_track_price_per_match && $this->fast_track_price_per_match > 0) {
            return (float) $this->fast_track_price_per_match;
        }

        // Default 2x regular price
        return (float) ($this->price_per_match * 2);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}

