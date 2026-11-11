<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderMatchLog;
use Illuminate\Support\Facades\DB;

class SetPlayingAction
{
    /**
     * Memindahkan status order menjadi SEDANG MAIN (Playing).
     */
    public function execute(int $orderId, string $actor = 'Streamer'): Order
    {
        return DB::transaction(function () use ($orderId, $actor) {
            /** @var Order $order */
            $order = Order::where('id', $orderId)->lockForUpdate()->firstOrFail();

            $before = $order->remaining_matches;
            $order->status = OrderStatus::PLAYING;
            if (!$order->started_playing_at) {
                $order->started_playing_at = now();
            }
            $order->save();

            OrderMatchLog::create([
                'order_id' => $order->id,
                'action' => 'SET_PLAYING',
                'matches_before' => $before,
                'matches_after' => $before,
                'actor_name' => $actor,
                'remarks' => 'Streamer memulai game / invite lobby.',
                'created_at' => now(),
            ]);

            return $order;
        });
    }
}

