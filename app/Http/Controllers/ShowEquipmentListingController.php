<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShowEquipmentListingRequest;
use App\Http\Requests\UpdateShowEquipmentListingRequest;
use App\Models\ShowEquipmentListing;
use Inertia\Inertia;

class ShowEquipmentListingController extends Controller
{
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
    public function create()
    {
        $this->authorize('create', ShowEquipmentListing::class);

        return Inertia::render('show-equipment-listings/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @phpstan-ignore-next-line
     */
    public function store(StoreShowEquipmentListingRequest $request)
    {
        $this->authorize('create', ShowEquipmentListing::class);

        // Handle photo uploads
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('show-equipment-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        // Get validated data
        $validated = $request->validated();

        // Create the listing
        $showEquipment = ShowEquipmentListing::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'photos' => $photoPaths,
            'condition' => $validated['condition'],
            'location' => $validated['location'],
            'phone_contact' => $validated['phone_contact'] ?? null,
            'email_contact' => $validated['email_contact'] ?? null,
        ]);

        // Redirect to dashboard - show equipment listings are free
        return redirect()->route('dashboard')
            ->with('success', 'Show equipment listing created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowEquipmentListing $equipment)
    {
        // Equipment listings don't have status field - they're always visible
        return Inertia::render('equipment/show', [
            'listing' => $equipment->load('user'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShowEquipmentListing $showEquipment)
    {
        $this->authorize('update', $showEquipment);

        return Inertia::render('show-equipment-listings/edit', [
            'showEquipment' => $showEquipment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShowEquipmentListingRequest $request, ShowEquipmentListing $showEquipment)
    {
        $this->authorize('update', $showEquipment);

        // Handle photo uploads
        $photoPaths = $showEquipment->photos ?? []; // Keep existing photos
        if ($request->hasFile('photos')) {
            // Add new photos to existing ones
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('show-equipment-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        // Get validated data
        $validated = $request->validated();

        // Update the listing
        $showEquipment->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'photos' => $photoPaths,
            'condition' => $validated['condition'],
            'location' => $validated['location'],
            'phone_contact' => $validated['phone_contact'] ?? null,
            'email_contact' => $validated['email_contact'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Show equipment listing updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShowEquipmentListing $showEquipment)
    {
        $this->authorize('delete', $showEquipment);

        $showEquipment->delete();

        return redirect()->route('dashboard')->with('success', 'Show equipment listing deleted successfully!');
    }
}
