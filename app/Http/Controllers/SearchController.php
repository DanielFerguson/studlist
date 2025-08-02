<?php

namespace App\Http\Controllers;

use App\Models\GeneticsListing;
use App\Models\ShowEquipmentListing;
use App\Models\SteerListing;
use App\Models\StudListing;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category', 'breed', 'location', 'min_price', 'max_price', 'sort']);

        return Inertia::render('search', [
            'filters' => $filters,
            'listings' => $this->getListings($request),
        ]);
    }

    private function getListings(Request $request)
    {
        $search = $request->get('search');
        $categories = $request->get('category', []);
        $breed = $request->get('breed');
        $location = $request->get('location');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $sort = $request->get('sort', 'newest');
        $page = $request->get('page', 1);

        // If no categories selected, default to all
        if (empty($categories)) {
            $categories = ['steers', 'studs', 'genetics', 'equipment'];
        }

        $allListings = collect();

        // Query each listing type based on selected categories
        if (in_array('steers', $categories)) {
            $steersQuery = SteerListing::where('status', 'active')
                ->with('user');

            if ($search) {
                $steersQuery->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('breed', 'like', "%{$search}%");
                });
            }

            if ($breed) {
                $steersQuery->where('breed', 'like', "%{$breed}%");
            }

            if ($location) {
                $steersQuery->where('location', 'like', "%{$location}%");
            }

            if ($minPrice) {
                $steersQuery->where('price', '>=', $minPrice);
            }

            if ($maxPrice) {
                $steersQuery->where('price', '<=', $maxPrice);
            }

            $steers = $steersQuery->get()->map(function ($steer) {
                $steer->listing_type = 'steer';
                $steer->listing_url = route('steers.show', $steer);
                return $steer;
            });

            $allListings = $allListings->concat($steers);
        }

        if (in_array('studs', $categories)) {
            $studsQuery = StudListing::where('status', 'active')
                ->with('user');

            if ($search) {
                $studsQuery->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('breed', 'like', "%{$search}%");
                });
            }

            if ($breed) {
                $studsQuery->where('breed', 'like', "%{$breed}%");
            }

            if ($location) {
                $studsQuery->where('location', 'like', "%{$location}%");
            }

            $studs = $studsQuery->get()->map(function ($stud) {
                $stud->listing_type = 'stud';
                $stud->listing_url = route('studs.show', $stud);
                return $stud;
            });

            $allListings = $allListings->concat($studs);
        }

        if (in_array('genetics', $categories)) {
            $geneticsQuery = GeneticsListing::with('user');

            if ($search) {
                $geneticsQuery->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('breed', 'like', "%{$search}%");
                });
            }

            if ($breed) {
                $geneticsQuery->where('breed', 'like', "%{$breed}%");
            }

            if ($minPrice) {
                $geneticsQuery->where('price', '>=', $minPrice);
            }

            if ($maxPrice) {
                $geneticsQuery->where('price', '<=', $maxPrice);
            }

            $genetics = $geneticsQuery->get()->map(function ($genetic) {
                $genetic->listing_type = 'genetics';
                $genetic->listing_url = route('genetics.show', $genetic);
                return $genetic;
            });

            $allListings = $allListings->concat($genetics);
        }

        if (in_array('equipment', $categories)) {
            $equipmentQuery = ShowEquipmentListing::with('user');

            if ($search) {
                $equipmentQuery->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($location) {
                $equipmentQuery->where('location', 'like', "%{$location}%");
            }

            $equipment = $equipmentQuery->get()->map(function ($item) {
                $item->listing_type = 'equipment';
                $item->listing_url = route('equipment.show', $item);
                $item->name = $item->title; // Normalize field name
                return $item;
            });

            $allListings = $allListings->concat($equipment);
        }

        // Sort the combined results
        switch ($sort) {
            case 'price_low':
                $allListings = $allListings->sortBy('price');
                break;
            case 'price_high':
                $allListings = $allListings->sortByDesc('price');
                break;
            case 'name':
                $allListings = $allListings->sortBy('name');
                break;
            case 'newest':
            default:
                $allListings = $allListings->sortByDesc('created_at');
                break;
        }

        // Paginate the results
        $perPage = 20;
        $total = $allListings->count();
        $currentPage = $page;
        $items = $allListings->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return [
            'data' => $items,
            'current_page' => $currentPage,
            'last_page' => ceil($total / $perPage),
            'per_page' => $perPage,
            'total' => $total,
            'from' => ($currentPage - 1) * $perPage + 1,
            'to' => min($currentPage * $perPage, $total),
        ];
    }
}