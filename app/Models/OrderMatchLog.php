<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Blueprint;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderMatchLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'action',
        'matches_before',
        'matches_after',
        'actor_name',
        'remarks',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'matches_before' => 'integer',
        'matches_after' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

