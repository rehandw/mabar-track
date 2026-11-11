<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Game;
use App\Models\Order;
use App\Models\OrderMatchLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateOrderAction
{
    /**
     * @param array{
     *     game_id: int,
     *     customer_name: string,
     *     in_game_id: string,
     *     in_game_nickname?: string|null,
     *     amount_paid?: float|int|null,
     *     total_matches?: int|null,
     *     notes?: string|null,
     *     is_fast_track?: bool|null,
     *     saweria_transaction_id?: string|null,
     *     payment_status?: PaymentStatus|string|null
     * } $data
     */
    public function execute(array $data, string $actor = 'System'): Order
    {
        return DB::transaction(function () use ($data, $actor) {
            $game = Game::findOrFail($data['game_id']);

            $isFastTrack = (bool) ($data['is_fast_track'] ?? false);
            $unitPrice = $isFastTrack ? $game->effective_fast_track_price : (float) $game->price_per_match;

            $amountPaid = (float) ($data['amount_paid'] ?? 0);
            $totalMatches = (int) ($data['total_matches'] ?? 0);

            // Jika total_matches belum diset tapi ada nominal pembayaran
            if ($totalMatches <= 0 && $amountPaid > 0 && $unitPrice > 0) {
                $totalMatches = (int) floor($amountPaid / $unitPrice);
                $totalMatches = max(1, $totalMatches);
            }

            if ($totalMatches <= 0) {
                $totalMatches = 1;
            }

            // Jika amount belum diset tapi ada total_matches
            if ($amountPaid <= 0) {
                $amountPaid = $totalMatches * $unitPrice;
            }

            $orderNumber = 'MBR-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $nextPosition = (Order::where('status', OrderStatus::QUEUED->value)->max('queue_position') ?? 0) + 1;

            $paymentStatus = $data['payment_status'] ?? PaymentStatus::VERIFIED;
            if (is_string($paymentStatus)) {
                $paymentStatus = PaymentStatus::tryFrom($paymentStatus) ?? PaymentStatus::VERIFIED;
            }

            $order = Order::create([
                'order_number' => $orderNumber,
                'game_id' => $game->id,
                'customer_name' => trim($data['customer_name']),
                'in_game_id' => trim($data['in_game_id']),
                'in_game_nickname' => isset($data['in_game_nickname']) ? trim($data['in_game_nickname']) : null,
                'amount_paid' => $amountPaid,
                'total_matches' => $totalMatches,
                'remaining_matches' => $totalMatches,
                'status' => OrderStatus::QUEUED,
                'payment_status' => $paymentStatus,
                'is_fast_track' => $isFastTrack,
                'saweria_transaction_id' => $data['saweria_transaction_id'] ?? null,
                'notes' => $data['notes'] ?? null,
                'queue_position' => $nextPosition,
            ]);

            $fastTrackRemark = $isFastTrack ? ' [⚡ FAST TRACK VIP]' : '';
            OrderMatchLog::create([
                'order_id' => $order->id,
                'action' => $isFastTrack ? 'ORDER_CREATED_FAST_TRACK' : 'ORDER_CREATED',
                'matches_before' => 0,
                'matches_after' => $totalMatches,
                'actor_name' => $actor,
                'remarks' => "Order dibuat dengan total {$totalMatches} match.{$fastTrackRemark}",
                'created_at' => now(),
            ]);

            return $order;
        });
    }
}

