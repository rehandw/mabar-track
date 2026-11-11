<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderMatchLog;
use Illuminate\Support\Facades\DB;

class CompleteOrderAction
{
    public function execute(int $orderId, string $actor = 'Streamer', ?string $remarks = null): Order
    {
        return DB::transaction(function () use ($orderId, $actor, $remarks) {
            /** @var Order $order */
            $order = Order::where('id', $orderId)->lockForUpdate()->firstOrFail();

            $before = $order->remaining_matches;
            $order->remaining_matches = 0;
            $order->status = OrderStatus::COMPLETED;
            $order->completed_at = now();
            $order->save();

            OrderMatchLog::create([
                'order_id' => $order->id,
                'action' => 'COMPLETED',
                'matches_before' => $before,
                'matches_after' => 0,
                'actor_name' => $actor,
                'remarks' => $remarks ?? 'Order diselesaikan secara manual.',
                'created_at' => now(),
            ]);

            return $order;
        });
    }
}

