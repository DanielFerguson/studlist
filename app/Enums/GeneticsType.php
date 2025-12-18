<?php

namespace App\Enums;

enum GeneticsType: string
{
    case SemenStraws = 'Semen Straws';
    case Embryos = 'Embryos';

    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->value])
            ->toArray();
    }
}


