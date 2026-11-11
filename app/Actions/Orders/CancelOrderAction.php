<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderMatchLog;
use Illuminate\Support\Facades\DB;

class CancelOrderAction
{
    public function execute(int $orderId, string $actor = 'Streamer', ?string $reason = null): Order
    {
        return DB::transaction(function () use ($orderId, $actor, $reason) {
            /** @var Order $order */
            $order = Order::where('id', $orderId)->lockForUpdate()->firstOrFail();

            $before = $order->remaining_matches;
            $order->status = OrderStatus::CANCELLED;
            $order->save();

            OrderMatchLog::create([
                'order_id' => $order->id,
                'action' => 'CANCELLED',
                'matches_before' => $before,
                'matches_after' => $before,
                'actor_name' => $actor,
                'remarks' => $reason ? "Dibatalkan: {$reason}" : 'Order dibatalkan.',
                'created_at' => now(),
            ]);

            return $order;
        });
    }
}

