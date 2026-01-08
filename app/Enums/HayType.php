<?php

namespace App\Enums;

enum HayType: string
{
    case Lucerne = 'Lucerne';
    case Grass = 'Grass';
    case Oaten = 'Oaten';
    case Meadow = 'Meadow';
    case Clover = 'Clover';
    case Mixed = 'Mixed';
    case Wheaten = 'Wheaten';
    case Barley = 'Barley';
    case Sorghum = 'Sorghum';
    case Other = 'Other';

    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->value])
            ->toArray();
    }
}
