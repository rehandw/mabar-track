<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderMatchLog;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class DecrementMatchAction
{
    /**
     * Kurangi 1 match dari order secara atomic dengan database pessimistic lock.
     */
    public function execute(int $orderId, string $actor = 'Streamer', ?string $remarks = null): Order
    {
        return DB::transaction(function () use ($orderId, $actor, $remarks) {
            /** @var Order $order */
            $order = Order::where('id', $orderId)->lockForUpdate()->firstOrFail();

            if ($order->remaining_matches <= 0) {
                throw new InvalidArgumentException("Order #{$order->order_number} sudah tidak memiliki sisa match.");
            }

            $before = $order->remaining_matches;
            $after = $before - 1;

            $order->remaining_matches = $after;

            if ($after === 0) {
                $order->status = OrderStatus::COMPLETED;
                $order->completed_at = now();
            } else {
                // Pastikan status tetap playing jika masih ada sisa
                if ($order->status !== OrderStatus::PLAYING) {
                    $order->status = OrderStatus::PLAYING;
                    $order->started_playing_at = $order->started_playing_at ?? now();
                }
            }

            $order->save();

            OrderMatchLog::create([
                'order_id' => $order->id,
                'action' => 'DECREMENT_MATCH',
                'matches_before' => $before,
                'matches_after' => $after,
                'actor_name' => $actor,
                'remarks' => $remarks ?? ($after === 0 ? "Match terakhir selesai. Order ditutup otomatis." : "Match selesai. Sisa {$after} match."),
                'created_at' => now(),
            ]);

            return $order;
        });
    }
}

