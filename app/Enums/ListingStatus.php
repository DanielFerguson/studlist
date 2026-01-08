<?php

namespace App\Enums;

enum ListingStatus: string
{
    case Draft = 'draft';
    case Active = 'active';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Active => 'Active',
        };
    }

    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}




