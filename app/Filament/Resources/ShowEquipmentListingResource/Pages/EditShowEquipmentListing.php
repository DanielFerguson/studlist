<?php

namespace App\Filament\Resources\ShowEquipmentListingResource\Pages;

use App\Filament\Resources\ShowEquipmentListingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShowEquipmentListing extends EditRecord
{
    protected static string $resource = ShowEquipmentListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
