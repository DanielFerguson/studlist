<?php

namespace App\Enums;

enum Colour: string
{
    case Black = 'Black';
    case Red = 'Red';
    case White = 'White';
    case Brown = 'Brown';
    case Grey = 'Grey';
    case Dun = 'Dun';
    case Roan = 'Roan';
    case Fawn = 'Fawn';
    case Cream = 'Cream';
    case Yellow = 'Yellow';
    case Blue = 'Blue';
    case Brindle = 'Brindle';
    case Mottle = 'Mottle';
    case Speckled = 'Speckled';
    case Spotted = 'Spotted';
    case Pied = 'Pied';
    case Tan = 'Tan';
    case Chocolate = 'Chocolate';
    case Tawny = 'Tawny';
    case Other = 'Other';

    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->value])
            ->toArray();
    }
}





