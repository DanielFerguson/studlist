<?php

namespace App\Filament\Resources\HayListingResource\Pages;

use App\Filament\Resources\HayListingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHayListing extends EditRecord
{
    protected static string $resource = HayListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
