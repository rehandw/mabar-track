<?php

namespace App\Livewire;

use App\Actions\Orders\AddMatchAction;
use App\Actions\Orders\CancelOrderAction;
use App\Actions\Orders\CompleteOrderAction;
use App\Actions\Orders\CreateOrderAction;
use App\Actions\Orders\DecrementMatchAction;
use App\Actions\Orders\SetPlayingAction;
use App\Actions\Saweria\ParseSaweriaDonationAction;
use App\Enums\OrderStatus;
use App\Models\Game;
use App\Models\Order;
use App\Models\StreamerSetting;
use Livewire\Component;

class StreamerDashboard extends Component
{
    // Filter
    public string $selectedGameId = 'all';
    public string $search = '';

    // Modals
    public bool $showAddModal = false;
    public bool $showSimulateSaweriaModal = false;
    public bool $showSettingsModal = false;
    public bool $showAddMatchModal = false;
    public ?int $selectedOrderLogsId = null;

    // Add Match Form
    public ?int $addMatchOrderId = null;
    public string $addMatchOrderLabel = '';
    public int $add_match_count = 1;
    public float $add_match_amount = 0;
    public float $add_match_unit_price = 0;

    // Manual Form
    public int $new_game_id = 1;
    public string $new_customer_name = '';
    public string $new_in_game_id = '';
    public string $new_in_game_nickname = '';
    public int $new_total_matches = 1;
    public float $new_amount_paid = 10000;
    public bool $new_is_fast_track = false;
    public string $new_notes = '';

    // Saweria Simulation Form
    public string $saweria_donator = 'ViewerSetia_Gaming';
    public float $saweria_amount = 30000;
    public string $saweria_message = 'MABAR MLBB 98765432(2099) Request Roam bang!';

    // Streamer Settings Form
    public string $streamer_name = '';
    public bool $is_queue_open = true;
    public string $announcement = '';
    public string $channel_url = '';

    public function mount(): void
    {
        $setting = StreamerSetting::current();
        $this->streamer_name = $setting->streamer_name;
        $this->is_queue_open = $setting->is_queue_open;
        $this->announcement = $setting->announcement ?? '';
        $this->channel_url = $setting->channel_url ?? '';

        $firstGame = Game::where('is_active', true)->first();
        if ($firstGame) {
            $this->new_game_id = $firstGame->id;
            $this->new_amount_paid = (float) $firstGame->price_per_match;
        }
    }

    public function updatedNewGameId($val): void
    {
        $game = Game::find($val);
        if ($game) {
            $unitPrice = $this->new_is_fast_track ? $game->effective_fast_track_price : (float) $game->price_per_match;
            $this->new_amount_paid = (float) ($unitPrice * max(1, $this->new_total_matches));
        }
    }

    public function updatedNewTotalMatches($val): void
    {
        $game = Game::find($this->new_game_id);
        if ($game) {
            $unitPrice = $this->new_is_fast_track ? $game->effective_fast_track_price : (float) $game->price_per_match;
            $this->new_amount_paid = (float) ($unitPrice * max(1, (int) $val));
        }
    }

    public function updatedNewIsFastTrack($val): void
    {
        $game = Game::find($this->new_game_id);
        if ($game) {
            $unitPrice = $val ? $game->effective_fast_track_price : (float) $game->price_per_match;
            $this->new_amount_paid = (float) ($unitPrice * max(1, (int) $this->new_total_matches));
        }
    }

    public function toggleQueueStatus(): void
    {
        $setting = StreamerSetting::current();
        $setting->is_queue_open = !$setting->is_queue_open;
        $setting->save();
        $this->is_queue_open = $setting->is_queue_open;

        $msg = $this->is_queue_open ? 'Status antrean dibuka!' : 'Status antrean ditutup sementara.';
        $this->dispatch('notify', message: $msg, type: 'info');
    }

    public function decrementMatch(int $orderId, DecrementMatchAction $action): void
    {
        try {
            $order = $action->execute($orderId, actor: 'Streamer HUD');
            if ($order->remaining_matches === 0) {
                $this->dispatch('notify', message: "Order #{$order->order_number} ({$order->customer_name}) SELESAI!", type: 'success');
            } else {
                $this->dispatch('notify', message: "Match selesai! Sisa {$order->remaining_matches} game untuk {$order->customer_name}.", type: 'success');
            }
        } catch (\Throwable $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function setPlaying(int $orderId, SetPlayingAction $action): void
    {
        try {
            $order = $action->execute($orderId, actor: 'Streamer HUD');
            $this->dispatch('notify', message: "{$order->customer_name} ({$order->in_game_id}) sekarang SEDANG MAIN!", type: 'info');
        } catch (\Throwable $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function completeOrder(int $orderId, CompleteOrderAction $action): void
    {
        try {
            $order = $action->execute($orderId, actor: 'Streamer HUD');
            $this->dispatch('notify', message: "Order #{$order->order_number} berhasil diselesaikan.", type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function cancelOrder(int $orderId, CancelOrderAction $action): void
    {
        try {
            $order = $action->execute($orderId, actor: 'Streamer HUD', reason: 'Dibatalkan oleh Streamer');
            $this->dispatch('notify', message: "Order #{$order->order_number} telah dibatalkan.", type: 'warning');
        } catch (\Throwable $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function createManualOrder(CreateOrderAction $action): void
    {
        $this->validate([
            'new_game_id' => 'required|exists:games,id',
            'new_customer_name' => 'required|string|max:100',
            'new_in_game_id' => 'required|string|max:100',
            'new_total_matches' => 'required|integer|min:1|max:50',
            'new_amount_paid' => 'required|numeric|min:0',
        ]);

        $order = $action->execute([
            'game_id' => $this->new_game_id,
            'customer_name' => $this->new_customer_name,
            'in_game_id' => $this->new_in_game_id,
            'in_game_nickname' => $this->new_in_game_nickname ?: null,
            'total_matches' => $this->new_total_matches,
            'amount_paid' => $this->new_amount_paid,
            'is_fast_track' => $this->new_is_fast_track,
            'notes' => $this->new_notes ?: null,
        ], actor: 'Manual Entry');

        $this->showAddModal = false;
        $this->new_customer_name = '';
        $this->new_in_game_id = '';
        $this->new_in_game_nickname = '';
        $this->new_notes = '';
        $this->new_is_fast_track = false;

        $fastTrackText = $order->is_fast_track ? ' (⚡ FAST TRACK VIP)' : '';
        $this->dispatch('notify', message: "Order #{$order->order_number}{$fastTrackText} berhasil ditambahkan ke antrean!", type: 'success');
    }

    public function toggleFastTrack(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        $order->is_fast_track = !$order->is_fast_track;
        $order->save();

        $statusStr = $order->is_fast_track ? 'dinaikkan ke ⚡ FAST TRACK VIP' : 'dikembalikan ke antrean Reguler';
        $this->dispatch('notify', message: "Order #{$order->order_number} ({$order->customer_name}) {$statusStr}!", type: 'info');
    }

    public function openAddMatchModal(int $orderId): void
    {
        $order = Order::with('game')->findOrFail($orderId);
        $this->addMatchOrderId = $order->id;
        $this->addMatchOrderLabel = "{$order->customer_name} ({$order->order_number})";
        $this->add_match_count = 1;
        $this->add_match_unit_price = (float) $order->game->price_per_match;
        $this->add_match_amount = $this->add_match_unit_price;
        $this->showAddMatchModal = true;
    }

    public function updatedAddMatchCount($val): void
    {
        $this->add_match_amount = (float) ($this->add_match_unit_price * max(1, (int) $val));
    }

    public function addMatchToOrder(AddMatchAction $action): void
    {
        $this->validate([
            'addMatchOrderId' => 'required|exists:orders,id',
            'add_match_count' => 'required|integer|min:1|max:50',
            'add_match_amount' => 'required|numeric|min:0',
        ]);

        try {
            $order = $action->execute(
                orderId: $this->addMatchOrderId,
                addCount: $this->add_match_count,
                additionalAmount: $this->add_match_amount,
                actor: 'Streamer HUD',
            );

            $this->showAddMatchModal = false;
            $this->addMatchOrderId = null;
            $this->dispatch('notify', message: "+{$this->add_match_count} match ditambahkan ke Order #{$order->order_number} ({$order->customer_name}). Sisa: {$order->remaining_matches} game.", type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function closeAddMatchModal(): void
    {
        $this->showAddMatchModal = false;
        $this->addMatchOrderId = null;
    }

    public function simulateSaweria(ParseSaweriaDonationAction $action): void
    {
        $this->validate([
            'saweria_donator' => 'required|string|max:100',
            'saweria_amount' => 'required|numeric|min:1000',
            'saweria_message' => 'required|string|max:255',
        ]);

        $order = $action->execute([
            'donator_name' => $this->saweria_donator,
            'amount' => $this->saweria_amount,
            'message' => $this->saweria_message,
            'transaction_id' => 'SAW-SIM-' . time(),
        ]);

        $this->showSimulateSaweriaModal = false;
        $this->dispatch('notify', message: "Donasi Saweria diterima! Rp " . number_format($this->saweria_amount) . " dari {$this->saweria_donator} otomatis masuk antrean.", type: 'success');
    }

    public function saveSettings(): void
    {
        $setting = StreamerSetting::current();
        $setting->update([
            'streamer_name' => $this->streamer_name,
            'announcement' => $this->announcement,
            'channel_url' => $this->channel_url,
        ]);

        $this->showSettingsModal = false;
        $this->dispatch('notify', message: 'Pengaturan streamer berhasil disimpan!', type: 'success');
    }

    public function viewLogs(int $orderId): void
    {
        $this->selectedOrderLogsId = $orderId;
    }

    public function closeLogs(): void
    {
        $this->selectedOrderLogsId = null;
    }

    public function render()
    {
        $setting = StreamerSetting::current();
        $games = Game::where('is_active', true)->get();

        // Query active playing orders
        $playingOrdersQuery = Order::with('game')
            ->where('status', OrderStatus::PLAYING->value)
            ->orderBy('started_playing_at', 'desc');

        // Query queued orders (Fast Track prioritised at the top)
        $queuedOrdersQuery = Order::with('game')
            ->where('status', OrderStatus::QUEUED->value)
            ->orderBy('is_fast_track', 'desc')
            ->orderBy('queue_position', 'asc')
            ->orderBy('created_at', 'asc');

        // Query completed orders
        $completedOrdersQuery = Order::with('game')
            ->where('status', OrderStatus::COMPLETED->value)
            ->orderBy('completed_at', 'desc');

        if ($this->selectedGameId !== 'all') {
            $playingOrdersQuery->where('game_id', $this->selectedGameId);
            $queuedOrdersQuery->where('game_id', $this->selectedGameId);
            $completedOrdersQuery->where('game_id', $this->selectedGameId);
        }

        if ($this->search) {
            $term = '%' . $this->search . '%';
            $filter = function ($q) use ($term) {
                $q->where('customer_name', 'like', $term)
                  ->orWhere('in_game_id', 'like', $term)
                  ->orWhere('order_number', 'like', $term);
            };
            $playingOrdersQuery->where($filter);
            $queuedOrdersQuery->where($filter);
            $completedOrdersQuery->where($filter);
        }

        $playingOrders = $playingOrdersQuery->get();
        $queuedOrders = $queuedOrdersQuery->get();
        $completedOrders = $completedOrdersQuery->take(10)->get();

        // Global stats
        $totalEarnings = Order::whereIn('status', [OrderStatus::PLAYING->value, OrderStatus::COMPLETED->value])->sum('amount_paid');
        $totalMatchesPlayed = Order::sum('total_matches') - Order::sum('remaining_matches');

        $selectedOrderLogs = null;
        if ($this->selectedOrderLogsId) {
            $selectedOrderLogs = Order::with(['matchLogs', 'game'])->find($this->selectedOrderLogsId);
        }

        return view('livewire.streamer-dashboard', [
            'setting' => $setting,
            'games' => $games,
            'playingOrders' => $playingOrders,
            'queuedOrders' => $queuedOrders,
            'completedOrders' => $completedOrders,
            'totalEarnings' => $totalEarnings,
            'totalMatchesPlayed' => $totalMatchesPlayed,
            'selectedOrderLogs' => $selectedOrderLogs,
        ])->layout('components.layouts.app', ['title' => 'Streamer Command Center - MabarTrack']);
    }
}
