<?php

namespace App\Enums;

enum ServiceType: string
{
    case Clipping = 'Clipping';
    case Fitting = 'Fitting';
    case Photography = 'Photography';
    case Transport = 'Transport';
    case Veterinary = 'Veterinary';
    case FeedSupplier = 'Feed Supplier';
    case ShowPreparation = 'Show Preparation';
    case Other = 'Other';

    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->value])
            ->toArray();
    }
}

