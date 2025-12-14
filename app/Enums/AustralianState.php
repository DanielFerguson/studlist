<?php

namespace App\Enums;

enum AustralianState: string
{
    case ACT = 'ACT';
    case NSW = 'NSW';
    case NT = 'NT';
    case QLD = 'QLD';
    case SA = 'SA';
    case TAS = 'TAS';
    case VIC = 'VIC';
    case WA = 'WA';

    public function label(): string
    {
        return match ($this) {
            self::ACT => 'Australian Capital Territory',
            self::NSW => 'New South Wales',
            self::NT => 'Northern Territory',
            self::QLD => 'Queensland',
            self::SA => 'South Australia',
            self::TAS => 'Tasmania',
            self::VIC => 'Victoria',
            self::WA => 'Western Australia',
        };
    }

    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}

