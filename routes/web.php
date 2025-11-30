<?php

use App\Http\Controllers\GeneticsListingController;
use App\Http\Controllers\ServiceListingController;
use App\Http\Controllers\ShowEquipmentListingController;
use App\Http\Controllers\SteerListingController;
use App\Http\Controllers\StudListingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;
use App\Models\GeneticsListing;
use App\Models\ServiceListing;
use App\Models\ShowEquipmentListing;
use App\Models\SteerListing;
use App\Models\StudListing;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $steerListings = SteerListing::where('status', 'active')
        ->with('user')
        ->latest()
        ->take(6)
        ->get();

    $studListings = StudListing::where('status', 'active')
        ->with('user')
        ->latest()
        ->take(6)
        ->get();

    // Genetics and Show Equipment listings are free - no status filter needed
    $geneticsListings = GeneticsListing::with('user')
        ->latest()
        ->take(4)
        ->get();

    $showEquipmentListings = ShowEquipmentListing::with('user')
        ->latest()
        ->take(4)
        ->get();

    return Inertia::render('welcome', [
        'steerListings' => $steerListings,
        'studListings' => $studListings,
        'geneticsListings' => $geneticsListings,
        'showEquipmentListings' => $showEquipmentListings,
    ]);
})->name('home');

// Stripe webhook route (must be outside auth middleware)
Route::post('stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('cashier.webhook');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $steerListings = SteerListing::where('user_id', auth()->user()->id)
            ->with(['user.subscriptions' => function ($query) {
                $query->select('id', 'user_id', 'name', 'stripe_id', 'stripe_status', 'created_at', 'updated_at', 'ends_at');
            }])
            ->latest()
            ->get()
            ->map(function ($listing) {
                // Add subscription info to each listing
                $listing->subscription_info = $listing->laravelSubscription();
                $listing->type = 'steer';

                return $listing;
            });

        $studListings = StudListing::where('user_id', auth()->user()->id)
            ->with(['user.subscriptions' => function ($query) {
                $query->select('id', 'user_id', 'name', 'stripe_id', 'stripe_status', 'created_at', 'updated_at', 'ends_at');
            }])
            ->latest()
            ->get()
            ->map(function ($listing) {
                // Add subscription info to each listing
                $listing->subscription_info = $listing->laravelSubscription();
                $listing->type = 'stud';

                return $listing;
            });

        $geneticsListings = GeneticsListing::where('user_id', auth()->user()->id)
            ->latest()
            ->get();

        $showEquipmentListings = ShowEquipmentListing::where('user_id', auth()->user()->id)
            ->latest()
            ->get();

        $serviceListings = ServiceListing::where('user_id', auth()->user()->id)
            ->latest()
            ->get();

        return Inertia::render('dashboard', [
            'steerListings' => $steerListings,
            'studListings' => $studListings,
            'geneticsListings' => $geneticsListings,
            'showEquipmentListings' => $showEquipmentListings,
            'serviceListings' => $serviceListings,
        ]);
    })->name('dashboard');

    Route::resource('steers', SteerListingController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('studs', StudListingController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('genetics', GeneticsListingController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('show-equipment', ShowEquipmentListingController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('services', ServiceListingController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

    // Subscription routes
    Route::prefix('subscriptions')->name('subscription.')->group(function () {
        Route::match(['get', 'post'], 'checkout/{steer}', [SubscriptionController::class, 'checkout'])->name('checkout');
        Route::get('success/{steer}', [SubscriptionController::class, 'success'])->name('success');
        Route::post('cancel/{steer}', [SubscriptionController::class, 'cancel'])->name('cancel');

        Route::match(['get', 'post'], 'checkout-stud/{stud}', [SubscriptionController::class, 'checkoutStud'])->name('checkout-stud');
        Route::get('success-stud/{stud}', [SubscriptionController::class, 'successStud'])->name('success-stud');
        Route::post('cancel-stud/{stud}', [SubscriptionController::class, 'cancelStud'])->name('cancel-stud');

        Route::get('billing-portal', [SubscriptionController::class, 'billingPortal'])->name('billing-portal');
    });
});

// Public search route
Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');

// About Us page
Route::get('/about', function () {
    return Inertia::render('about');
})->name('about');

// Public listing view routes
Route::get('/steers/{steer}', [SteerListingController::class, 'show'])->name('steers.show');
Route::get('/studs/{stud}', [StudListingController::class, 'show'])->name('studs.show');
Route::get('/genetics/{genetic}', [GeneticsListingController::class, 'show'])->name('genetics.show');
Route::get('/equipment/{equipment}', [ShowEquipmentListingController::class, 'show'])->name('equipment.show');
Route::get('/services/{service}', [ServiceListingController::class, 'show'])->name('services.show');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
