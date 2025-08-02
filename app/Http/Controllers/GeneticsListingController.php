<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGeneticsListingRequest;
use App\Http\Requests\UpdateGeneticsListingRequest;
use App\Models\GeneticsListing;
use Inertia\Inertia;

class GeneticsListingController extends Controller
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
        $this->authorize('create', GeneticsListing::class);

        return Inertia::render('genetics-listings/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @phpstan-ignore-next-line
     */
    public function store(StoreGeneticsListingRequest $request)
    {
        $this->authorize('create', GeneticsListing::class);

        // Handle photo uploads
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('genetics-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        // Get validated data
        $validated = $request->validated();

        // Create the listing
        $genetics = GeneticsListing::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'price' => $validated['price'],
            'photos' => $photoPaths,
            'breed' => $validated['breed'],
            'type' => $validated['type'],
            'sire' => $validated['sire'] ?? null,
            'dam' => $validated['dam'] ?? null,
            'registration_link' => $validated['registration_link'] ?? null,
            'storage_location' => $validated['storage_location'],
            'phone_contact' => $validated['phone_contact'] ?? null,
            'email_contact' => $validated['email_contact'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        // Redirect to dashboard - genetics listings are free
        return redirect()->route('dashboard')
            ->with('success', 'Genetics listing created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(GeneticsListing $genetic)
    {
        // Genetics listings don't have status field - they're always visible
        return Inertia::render('genetics/show', [
            'listing' => $genetic->load('user'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GeneticsListing $genetic)
    {
        $this->authorize('update', $genetic);

        return Inertia::render('genetics-listings/edit', [
            'genetics' => $genetic,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGeneticsListingRequest $request, GeneticsListing $genetic)
    {
        $this->authorize('update', $genetic);

        // Handle photo uploads
        $photoPaths = $genetic->photos ?? []; // Keep existing photos
        if ($request->hasFile('photos')) {
            // Add new photos to existing ones
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('genetics-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        // Get validated data
        $validated = $request->validated();

        // Update the listing
        $genetic->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'photos' => $photoPaths,
            'breed' => $validated['breed'],
            'type' => $validated['type'],
            'sire' => $validated['sire'] ?? null,
            'dam' => $validated['dam'] ?? null,
            'registration_link' => $validated['registration_link'] ?? null,
            'storage_location' => $validated['storage_location'],
            'phone_contact' => $validated['phone_contact'] ?? null,
            'email_contact' => $validated['email_contact'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Genetics listing updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GeneticsListing $genetic)
    {
        $this->authorize('delete', $genetic);

        $genetic->delete();

        return redirect()->route('dashboard')->with('success', 'Genetics listing deleted successfully!');
    }
}
