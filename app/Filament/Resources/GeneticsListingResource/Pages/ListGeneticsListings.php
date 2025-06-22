<?php

namespace App\Filament\Resources\GeneticsListingResource\Pages;

use App\Filament\Resources\GeneticsListingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeneticsListings extends ListRecords
{
    protected static string $resource = GeneticsListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
