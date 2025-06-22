<?php

namespace App\Filament\Resources\GeneticsListingResource\Pages;

use App\Filament\Resources\GeneticsListingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGeneticsListing extends EditRecord
{
    protected static string $resource = GeneticsListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
