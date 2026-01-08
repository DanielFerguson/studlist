<?php

namespace App\Enums;

enum HayQualityGrade: string
{
    case Premium = 'Premium';
    case AGrade = 'A-Grade';
    case BGrade = 'B-Grade';
    case CGrade = 'C-Grade';
    case Stockfeed = 'Stockfeed';

    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->value])
            ->toArray();
    }
}
