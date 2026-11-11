<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StreamerSetting extends Model
{
    protected $fillable = [
        'streamer_name',
        'channel_url',
        'is_queue_open',
        'overlay_token',
        'announcement',
        'saweria_stream_key',
    ];

    protected $casts = [
        'is_queue_open' => 'boolean',
    ];

    public static function current(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'streamer_name' => 'Bang Gaming Live',
                'channel_url' => 'https://youtube.com/@mabar-gaming',
                'is_queue_open' => true,
                'overlay_token' => Str::random(32),
                'announcement' => 'Open Jasa Mabar! Donasi Saweria otomatis masuk antrean.',
                'saweria_stream_key' => Str::random(24),
            ]
        );
    }
}

