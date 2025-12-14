<?php

namespace App\Filament\Resources\ServiceListingResource\Pages;

use App\Filament\Resources\ServiceListingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceListing extends EditRecord
{
    protected static string $resource = ServiceListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
