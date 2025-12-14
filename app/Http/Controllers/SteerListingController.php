<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSteerListingRequest;
use App\Http\Requests\UpdateSteerListingRequest;
use App\Models\SteerListing;
use App\Services\AnalyticsService;
use Illuminate\View\View;

class SteerListingController extends Controller
{
    public function __construct(
        private AnalyticsService $analytics
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', SteerListing::class);

        return view('listings.steers.create', [
            'contactDefaults' => auth()->user()->getContactDefaults(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @phpstan-ignore-next-line
     */
    public function store(StoreSteerListingRequest $request)
    {
        $this->authorize('create', SteerListing::class);

        // Handle photo uploads
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('steer-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        // Get validated data
        $validated = $request->validated();

        // Create the listing
        $steer = SteerListing::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'photos' => $photoPaths,
            'date_of_birth' => $validated['dob'],
            'breed' => $validated['breed'],
            'colour' => $validated['colour'],
            'location' => $validated['location'],
            'sire' => $validated['sire'] ?? null,
            'dam' => $validated['dam'] ?? null,
            'business_contact' => $validated['business_contact'] ?? null,
            'email_contact' => $validated['email_contact'] ?? null,
            'phone_contact' => $validated['phone_contact'] ?? null,
            'pic_number' => $validated['pic_number'] ?? null,
            'description' => $validated['description'] ?? null,
            'started_on_feed' => $validated['started_on_feed'] ?? false,
            'price' => $validated['price'] ?? null,
            'status' => 'active',
        ]);

        // Save contact info for future listings
        $request->user()->saveContactDefaults($validated);

        $this->analytics->trackListingCreated($request->user(), $steer, 'steer');

        return redirect()->route('dashboard')
            ->with('success', 'Steer listing created successfully! Your listing is now live.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SteerListing $steer): View
    {
        // Only show active listings publicly
        abort_if($steer->status !== 'active', 404);

        return view('public.listings.steer', [
            'listing' => $steer->load('user'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SteerListing $steer): View
    {
        $this->authorize('update', $steer);

        return view('listings.steers.edit', [
            'steer' => $steer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSteerListingRequest $request, SteerListing $steer)
    {
        $this->authorize('update', $steer);

        // Handle photo uploads
        $photoPaths = $steer->photos ?? []; // Keep existing photos
        if ($request->hasFile('photos')) {
            // Add new photos to existing ones
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('steer-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        // Get validated data
        $validated = $request->validated();

        // Update the listing
        $steer->update([
            'name' => $validated['name'],
            'photos' => $photoPaths,
            'date_of_birth' => $validated['dob'],
            'breed' => $validated['breed'],
            'colour' => $validated['colour'],
            'location' => $validated['location'],
            'sire' => $validated['sire'] ?? null,
            'dam' => $validated['dam'] ?? null,
            'business_contact' => $validated['business_contact'] ?? null,
            'email_contact' => $validated['email_contact'] ?? null,
            'phone_contact' => $validated['phone_contact'] ?? null,
            'pic_number' => $validated['pic_number'] ?? null,
            'description' => $validated['description'] ?? null,
            'started_on_feed' => $validated['started_on_feed'] ?? false,
            'price' => $validated['price'] ?? null,
        ]);

        $this->analytics->trackListingUpdated($request->user(), $steer, 'steer');

        return redirect()->route('dashboard')->with('success', 'Steer listing updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SteerListing $steer)
    {
        $this->authorize('delete', $steer);

        $subscriptionCancelled = false;

        // If the steer has an active subscription, cancel it first
        if ($steer->stripe_subscription_id) {
            try {
                $user = auth()->user();
                $subscription = $user->subscriptions()->where('stripe_id', $steer->stripe_subscription_id)->first();

                if ($subscription && $subscription->active()) {
                    // In testing environment, skip actual Stripe cancellation
                    if (app()->environment('testing')) {
                        // Just update the subscription status in database
                        $subscription->update([
                            'stripe_status' => 'canceled',
                            'ends_at' => now(),
                        ]);
                    } else {
                        // In production, cancel through Stripe
                        $subscription->cancel();
                    }

                    $steer->update(['status' => 'cancelled']);
                    $subscriptionCancelled = true;
                }
            } catch (\Exception $e) {
                // Log the error but continue with deletion
                \Illuminate\Support\Facades\Log::error('Error cancelling subscription during deletion', [
                    'steer_id' => $steer->id,
                    'subscription_id' => $steer->stripe_subscription_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->analytics->trackListingDeleted(auth()->user(), $steer, 'steer');

        $steer->delete();

        $message = $subscriptionCancelled
            ? 'Steer listing deleted successfully! Subscription cancelled.'
            : 'Steer listing deleted successfully!';

        return redirect()->route('dashboard')->with('success', $message);
    }
}
