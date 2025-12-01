<?php

namespace App\Enums;

enum EquipmentCondition: string
{
    case New = 'New';
    case LikeNew = 'Like New';
    case Good = 'Good';
    case Fair = 'Fair';
    case Poor = 'Poor';

    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->value])
            ->toArray();
    }
}





