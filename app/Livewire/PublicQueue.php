<?php

namespace App\Livewire;

use App\Enums\OrderStatus;
use App\Models\Game;
use App\Models\Order;
use App\Models\StreamerSetting;
use Livewire\Component;

class PublicQueue extends Component
{
    public string $searchId = '';

    public function render()
    {
        $setting = StreamerSetting::current();
        $games = Game::where('is_active', true)->get();

        $playingOrders = Order::with('game')
            ->where('status', OrderStatus::PLAYING->value)
            ->orderBy('started_playing_at', 'desc')
            ->get();

        $queuedOrdersQuery = Order::with('game')
            ->where('status', OrderStatus::QUEUED->value)
            ->orderBy('is_fast_track', 'desc')
            ->orderBy('queue_position', 'asc')
            ->orderBy('created_at', 'asc');

        $queuedOrders = $queuedOrdersQuery->get();

        // Cari posisi user jika input search diisi
        $userSearchResult = null;
        if (!empty(trim($this->searchId))) {
            $term = strtolower(trim($this->searchId));
            foreach ($queuedOrders as $index => $item) {
                if (str_contains(strtolower($item->in_game_id), $term) || str_contains(strtolower($item->customer_name), $term)) {
                    $userSearchResult = [
                        'position' => $index + 1,
                        'order' => $item,
                    ];
                    break;
                }
            }
        }

        return view('livewire.public-queue', [
            'setting' => $setting,
            'games' => $games,
            'playingOrders' => $playingOrders,
            'queuedOrders' => $queuedOrders,
            'userSearchResult' => $userSearchResult,
        ])->layout('components.layouts.app', ['title' => 'Cek Status Antrean Mabar - ' . $setting->streamer_name]);
    }
}
