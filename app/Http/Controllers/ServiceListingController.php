<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceListingRequest;
use App\Http\Requests\UpdateServiceListingRequest;
use App\Models\ServiceListing;
use App\Services\AnalyticsService;
use Illuminate\View\View;

class ServiceListingController extends Controller
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
        $this->authorize('create', ServiceListing::class);

        return view('listings.services.create', [
            'contactDefaults' => auth()->user()->getContactDefaults(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @phpstan-ignore-next-line
     */
    public function store(StoreServiceListingRequest $request)
    {
        $this->authorize('create', ServiceListing::class);

        // Get validated data
        $validated = $request->validated();

        // Filter out empty links
        $links = array_filter($validated['links'] ?? [], fn ($link) => ! empty($link));

        // Create the listing
        $service = ServiceListing::create([
            'user_id' => $request->user()->id,
            'type' => $validated['type'],
            'abn' => $validated['abn'] ?? null,
            'business_name' => $validated['business_name'],
            'contact_name' => $validated['contact_name'],
            'phone_contact' => $validated['phone_contact'] ?? null,
            'email_contact' => $validated['email_contact'] ?? null,
            'locations_covered' => $validated['locations_covered'],
            'links' => ! empty($links) ? array_values($links) : null,
            'description' => $validated['description'] ?? null,
        ]);

        // Save contact info for future listings
        $request->user()->saveContactDefaults($validated);

        $this->analytics->trackListingCreated($request->user(), $service, 'service');

        // Redirect to dashboard - service listings are free
        return redirect()->route('dashboard')
            ->with('success', 'Service listing created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceListing $service): View
    {
        // Service listings are always visible (free)
        return view('public.listings.service', [
            'listing' => $service->load('user'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceListing $service): View
    {
        $this->authorize('update', $service);

        return view('listings.services.edit', [
            'service' => $service,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceListingRequest $request, ServiceListing $service)
    {
        $this->authorize('update', $service);

        // Get validated data
        $validated = $request->validated();

        // Filter out empty links
        $links = array_filter($validated['links'] ?? [], fn ($link) => ! empty($link));

        // Update the listing
        $service->update([
            'type' => $validated['type'],
            'abn' => $validated['abn'] ?? null,
            'business_name' => $validated['business_name'],
            'contact_name' => $validated['contact_name'],
            'phone_contact' => $validated['phone_contact'] ?? null,
            'email_contact' => $validated['email_contact'] ?? null,
            'locations_covered' => $validated['locations_covered'],
            'links' => ! empty($links) ? array_values($links) : null,
            'description' => $validated['description'] ?? null,
        ]);

        $this->analytics->trackListingUpdated($request->user(), $service, 'service');

        return redirect()->route('dashboard')->with('success', 'Service listing updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceListing $service)
    {
        $this->authorize('delete', $service);

        $this->analytics->trackListingDeleted(auth()->user(), $service, 'service');

        $service->delete();

        return redirect()->route('dashboard')->with('success', 'Service listing deleted successfully!');
    }
}
