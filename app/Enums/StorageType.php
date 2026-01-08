<?php

namespace App\Enums;

enum StorageType: string
{
    case Shed = 'Shed';
    case CoveredOutdoor = 'Covered Outdoor';
    case OpenPaddock = 'Open Paddock';

    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->value])
            ->toArray();
    }
}
