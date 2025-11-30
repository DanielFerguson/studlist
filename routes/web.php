<?php

use App\Http\Controllers\GeneticsListingController;
use App\Http\Controllers\PublicPageController;
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

// Public pages (Blade templates)
Route::get('/', [PublicPageController::class, 'home'])->name('home');

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

        return view('dashboard.index', [
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

// Public pages (Blade templates)
Route::get('/search', [PublicPageController::class, 'search'])->name('search');
Route::get('/about', [PublicPageController::class, 'about'])->name('about');

// Public listing view routes (Blade templates)
Route::get('/steers/{steer}', [PublicPageController::class, 'showSteer'])->name('steers.show');
Route::get('/studs/{stud}', [PublicPageController::class, 'showStud'])->name('studs.show');
Route::get('/genetics/{genetic}', [PublicPageController::class, 'showGenetics'])->name('genetics.show');
Route::get('/equipment/{equipment}', [PublicPageController::class, 'showEquipment'])->name('equipment.show');
Route::get('/services/{service}', [PublicPageController::class, 'showService'])->name('services.show');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
