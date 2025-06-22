<?php

namespace App\Filament\Resources\SteerListingResource\Pages;

use App\Filament\Resources\SteerListingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSteerListing extends EditRecord
{
    protected static string $resource = SteerListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
