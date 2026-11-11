<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'game_id',
        'customer_name',
        'in_game_id',
        'in_game_nickname',
        'amount_paid',
        'total_matches',
        'remaining_matches',
        'status',
        'payment_status',
        'is_fast_track',
        'saweria_transaction_id',
        'notes',
        'queue_position',
        'started_playing_at',
        'completed_at',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'is_fast_track' => 'boolean',
        'amount_paid' => 'decimal:2',
        'total_matches' => 'integer',
        'remaining_matches' => 'integer',
        'queue_position' => 'integer',
        'started_playing_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function matchLogs(): HasMany
    {
        return $this->hasMany(OrderMatchLog::class)->orderByDesc('created_at');
    }

    public function getCompletedMatchesAttribute(): int
    {
        return max(0, $this->total_matches - $this->remaining_matches);
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->total_matches <= 0) {
            return 100;
        }

        return (int) round(($this->completed_matches / $this->total_matches) * 100);
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount_paid, 0, ',', '.');
    }
}

