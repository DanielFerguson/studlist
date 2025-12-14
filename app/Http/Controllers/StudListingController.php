<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudListingRequest;
use App\Http\Requests\UpdateStudListingRequest;
use App\Models\StudListing;
use App\Services\AnalyticsService;
use Illuminate\View\View;

class StudListingController extends Controller
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
        $this->authorize('create', StudListing::class);

        return view('listings.studs.create', [
            'contactDefaults' => auth()->user()->getContactDefaults(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudListingRequest $request)
    {
        $this->authorize('create', StudListing::class);

        // Handle photo uploads
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('stud-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        // Get validated data
        $validated = $request->validated();

        // Create the listing
        $stud = StudListing::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'photos' => $photoPaths,
            'date_of_birth' => $validated['dob'],
            'breed' => $validated['breed'],
            'colour' => $validated['colour'],
            'tattoo_number' => $validated['tattoo_number'] ?? null,
            'location' => $validated['location'],
            'sire' => $validated['sire'] ?? null,
            'dam' => $validated['dam'] ?? null,
            'registration_link' => $validated['registration_link'] ?? null,
            'business_contact' => $validated['business_contact'] ?? null,
            'email_contact' => $validated['email_contact'] ?? null,
            'phone_contact' => $validated['phone_contact'] ?? null,
            'pic_number' => $validated['pic_number'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => 'active',
        ]);

        // Save contact info for future listings
        $request->user()->saveContactDefaults($validated);

        $this->analytics->trackListingCreated($request->user(), $stud, 'stud');

        return redirect()->route('dashboard')
            ->with('success', 'Stud listing created successfully! Your listing is now live.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudListing $stud): View
    {
        // Only show active listings publicly
        abort_if($stud->status !== 'active', 404);

        return view('public.listings.stud', [
            'listing' => $stud->load('user'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudListing $stud): View
    {
        $this->authorize('update', $stud);

        return view('listings.studs.edit', [
            'stud' => $stud,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudListingRequest $request, StudListing $stud)
    {
        $this->authorize('update', $stud);

        // Handle photo uploads
        $photoPaths = $stud->photos ?? []; // Keep existing photos
        if ($request->hasFile('photos')) {
            // Add new photos to existing ones
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('stud-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        // Get validated data
        $validated = $request->validated();

        // Update the listing
        $stud->update([
            'name' => $validated['name'],
            'photos' => $photoPaths,
            'date_of_birth' => $validated['dob'],
            'breed' => $validated['breed'],
            'colour' => $validated['colour'],
            'tattoo_number' => $validated['tattoo_number'] ?? null,
            'location' => $validated['location'],
            'sire' => $validated['sire'] ?? null,
            'dam' => $validated['dam'] ?? null,
            'registration_link' => $validated['registration_link'] ?? null,
            'business_contact' => $validated['business_contact'] ?? null,
            'email_contact' => $validated['email_contact'] ?? null,
            'phone_contact' => $validated['phone_contact'] ?? null,
            'pic_number' => $validated['pic_number'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        $this->analytics->trackListingUpdated($request->user(), $stud, 'stud');

        return redirect()->route('dashboard')->with('success', 'Stud listing updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudListing $stud)
    {
        $this->authorize('delete', $stud);

        $subscriptionCancelled = false;

        // If the stud has an active subscription, cancel it first
        if ($stud->stripe_subscription_id) {
            try {
                $user = auth()->user();
                $subscription = $user->subscriptions()->where('stripe_id', $stud->stripe_subscription_id)->first();

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

                    $stud->update(['status' => 'cancelled']);
                    $subscriptionCancelled = true;
                }
            } catch (\Exception $e) {
                // Log the error but continue with deletion
                \Illuminate\Support\Facades\Log::error('Error cancelling subscription during deletion', [
                    'stud_id' => $stud->id,
                    'subscription_id' => $stud->stripe_subscription_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->analytics->trackListingDeleted(auth()->user(), $stud, 'stud');

        $stud->delete();

        $message = $subscriptionCancelled
            ? 'Stud listing deleted successfully! Subscription cancelled.'
            : 'Stud listing deleted successfully!';

        return redirect()->route('dashboard')->with('success', $message);
    }
}
