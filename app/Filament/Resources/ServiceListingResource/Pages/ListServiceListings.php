<?php

namespace App\Filament\Resources\ServiceListingResource\Pages;

use App\Filament\Resources\ServiceListingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceListings extends ListRecords
{
    protected static string $resource = ServiceListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
