<?php

namespace App\Enums;

enum Breed: string
{
    case Angus = 'Angus';
    case Hereford = 'Hereford';
    case Shorthorn = 'Shorthorn';
    case Charolais = 'Charolais';
    case Limousin = 'Limousin';
    case Wagyu = 'Wagyu';
    case MurrayGrey = 'Murray Grey';
    case Simmental = 'Simmental';
    case Brahman = 'Brahman';
    case Brangus = 'Brangus';
    case Droughtmaster = 'Droughtmaster';
    case SantaGertrudis = 'Santa Gertrudis';
    case Beefmaster = 'Beefmaster';
    case Bazadaise = 'Bazadaise';
    case BelgianBlue = 'Belgian Blue';
    case Devon = 'Devon';
    case Gelbvieh = 'Gelbvieh';
    case Galloway = 'Galloway';
    case HerefordPoll = 'Hereford (Poll)';
    case Highland = 'Highland';
    case Jersey = 'Jersey';
    case Longhorn = 'Longhorn';
    case Piedmontese = 'Piedmontese';
    case Salers = 'Salers';
    case SouthDevon = 'South Devon';
    case SpecklePark = 'Speckle Park';
    case TexasLonghorn = 'Texas Longhorn';
    case Other = 'Other';

    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->value])
            ->toArray();
    }
}


