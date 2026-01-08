<?php

namespace App\Enums;

enum SeasonCut: string
{
    case FirstCut = '1st Cut';
    case SecondCut = '2nd Cut';
    case ThirdCut = '3rd Cut';
    case FourthCut = '4th Cut';
    case MultipleCuts = 'Multiple Cuts';

    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->value])
            ->toArray();
    }
}
