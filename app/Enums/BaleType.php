<?php

namespace App\Enums;

enum BaleType: string
{
    case Round = 'Round';
    case SmallSquare = 'Small Square';
    case LargeSquare = 'Large Square';

    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->value])
            ->toArray();
    }
}
