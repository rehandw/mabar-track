<?php

namespace App\Actions\Saweria;

use App\Actions\Orders\AddMatchAction;
use App\Actions\Orders\CreateOrderAction;
use App\Enums\OrderStatus;
use App\Models\Game;
use App\Models\Order;
use Illuminate\Support\Str;

class ParseSaweriaDonationAction
{
    public function __construct(
        protected CreateOrderAction $createOrderAction,
        protected AddMatchAction $addMatchAction
    ) {
    }

    public function execute(array $payload): Order
    {
        $message = trim($payload['message'] ?? '');
        $donatorName = trim($payload['donator_name'] ?? 'Donatur Misterius');
        $amount = (float) ($payload['amount'] ?? 0);
        $transactionId = $payload['transaction_id'] ?? ('SAW-' . Str::random(10));

        $gameFound = null;
        $inGameId = 'UNKNOWN_ID';
        $notes = $message;

        $games = Game::where('is_active', true)->get();

        foreach ($games as $game) {
            $slug = strtolower($game->slug);
            $name = strtolower($game->name);

            if (Str::contains(strtolower($message), $slug) || Str::contains(strtolower($message), $name)) {
                $gameFound = $game;
                break;
            }
        }

        if (! $gameFound) {
            $gameFound = $games->first() ?? Game::first();
        }

        $lowerMessage = strtolower($message);
        $isFastTrack = Str::contains($lowerMessage, ['fast', 'fasttrack', 'fast-track', 'vip', 'express', 'prioritas']);
        $isAddMatch = Str::contains($lowerMessage, ['tambah', 'extend', 'lanjut', 'topup', 'top-up', 'add', 'nambah']);

        // 1. Cek apakah ada nomor order di pesan (contoh: MBR-20261102-001 atau #MBR-...)
        $orderNumberMatches = [];
        if (preg_match('/(?:#)?(MBR-[0-9]{8}-[0-9]{3,4})/i', $message, $orderNumberMatches)) {
            $existingOrder = Order::with('game')
                ->where('order_number', strtoupper($orderNumberMatches[1]))
                ->first();

            if ($existingOrder) {
                $unitPrice = $existingOrder->is_fast_track ? $existingOrder->game->effective_fast_track_price : (float) $existingOrder->game->price_per_match;
                $addCount = max(1, (int) floor($amount / max(1, $unitPrice)));

                return $this->addMatchAction->execute(
                    orderId: $existingOrder->id,
                    addCount: $addCount,
                    additionalAmount: $amount,
                    actor: 'Saweria Webhook',
                    remarks: "Top-up via Saweria [{$transactionId}] dari {$donatorName} (+{$addCount} match). Pesan: {$message}"
                );
            }
        }

        $matches = [];
        if (preg_match('/(?:mabar|tambah|extend|lanjut|topup|add|id\s*[:=]?)\s*(?:(?:fast|fasttrack|fast-track|vip|express)\s+)?(?:[a-z0-9_-]+\s+)?([a-z0-9_#\(\)-]+)(?:\s+(.*))?$/i', $message, $matches)) {
            $inGameId = $matches[1] ?? 'ID belum dicantumkan';
            if (! empty($matches[2])) {
                $notes = trim($matches[2]);
            }
        } else {
            $inGameId = $message ?: 'ID belum dicantumkan';
        }

        // 2. Jika ada keyword tambah/extend dan in_game_id cocok dengan order yang sedang aktif
        if ($isAddMatch) {
            $activeOrder = Order::with('game')
                ->whereIn('status', [OrderStatus::PLAYING->value, OrderStatus::QUEUED->value])
                ->where('in_game_id', $inGameId)
                ->latest()
                ->first();

            // Jika tidak cocok langsung dengan $inGameId, cek apakah ada active order yang in_game_id-nya terkandung di dalam pesan
            if (! $activeOrder) {
                $candidates = Order::with('game')
                    ->whereIn('status', [OrderStatus::PLAYING->value, OrderStatus::QUEUED->value])
                    ->get();

                foreach ($candidates as $cand) {
                    if (! empty($cand->in_game_id) && Str::contains($message, $cand->in_game_id)) {
                        $activeOrder = $cand;
                        break;
                    }
                }
            }

            if ($activeOrder) {
                $unitPrice = $activeOrder->is_fast_track ? $activeOrder->game->effective_fast_track_price : (float) $activeOrder->game->price_per_match;
                $addCount = max(1, (int) floor($amount / max(1, $unitPrice)));

                return $this->addMatchAction->execute(
                    orderId: $activeOrder->id,
                    addCount: $addCount,
                    additionalAmount: $amount,
                    actor: 'Saweria Webhook',
                    remarks: "Top-up via Saweria [{$transactionId}] dari {$donatorName} (+{$addCount} match). Pesan: {$message}"
                );
            }
        }

        return $this->createOrderAction->execute([
            'game_id' => $gameFound->id,
            'customer_name' => $donatorName,
            'in_game_id' => $inGameId,
            'amount_paid' => $amount,
            'is_fast_track' => $isFastTrack,
            'saweria_transaction_id' => $transactionId,
            'notes' => '[Saweria Donation] ' . $message,
        ], 'Saweria Webhook');
    }
}
