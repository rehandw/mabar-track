<?php

use App\Http\Controllers\SaweriaWebhookController;
use App\Livewire\ObsOverlay;
use App\Livewire\PublicQueue;
use App\Livewire\StreamerDashboard;
use Illuminate\Support\Facades\Route;

// 1. Dashboard Utama Streamer & Moderator (Cockpit Mabar)
Route::get('/', StreamerDashboard::class)->name('streamer.dashboard');

// 2. Layar Transparan untuk Browser Source OBS Studio
Route::get('/overlay/{token}', ObsOverlay::class)->name('obs.overlay');

// 3. Pelacak Antrean Publik (Untuk Penonton di HP / Chat Link)
Route::get('/queue', PublicQueue::class)->name('public.queue');

// 4. Webhook Penerima Notifikasi Donasi Saweria
Route::post('/api/saweria/webhook', [SaweriaWebhookController::class, 'handle'])->name('saweria.webhook');
