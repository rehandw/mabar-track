<?php

namespace App\Http\Controllers;

use App\Actions\Saweria\ParseSaweriaDonationAction;
use App\Models\StreamerSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaweriaWebhookController extends Controller
{
    public function handle(Request $request, ParseSaweriaDonationAction $parser): JsonResponse
    {
        $payload = $request->all();
        Log::info('Saweria Webhook Received:', $payload);

        // Validasi opsional stream key / token dari header atau query
        $streamKey = $request->header('X-Saweria-Key') ?? $request->query('key');
        $setting = StreamerSetting::current();

        if ($streamKey && $setting->saweria_stream_key && $streamKey !== $setting->saweria_stream_key) {
            return response()->json(['error' => 'Unauthorized Stream Key'], 401);
        }

        // Ekstraksi field saweria
        $donatorName = $request->input('donator_name') ?? $request->input('name') ?? 'Donatur Saweria';
        $amount = (float) ($request->input('amount_raw') ?? $request->input('amount') ?? 0);
        $message = $request->input('message') ?? '';
        $transId = $request->input('id') ?? $request->input('transaction_id');

        if ($amount <= 0) {
            return response()->json(['status' => 'ignored', 'message' => 'Amount must be greater than 0'], 200);
        }

        try {
            $order = $parser->execute([
                'donator_name' => $donatorName,
                'amount' => $amount,
                'message' => $message,
                'transaction_id' => $transId,
            ]);

            return response()->json([
                'status' => 'success',
                'order_number' => $order->order_number,
                'customer' => $order->customer_name,
                'total_matches' => $order->total_matches,
            ]);
        } catch (\Throwable $e) {
            Log::error('Saweria Webhook Processing Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

