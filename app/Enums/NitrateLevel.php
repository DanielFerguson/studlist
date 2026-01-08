<?php

namespace App\Enums;

enum NitrateLevel: string
{
    case Low = 'Low';
    case Medium = 'Medium';
    case High = 'High';

    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->value])
            ->toArray();
    }
}
