<?php

namespace App\Enums;

enum OrderStatus: string
{
    case QUEUED = 'queued';
    case PLAYING = 'playing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::QUEUED => 'Menunggu Antrean',
            self::PLAYING => 'Sedang Main',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::QUEUED => 'bg-amber-500/10 text-amber-400 border border-amber-500/30',
            self::PLAYING => 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 animate-pulse',
            self::COMPLETED => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30',
            self::CANCELLED => 'bg-rose-500/10 text-rose-400 border border-rose-500/30',
        };
    }
}

