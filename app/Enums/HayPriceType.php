<?php

namespace App\Enums;

enum HayPriceType: string
{
    case PerBale = 'Per Bale';
    case PerTonne = 'Per Tonne';
    case Negotiable = 'Negotiable';

    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->value])
            ->toArray();
    }
}
