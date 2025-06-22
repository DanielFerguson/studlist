<?php

namespace App\Filament\Widgets;

use App\Models\GeneticsListing;
use App\Models\ShowEquipmentListing;
use App\Models\SteerListing;
use App\Models\StudListing;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ListingsStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Steer Listings', SteerListing::count())
                ->description(SteerListing::where('status', 'active')->count().' active')
                ->descriptionIcon('heroicon-m-star')
                ->color('primary'),

            Stat::make('Stud Listings', StudListing::count())
                ->description(StudListing::where('status', 'active')->count().' active')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('warning'),

            Stat::make('Genetics Listings', GeneticsListing::count())
                ->description('Available genetics')
                ->descriptionIcon('heroicon-m-beaker')
                ->color('info'),

            Stat::make('Show Equipment', ShowEquipmentListing::count())
                ->description('Equipment listings')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('gray'),

            Stat::make('Active Subscriptions',
                SteerListing::where('status', 'active')->count() +
                StudListing::where('status', 'active')->count()
            )
                ->description('Paid listings')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
