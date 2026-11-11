<?php

namespace Database\Seeders;

use App\Actions\Orders\CreateOrderAction;
use App\Enums\OrderStatus;
use App\Models\Game;
use App\Models\Order;
use App\Models\StreamerSetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Settings
        $setting = StreamerSetting::current();
        $setting->update([
            'streamer_name' => 'Bang Gaming Official',
            'channel_url' => 'https://youtube.com/@bang-gaming-live',
            'is_queue_open' => true,
            'announcement' => '🔥 OPEN JASA MABAR WIN RATE UP! Format Saweria: MABAR [GAME] [ID/SERVER] [HERO/ROLE]',
        ]);

        // 2. Default Games
        $mlbb = Game::firstOrCreate(['slug' => 'mlbb'], [
            'name' => 'Mobile Legends: Bang Bang',
            'category' => 'MOBA Mobile',
            'price_per_match' => 10000.00,
            'id_format_hint' => 'Contoh: 12345678 (2021)',
            'badge_color' => 'cyan',
            'is_active' => true,
        ]);

        $valo = Game::firstOrCreate(['slug' => 'valo'], [
            'name' => 'Valorant',
            'category' => 'Tactical FPS',
            'price_per_match' => 15000.00,
            'id_format_hint' => 'Contoh: AcePlayer#ID1',
            'badge_color' => 'rose',
            'is_active' => true,
        ]);

        $hok = Game::firstOrCreate(['slug' => 'hok'], [
            'name' => 'Honor of Kings',
            'category' => 'MOBA Mobile',
            'price_per_match' => 10000.00,
            'id_format_hint' => 'Contoh: Nickname Akun HOK',
            'badge_color' => 'amber',
            'is_active' => true,
        ]);

        $ff = Game::firstOrCreate(['slug' => 'ff'], [
            'name' => 'Free Fire',
            'category' => 'Battle Royale',
            'price_per_match' => 10000.00,
            'id_format_hint' => 'Contoh: 812938192',
            'badge_color' => 'orange',
            'is_active' => true,
        ]);

        // 3. Sample Orders
        $createAction = app(CreateOrderAction::class);

        // Sedang Main 1 (MLBB - 3 match, sisa 2)
        $order1 = $createAction->execute([
            'game_id' => $mlbb->id,
            'customer_name' => 'Sultan_Aldi',
            'in_game_id' => '84920194 (2104)',
            'in_game_nickname' => 'AldiGanteng99',
            'total_matches' => 3,
            'amount_paid' => 30000,
            'notes' => 'Tolong gendong ke Mythical Glory bang!',
            'saweria_transaction_id' => 'SAW-20261102-001',
        ], actor: 'Saweria Webhook');

        $order1->update([
            'status' => OrderStatus::PLAYING,
            'remaining_matches' => 2,
            'started_playing_at' => now()->subMinutes(18),
        ]);

        // Antrean 1 (MLBB - 2 match)
        $createAction->execute([
            'game_id' => $mlbb->id,
            'customer_name' => 'Rian_Fighter',
            'in_game_id' => '94827102 (2044)',
            'in_game_nickname' => 'RianChouGod',
            'total_matches' => 2,
            'amount_paid' => 20000,
            'notes' => 'Request Chou roam / EXP',
            'saweria_transaction_id' => 'SAW-20261102-002',
        ], actor: 'Saweria Webhook');

        // Antrean 2 (Valorant - 1 match)
        $createAction->execute([
            'game_id' => $valo->id,
            'customer_name' => 'KimiValkyrie',
            'in_game_id' => 'Kimi#JKT',
            'in_game_nickname' => 'Kimi#JKT',
            'total_matches' => 1,
            'amount_paid' => 15000,
            'notes' => 'Main Jett atau Omen ya bang',
            'saweria_transaction_id' => 'SAW-20261102-003',
        ], actor: 'Saweria Webhook');

        // Selesai (Riwayat untuk audit)
        $completedOrder = $createAction->execute([
            'game_id' => $mlbb->id,
            'customer_name' => 'Budi_Santoso',
            'in_game_id' => '10293847 (2001)',
            'in_game_nickname' => 'BudiTigreal',
            'total_matches' => 2,
            'amount_paid' => 20000,
            'notes' => 'Fast game win streak',
            'saweria_transaction_id' => 'SAW-20261102-000',
        ], actor: 'Saweria Webhook');

        $completedOrder->update([
            'status' => OrderStatus::COMPLETED,
            'remaining_matches' => 0,
            'started_playing_at' => now()->subHours(1),
            'completed_at' => now()->subMinutes(25),
        ]);
    }
}
