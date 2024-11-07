<?php

namespace App\Enums;

enum SalesUnit: string
{
    case EXE = 'extraordinary';
    case EYE = 'everyhome';
    case EVE = 'elanvital';

    public function booking_server(): string
    {
        return match ($this) {
            SalesUnit::EXE => 'book.extraordinaryenclaves.ph',
            SalesUnit::EYE => 'book.everyhomeenclaves.ph',
            SalesUnit::EVE => 'book.elanvitalenclaves.ph'
        };
    }

    public function campaign_code(): string
    {
        return match ($this) {
            SalesUnit::EXE => config('funnel.campaign_code')[SalesUnit::EXE->name],
            SalesUnit::EYE => config('funnel.campaign_code')[SalesUnit::EYE->name],
            SalesUnit::EVE => config('funnel.campaign_code')[SalesUnit::EVE->name],
        };
    }

    public static function random(): self
    {
        $count = count(self::cases()) - 1;

        return self::cases()[rand(0, $count)];
    }
}
