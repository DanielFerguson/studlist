<?php

namespace App\Http\Controllers;

use App\Models\GeneticsListing;
use App\Models\HayListing;
use App\Models\ServiceListing;
use App\Models\ShowEquipmentListing;
use App\Models\SteerListing;
use App\Models\StudListing;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
class PublicPageController extends Controller
{
    public function __construct(
        private AnalyticsService $analytics
    ) {}

    /**
     * Display the homepage.
     */
    public function home()
    {
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

        $geneticsListings = GeneticsListing::with('user')
            ->latest()
            ->take(4)
            ->get();

        $showEquipmentListings = ShowEquipmentListing::with('user')
            ->latest()
            ->take(4)
            ->get();

        $serviceListings = ServiceListing::with('user')
            ->latest()
            ->take(4)
            ->get();

        $hayListings = HayListing::with('user')
            ->latest()
            ->take(4)
            ->get();

        return view('public.home', [
            'steerListings' => $steerListings,
            'studListings' => $studListings,
            'geneticsListings' => $geneticsListings,
            'showEquipmentListings' => $showEquipmentListings,
            'serviceListings' => $serviceListings,
            'hayListings' => $hayListings,
        ]);
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Display the search page.
     */
    public function search(Request $request)
    {
        $filters = $request->only(['search', 'category', 'breed', 'location', 'min_price', 'max_price', 'sort']);
        $listings = $this->getListings($request);

        $this->analytics->trackSearchPerformed(
            auth()->user(),
            $filters,
            $listings['total']
        );

        if ($listings['total'] === 0) {
            $this->analytics->trackSearchNoResults(auth()->user(), $filters);
        }

        return view('public.search', [
            'filters' => $filters,
            'listings' => $listings,
        ]);
    }

    /**
     * Display a steer listing.
     */
    public function showSteer(SteerListing $steer)
    {
        abort_if($steer->status !== 'active', 404);

        $this->analytics->trackListingViewed($steer, 'steer', auth()->user());

        return view('public.listings.steer', [
            'listing' => $steer->load('user'),
        ]);
    }

    /**
     * Display a stud listing.
     */
    public function showStud(StudListing $stud)
    {
        abort_if($stud->status !== 'active', 404);

        $this->analytics->trackListingViewed($stud, 'stud', auth()->user());

        return view('public.listings.stud', [
            'listing' => $stud->load('user'),
        ]);
    }

    /**
     * Display a genetics listing.
     */
    public function showGenetics(GeneticsListing $genetic)
    {
        $this->analytics->trackListingViewed($genetic, 'genetics', auth()->user());

        return view('public.listings.genetics', [
            'listing' => $genetic->load('user'),
        ]);
    }

    /**
     * Display an equipment listing.
     */
    public function showEquipment(ShowEquipmentListing $equipment)
    {
        $this->analytics->trackListingViewed($equipment, 'equipment', auth()->user());

        return view('public.listings.equipment', [
            'listing' => $equipment->load('user'),
        ]);
    }

    /**
     * Display a service listing.
     */
    public function showService(ServiceListing $service)
    {
        $this->analytics->trackListingViewed($service, 'service', auth()->user());

        return view('public.listings.service', [
            'listing' => $service->load('user'),
        ]);
    }

    /**
     * Display the hay listings index with map.
     */
    public function hayIndex(Request $request)
    {
        $query = HayListing::with('user');

        if ($request->filled('hay_type')) {
            $query->where('hay_type', $request->hay_type);
        }

        if ($request->filled('bale_type')) {
            $query->where('bale_type', $request->bale_type);
        }

        if ($request->filled('quality_grade')) {
            $query->where('quality_grade', $request->quality_grade);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('price_per_bale', '>=', $request->min_price)
                    ->orWhere('price_per_tonne', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('price_per_bale', '<=', $request->max_price)
                    ->orWhere('price_per_tonne', '<=', $request->max_price);
            });
        }

        if ($request->has('delivery_available')) {
            $query->where('delivery_available', true);
        }

        if ($request->has('test_results')) {
            $query->where('test_results_available', true);
        }

        $listings = $query->latest()->paginate(20)->withQueryString();

        $listingsWithCoordinates = HayListing::withCoordinates()->get();

        return view('public.hay.index', [
            'listings' => $listings,
            'listingsWithCoordinates' => $listingsWithCoordinates,
            'filters' => $request->only([
                'hay_type', 'bale_type', 'quality_grade', 'location',
                'min_price', 'max_price', 'delivery_available', 'test_results',
            ]),
            'mapboxToken' => config('services.mapbox.token'),
        ]);
    }

    /**
     * Display a hay listing.
     */
    public function showHay(HayListing $hay)
    {
        $this->analytics->trackListingViewed($hay, 'hay', auth()->user());

        return view('public.listings.hay', [
            'listing' => $hay->load('user'),
        ]);
    }

    /**
     * Get filtered listings for search.
     */
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

        if (empty($categories)) {
            $categories = ['steers', 'studs', 'genetics', 'equipment', 'services', 'hay'];
        }

        $allListings = collect();

        if (in_array('steers', $categories)) {
            $steersQuery = SteerListing::where('status', 'active')->with('user');

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
            $studsQuery = StudListing::where('status', 'active')->with('user');

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
                $item->name = $item->title;

                return $item;
            });

            $allListings = $allListings->concat($equipment);
        }

        if (in_array('services', $categories)) {
            $servicesQuery = ServiceListing::with('user');

            if ($search) {
                $servicesQuery->where(function ($q) use ($search) {
                    $q->where('business_name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%");
                });
            }

            if ($location) {
                $servicesQuery->whereJsonContains('locations_covered', $location);
            }

            $services = $servicesQuery->get()->map(function ($service) {
                $service->listing_type = 'service';
                $service->listing_url = route('services.show', $service);
                $service->name = $service->business_name;

                return $service;
            });

            $allListings = $allListings->concat($services);
        }

        if (in_array('hay', $categories)) {
            $hayQuery = HayListing::with('user');

            if ($search) {
                $hayQuery->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('hay_type', 'like', "%{$search}%");
                });
            }

            if ($location) {
                $hayQuery->where('location', 'like', "%{$location}%");
            }
            if ($minPrice) {
                $hayQuery->where(function ($q) use ($minPrice) {
                    $q->where('price_per_bale', '>=', $minPrice)
                        ->orWhere('price_per_tonne', '>=', $minPrice);
                });
            }
            if ($maxPrice) {
                $hayQuery->where(function ($q) use ($maxPrice) {
                    $q->where('price_per_bale', '<=', $maxPrice)
                        ->orWhere('price_per_tonne', '<=', $maxPrice);
                });
            }

            $hay = $hayQuery->get()->map(function ($item) {
                $item->listing_type = 'hay';
                $item->listing_url = route('hay.show', $item);
                $item->name = $item->title;
                $item->price = $item->price_per_bale ?? $item->price_per_tonne;

                return $item;
            });

            $allListings = $allListings->concat($hay);
        }

        // Sort
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

        // Paginate
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
