<?php

namespace App\Providers;

use App\Models\GeneticsListing;
use App\Models\HayListing;
use App\Models\ServiceListing;
use App\Models\ShowEquipmentListing;
use App\Models\SteerListing;
use App\Models\StudListing;
use App\Policies\GeneticsListingPolicy;
use App\Policies\HayListingPolicy;
use App\Policies\ServiceListingPolicy;
use App\Policies\ShowEquipmentListingPolicy;
use App\Policies\SteerListingPolicy;
use App\Policies\StudListingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        SteerListing::class => SteerListingPolicy::class,
        StudListing::class => StudListingPolicy::class,
        GeneticsListing::class => GeneticsListingPolicy::class,
        ShowEquipmentListing::class => ShowEquipmentListingPolicy::class,
        ServiceListing::class => ServiceListingPolicy::class,
        HayListing::class => HayListingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
