<?php

namespace App\Actions\Orders;

use App\Models\Order;
use App\Models\OrderMatchLog;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class AddMatchAction
{
    /**
     * Tambah N match ke order yang sudah ada (top-up / extend).
     */
    public function execute(int $orderId, int $addCount = 1, float $additionalAmount = 0, string $actor = 'Streamer', ?string $remarks = null): Order
    {
        if ($addCount < 1) {
            throw new InvalidArgumentException('Jumlah match yang ditambahkan harus minimal 1.');
        }

        return DB::transaction(function () use ($orderId, $addCount, $additionalAmount, $actor, $remarks) {
            /** @var Order $order */
            $order = Order::where('id', $orderId)->lockForUpdate()->firstOrFail();

            $before = $order->remaining_matches;
            $after = $before + $addCount;

            $order->remaining_matches = $after;
            $order->total_matches += $addCount;

            if ($additionalAmount > 0) {
                $order->amount_paid = (float) $order->amount_paid + $additionalAmount;
            }

            // Jika order sudah COMPLETED, kembalikan ke PLAYING karena ada match baru
            if ($order->status === \App\Enums\OrderStatus::COMPLETED) {
                $order->status = \App\Enums\OrderStatus::PLAYING;
                $order->completed_at = null;
            }

            $order->save();

            $amountStr = $additionalAmount > 0 ? ' (+ Rp ' . number_format($additionalAmount, 0, ',', '.') . ')' : '';
            OrderMatchLog::create([
                'order_id' => $order->id,
                'action' => 'ADD_MATCH',
                'matches_before' => $before,
                'matches_after' => $after,
                'actor_name' => $actor,
                'remarks' => $remarks ?? "Tambah {$addCount} match.{$amountStr} Total sekarang: {$order->total_matches} match.",
                'created_at' => now(),
            ]);

            return $order;
        });
    }
}

