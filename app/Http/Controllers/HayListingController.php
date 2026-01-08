<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHayListingRequest;
use App\Http\Requests\UpdateHayListingRequest;
use App\Models\HayListing;
use App\Services\AnalyticsService;
use Illuminate\View\View;

class HayListingController extends Controller
{
    public function __construct(
        private AnalyticsService $analytics
    ) {}

    public function index()
    {
        //
    }

    public function create(): View
    {
        $this->authorize('create', HayListing::class);

        return view('listings.hay.create', [
            'contactDefaults' => auth()->user()->getContactDefaults(),
        ]);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function store(StoreHayListingRequest $request)
    {
        $this->authorize('create', HayListing::class);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('hay-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        $validated = $request->validated();

        $hay = HayListing::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'hay_type' => $validated['hay_type'],
            'bale_type' => $validated['bale_type'],
            'quantity' => $validated['quantity'],
            'weight_per_bale' => $validated['weight_per_bale'] ?? null,
            'season_cut' => $validated['season_cut'] ?? null,
            'cut_year' => $validated['cut_year'] ?? null,
            'quality_grade' => $validated['quality_grade'] ?? null,
            'test_results_available' => $validated['test_results_available'] ?? false,
            'protein_percentage' => $validated['protein_percentage'] ?? null,
            'moisture_percentage' => $validated['moisture_percentage'] ?? null,
            'energy_mj_kg' => $validated['energy_mj_kg'] ?? null,
            'nitrate_level' => $validated['nitrate_level'] ?? null,
            'weather_damaged' => $validated['weather_damaged'] ?? false,
            'storage_type' => $validated['storage_type'],
            'location' => $validated['location'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'delivery_available' => $validated['delivery_available'] ?? false,
            'delivery_radius_km' => $validated['delivery_radius_km'] ?? null,
            'minimum_order_quantity' => $validated['minimum_order_quantity'] ?? null,
            'price_type' => $validated['price_type'],
            'price_per_bale' => $validated['price_per_bale'] ?? null,
            'price_per_tonne' => $validated['price_per_tonne'] ?? null,
            'business_contact' => $validated['business_contact'] ?? null,
            'phone_contact' => $validated['phone_contact'] ?? null,
            'email_contact' => $validated['email_contact'] ?? null,
            'pic_number' => $validated['pic_number'] ?? null,
            'photos' => $photoPaths,
            'description' => $validated['description'] ?? null,
        ]);

        $request->user()->saveContactDefaults($validated);

        $this->analytics->trackListingCreated($request->user(), $hay, 'hay');

        return redirect()->route('dashboard')
            ->with('success', 'Hay listing created successfully!');
    }

    public function show(HayListing $hay): View
    {
        return view('public.listings.hay', [
            'listing' => $hay->load('user'),
        ]);
    }

    public function edit(HayListing $hay): View
    {
        $this->authorize('update', $hay);

        return view('listings.hay.edit', [
            'hay' => $hay,
        ]);
    }

    public function update(UpdateHayListingRequest $request, HayListing $hay)
    {
        $this->authorize('update', $hay);

        $photoPaths = $hay->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('hay-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        $validated = $request->validated();

        $hay->update([
            'title' => $validated['title'],
            'hay_type' => $validated['hay_type'],
            'bale_type' => $validated['bale_type'],
            'quantity' => $validated['quantity'],
            'weight_per_bale' => $validated['weight_per_bale'] ?? null,
            'season_cut' => $validated['season_cut'] ?? null,
            'cut_year' => $validated['cut_year'] ?? null,
            'quality_grade' => $validated['quality_grade'] ?? null,
            'test_results_available' => $validated['test_results_available'] ?? false,
            'protein_percentage' => $validated['protein_percentage'] ?? null,
            'moisture_percentage' => $validated['moisture_percentage'] ?? null,
            'energy_mj_kg' => $validated['energy_mj_kg'] ?? null,
            'nitrate_level' => $validated['nitrate_level'] ?? null,
            'weather_damaged' => $validated['weather_damaged'] ?? false,
            'storage_type' => $validated['storage_type'],
            'location' => $validated['location'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'delivery_available' => $validated['delivery_available'] ?? false,
            'delivery_radius_km' => $validated['delivery_radius_km'] ?? null,
            'minimum_order_quantity' => $validated['minimum_order_quantity'] ?? null,
            'price_type' => $validated['price_type'],
            'price_per_bale' => $validated['price_per_bale'] ?? null,
            'price_per_tonne' => $validated['price_per_tonne'] ?? null,
            'business_contact' => $validated['business_contact'] ?? null,
            'phone_contact' => $validated['phone_contact'] ?? null,
            'email_contact' => $validated['email_contact'] ?? null,
            'pic_number' => $validated['pic_number'] ?? null,
            'photos' => $photoPaths,
            'description' => $validated['description'] ?? null,
        ]);

        $this->analytics->trackListingUpdated($request->user(), $hay, 'hay');

        return redirect()->route('dashboard')->with('success', 'Hay listing updated successfully!');
    }

    public function destroy(HayListing $hay)
    {
        $this->authorize('delete', $hay);

        $this->analytics->trackListingDeleted(auth()->user(), $hay, 'hay');

        $hay->delete();

        return redirect()->route('dashboard')->with('success', 'Hay listing deleted successfully!');
    }
}
