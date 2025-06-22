<?php

namespace App\Filament\Resources\StudListingResource\Pages;

use App\Filament\Resources\StudListingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudListing extends EditRecord
{
    protected static string $resource = StudListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
