<?php

namespace Tests\Feature;

use App\Actions\Orders\AddMatchAction;
use App\Actions\Orders\CreateOrderAction;
use App\Actions\Orders\DecrementMatchAction;
use App\Enums\OrderStatus;
use App\Models\Game;
use App\Models\Order;
use App\Models\StreamerSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MabarTrackTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_dashboard_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('MabarTrack');
        $response->assertSee('Alur Antrean Mabar Streamer');
        $response->assertSee('SEDANG MAIN');
    }

    public function test_public_queue_page_can_be_rendered(): void
    {
        $response = $this->get('/queue');
        $response->assertStatus(200);
        $response->assertSee('Cek Nomor Giliran Anda');
    }

    public function test_obs_overlay_loads_with_valid_token(): void
    {
        $setting = StreamerSetting::current();
        $response = $this->get('/overlay/' . $setting->overlay_token);
        $response->assertStatus(200);
        $response->assertSee('MABAR HUD');
    }

    public function test_obs_overlay_fails_with_invalid_token(): void
    {
        $response = $this->get('/overlay/invalid-random-token-12345');
        $response->assertStatus(403);
    }

    public function test_decrement_match_action_reduces_remaining_matches(): void
    {
        $game = Game::where('slug', 'mlbb')->first();
        $createAction = app(CreateOrderAction::class);
        $decrementAction = app(DecrementMatchAction::class);

        $order = $createAction->execute([
            'game_id' => $game->id,
            'customer_name' => 'TestPlayer',
            'in_game_id' => '12345678',
            'total_matches' => 2,
            'amount_paid' => 20000,
        ]);

        $this->assertEquals(2, $order->remaining_matches);
        $this->assertEquals(OrderStatus::QUEUED, $order->status);

        // 1st decrement
        $order = $decrementAction->execute($order->id, actor: 'Unit Test');
        $this->assertEquals(1, $order->remaining_matches);
        $this->assertEquals(OrderStatus::PLAYING, $order->status);

        // 2nd decrement (should automatically complete)
        $order = $decrementAction->execute($order->id, actor: 'Unit Test');
        $this->assertEquals(0, $order->remaining_matches);
        $this->assertEquals(OrderStatus::COMPLETED, $order->status);
        $this->assertNotNull($order->completed_at);

        // Verify audit logs were created
        $this->assertDatabaseHas('order_match_logs', [
            'order_id' => $order->id,
            'action' => 'DECREMENT_MATCH',
            'matches_before' => 2,
            'matches_after' => 1,
        ]);
        $this->assertDatabaseHas('order_match_logs', [
            'order_id' => $order->id,
            'action' => 'DECREMENT_MATCH',
            'matches_before' => 1,
            'matches_after' => 0,
        ]);
    }

    public function test_saweria_webhook_creates_order_and_calculates_matches(): void
    {
        $response = $this->postJson('/api/saweria/webhook', [
            'id' => 'SAW-TEST-999',
            'amount_raw' => 30000, // MLBB price is 10,000 -> 3 matches
            'donator_name' => 'SultanTest',
            'message' => 'MABAR MLBB 99887766(2021) mau main roam dong',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'customer' => 'SultanTest',
            'total_matches' => 3,
        ]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'SultanTest',
            'in_game_id' => '99887766(2021)',
            'total_matches' => 3,
            'remaining_matches' => 3,
            'status' => OrderStatus::QUEUED->value,
            'saweria_transaction_id' => 'SAW-TEST-999',
        ]);
    }

    public function test_add_match_action_increases_matches_and_reverts_completed_order(): void
    {
        $game = Game::where('slug', 'mlbb')->first();
        $createAction = app(CreateOrderAction::class);
        $addMatchAction = app(AddMatchAction::class);

        $order = $createAction->execute([
            'game_id' => $game->id,
            'customer_name' => 'TestAddMatchUser',
            'in_game_id' => '55554444',
            'total_matches' => 1,
            'amount_paid' => 10000,
        ]);

        // Manually complete the order
        $order->update([
            'status' => OrderStatus::COMPLETED,
            'remaining_matches' => 0,
            'completed_at' => now(),
        ]);

        $this->assertEquals(OrderStatus::COMPLETED, $order->status);
        $this->assertEquals(0, $order->remaining_matches);

        // Add 2 matches (+Rp 20.000)
        $updatedOrder = $addMatchAction->execute(
            orderId: $order->id,
            addCount: 2,
            additionalAmount: 20000,
            actor: 'Streamer Test',
            remarks: 'Tambah 2 match untuk extend live'
        );

        $this->assertEquals(3, $updatedOrder->total_matches);
        $this->assertEquals(2, $updatedOrder->remaining_matches);
        $this->assertEquals(30000, (float) $updatedOrder->amount_paid);
        $this->assertEquals(OrderStatus::PLAYING, $updatedOrder->status);
        $this->assertNull($updatedOrder->completed_at);

        $this->assertDatabaseHas('order_match_logs', [
            'order_id' => $order->id,
            'action' => 'ADD_MATCH',
            'matches_before' => 0,
            'matches_after' => 2,
            'actor_name' => 'Streamer Test',
        ]);
    }

    public function test_saweria_webhook_extends_matches_when_topup_message_received(): void
    {
        $game = Game::where('slug', 'mlbb')->first();
        $createAction = app(CreateOrderAction::class);

        // Create an existing active order
        $order = $createAction->execute([
            'game_id' => $game->id,
            'customer_name' => 'ExistingPlayer',
            'in_game_id' => '77889900',
            'total_matches' => 1,
            'amount_paid' => 10000,
        ]);

        $this->assertEquals(1, $order->total_matches);

        // Simulate Saweria donation with TAMBAH keyword and matching ID
        $response = $this->postJson('/api/saweria/webhook', [
            'id' => 'SAW-TOPUP-001',
            'amount_raw' => 20000, // 2 extra matches
            'donator_name' => 'ExistingPlayer',
            'message' => 'TAMBAH MLBB 77889900 nambah 2 match lagi ya bang',
        ]);

        $response->assertStatus(200);

        $order->refresh();
        $this->assertEquals(3, $order->total_matches);
        $this->assertEquals(3, $order->remaining_matches);
        $this->assertEquals(30000, (float) $order->amount_paid);

        $this->assertDatabaseHas('order_match_logs', [
            'order_id' => $order->id,
            'action' => 'ADD_MATCH',
            'matches_before' => 1,
            'matches_after' => 3,
            'actor_name' => 'Saweria Webhook',
        ]);
    }
}

