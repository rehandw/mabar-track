<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case VERIFIED = 'verified';
    case PENDING = 'pending';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::VERIFIED => 'Terverifikasi (Saweria/Manual)',
            self::PENDING => 'Menunggu Verifikasi',
            self::REFUNDED => 'Dikembalikan (Refund)',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::VERIFIED => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30',
            self::PENDING => 'bg-amber-500/10 text-amber-400 border border-amber-500/30',
            self::REFUNDED => 'bg-zinc-500/10 text-zinc-400 border border-zinc-500/30',
        };
    }
}

