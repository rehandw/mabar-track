<?php

namespace App\Livewire;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\StreamerSetting;
use Livewire\Component;

class ObsOverlay extends Component
{
    public string $token = '';

    public function mount(string $token): void
    {
        $this->token = $token;
    }

    public function render()
    {
        $setting = StreamerSetting::where('overlay_token', $this->token)->first();

        if (!$setting) {
            abort(403, 'Akses OBS Overlay tidak valid (Invalid Token).');
        }

        $activeOrder = Order::with('game')
            ->where('status', OrderStatus::PLAYING->value)
            ->orderBy('started_playing_at', 'desc')
            ->first();

        $nextQueued = Order::with('game')
            ->where('status', OrderStatus::QUEUED->value)
            ->orderBy('is_fast_track', 'desc')
            ->orderBy('queue_position', 'asc')
            ->orderBy('created_at', 'asc')
            ->take(3)
            ->get();

        return view('livewire.obs-overlay', [
            'setting' => $setting,
            'activeOrder' => $activeOrder,
            'nextQueued' => $nextQueued,
        ])->layout('components.layouts.overlay');
    }
}
