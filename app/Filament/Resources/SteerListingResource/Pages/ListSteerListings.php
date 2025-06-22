<?php

namespace App\Filament\Resources\SteerListingResource\Pages;

use App\Filament\Resources\SteerListingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSteerListings extends ListRecords
{
    protected static string $resource = SteerListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
