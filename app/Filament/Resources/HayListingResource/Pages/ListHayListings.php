<?php

namespace App\Filament\Resources\HayListingResource\Pages;

use App\Filament\Resources\HayListingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHayListings extends ListRecords
{
    protected static string $resource = HayListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
