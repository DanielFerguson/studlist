import { Head, Link, router, usePage } from '@inertiajs/react';
import { Search, Filter, X, ChevronDown, Menu } from 'lucide-react';
import { useState, useEffect, useCallback } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { type SharedData } from '@/types';
import { WhenVisible } from '@inertiajs/react';

interface Listing {
    id: number;
    name: string;
    title?: string; // For equipment
    photos?: string[];
    price?: number;
    breed?: string;
    location?: string;
    description?: string;
    listing_type: string;
    listing_url: string;
    created_at: string;
    user: {
        name: string;
    };
}

interface PaginatedListings {
    data: Listing[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
}

interface Filters {
    search?: string;
    category?: string[];
    breed?: string;
    location?: string;
    min_price?: string;
    max_price?: string;
    sort?: string;
}

interface Props {
    filters: Filters;
    listings: PaginatedListings;
}

export default function SearchPage({ filters: initialFilters, listings: initialListings }: Props) {
    const { auth } = usePage<SharedData>().props;
    const [filters, setFilters] = useState<Filters>(initialFilters || {});
    const [listings, setListings] = useState<Listing[]>(initialListings?.data || []);
    const [currentPage, setCurrentPage] = useState(initialListings?.current_page || 1);
    const [lastPage, setLastPage] = useState(initialListings?.last_page || 1);
    const [loading, setLoading] = useState(false);
    const [mobileFiltersOpen, setMobileFiltersOpen] = useState(false);
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    // Category options
    const categories = [
        { value: 'steers', label: 'Steers' },
        { value: 'studs', label: 'Studs' },
        { value: 'genetics', label: 'Genetics' },
        { value: 'equipment', label: 'Equipment' },
    ];

    // Sort options
    const sortOptions = [
        { value: 'newest', label: 'Newest First' },
        { value: 'price_low', label: 'Price: Low to High' },
        { value: 'price_high', label: 'Price: High to Low' },
        { value: 'name', label: 'Name: A-Z' },
    ];

    // Update filters and reload results
    const updateFilters = useCallback((newFilters: Filters) => {
        setFilters(newFilters);
        // Don't clear listings here - keep stale results visible
        setCurrentPage(1);
        
        router.get(route('search'), newFilters, {
            preserveState: true,
            preserveScroll: true,
            only: ['listings'],
            onSuccess: (page) => {
                const newListings = page.props.listings as PaginatedListings;
                setListings(newListings.data);
                setCurrentPage(newListings.current_page);
                setLastPage(newListings.last_page);
            },
        });
    }, []);

    // Load more listings
    const loadMore = useCallback(() => {
        if (loading || currentPage >= lastPage) return;
        
        setLoading(true);
        
        router.get(route('search'), { ...filters, page: currentPage + 1 }, {
            preserveState: true,
            preserveScroll: true,
            only: ['listings'],
            onSuccess: (page) => {
                const newListings = page.props.listings as PaginatedListings;
                setListings(prev => [...prev, ...newListings.data]);
                setCurrentPage(newListings.current_page);
                setLoading(false);
            },
            onError: () => {
                setLoading(false);
            },
        });
    }, [currentPage, lastPage, filters, loading]);

    // Handle category change
    const handleCategoryChange = (category: string, checked: boolean) => {
        const currentCategories = filters.category || [];
        const newCategories = checked 
            ? [...currentCategories, category]
            : currentCategories.filter(c => c !== category);
        
        updateFilters({ ...filters, category: newCategories.length > 0 ? newCategories : undefined });
    };

    // Clear all filters
    const clearFilters = () => {
        updateFilters({});
    };

    const filterContent = (
        <div className="space-y-6">
            {/* Categories */}
            <div>
                <h3 className="font-medium mb-3">Categories</h3>
                <div className="space-y-2">
                    {categories.map((category) => (
                        <div key={category.value} className="flex items-center">
                            <Checkbox
                                id={category.value}
                                checked={(filters.category || []).includes(category.value)}
                                onCheckedChange={(checked) => handleCategoryChange(category.value, checked as boolean)}
                            />
                            <Label htmlFor={category.value} className="ml-2 cursor-pointer">
                                {category.label}
                            </Label>
                        </div>
                    ))}
                </div>
            </div>

            {/* Breed */}
            <div>
                <Label htmlFor="breed">Breed</Label>
                <Input
                    id="breed"
                    type="text"
                    placeholder="e.g. Angus"
                    value={filters.breed || ''}
                    onChange={(e) => updateFilters({ ...filters, breed: e.target.value || undefined })}
                    className="mt-1"
                />
            </div>

            {/* Location */}
            <div>
                <Label htmlFor="location">Location</Label>
                <Input
                    id="location"
                    type="text"
                    placeholder="e.g. Sydney"
                    value={filters.location || ''}
                    onChange={(e) => updateFilters({ ...filters, location: e.target.value || undefined })}
                    className="mt-1"
                />
            </div>

            {/* Price Range */}
            <div>
                <Label>Price Range</Label>
                <div className="grid grid-cols-2 gap-2 mt-1">
                    <Input
                        type="number"
                        placeholder="Min"
                        value={filters.min_price || ''}
                        onChange={(e) => updateFilters({ ...filters, min_price: e.target.value || undefined })}
                    />
                    <Input
                        type="number"
                        placeholder="Max"
                        value={filters.max_price || ''}
                        onChange={(e) => updateFilters({ ...filters, max_price: e.target.value || undefined })}
                    />
                </div>
            </div>

            {/* Clear Filters */}
            <Button 
                variant="outline" 
                className="w-full"
                onClick={clearFilters}
            >
                Clear Filters
            </Button>
        </div>
    );

    return (
        <>
            <Head title="Search Listings" />
            
            <div className="min-h-screen bg-white">
                {/* Header */}
                <header className="absolute inset-x-0 top-0 z-50 bg-white/95 backdrop-blur-sm">
                    <nav className="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
                        <div className="flex lg:flex-1">
                            <Link href="/" className="-m-1.5 p-1.5">
                                <span className="text-2xl font-bold text-gray-900">StudList</span>
                            </Link>
                        </div>
                        <div className="flex lg:hidden">
                            <button
                                type="button"
                                className="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
                                onClick={() => setMobileMenuOpen(true)}
                            >
                                <span className="sr-only">Open main menu</span>
                                <Menu className="h-6 w-6" />
                            </button>
                        </div>
                        <div className="hidden lg:flex lg:gap-x-12">
                            <Link href={route('search')} className="text-sm font-semibold leading-6 text-gray-900">Search Listings</Link>
                            <a href="/#categories" className="text-sm font-semibold leading-6 text-gray-900">Categories</a>
                            <a href="/#features" className="text-sm font-semibold leading-6 text-gray-900">Features</a>
                            <a href="/#how-it-works" className="text-sm font-semibold leading-6 text-gray-900">How It Works</a>
                        </div>
                        <div className="hidden lg:flex lg:flex-1 lg:justify-end">
                            <div className="flex items-center gap-x-4">
                                {auth.user ? (
                                    <Link href={route('dashboard')} className="text-sm font-semibold leading-6 text-gray-900">
                                        Dashboard <span aria-hidden="true">&rarr;</span>
                                    </Link>
                                ) : (
                                    <>
                                        <Link href={route('login')} className="text-sm font-semibold leading-6 text-gray-900 whitespace-nowrap">
                                            Log in
                                        </Link>
                                        <Link href={route('register')} className="rounded-md bg-cyan-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-cyan-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cyan-600 whitespace-nowrap">
                                            Start Listing
                                        </Link>
                                    </>
                                )}
                            </div>
                        </div>
                    </nav>
                    {/* Mobile menu */}
                    <div className={`lg:hidden ${mobileMenuOpen ? '' : 'hidden'}`}>
                        <div className="fixed inset-0 z-50" />
                        <div className="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                            <div className="flex items-center justify-between">
                                <Link href="/" className="-m-1.5 p-1.5">
                                    <span className="text-2xl font-bold text-gray-900">StudList</span>
                                </Link>
                                <button
                                    type="button"
                                    className="-m-2.5 rounded-md p-2.5 text-gray-700"
                                    onClick={() => setMobileMenuOpen(false)}
                                >
                                    <span className="sr-only">Close menu</span>
                                    <X className="h-6 w-6" />
                                </button>
                            </div>
                            <div className="mt-6 flow-root">
                                <div className="-my-6 divide-y divide-gray-500/10">
                                    <div className="space-y-2 py-6">
                                        <Link href={route('search')} className="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Search Listings</Link>
                                        <a href="/#categories" className="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Categories</a>
                                        <a href="/#features" className="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Features</a>
                                        <a href="/#how-it-works" className="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">How It Works</a>
                                    </div>
                                    <div className="py-6">
                                        {auth.user ? (
                                            <Link href={route('dashboard')} className="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                                Dashboard
                                            </Link>
                                        ) : (
                                            <>
                                                <Link href={route('login')} className="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                                    Log in
                                                </Link>
                                                <Link href={route('register')} className="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                                    Register
                                                </Link>
                                            </>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-8 min-h-screen">
                    {/* Search Bar */}
                    <div className="mb-8">
                        <div className="relative max-w-2xl mx-auto">
                            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5" />
                            <Input
                                type="text"
                                placeholder="Search listings..."
                                value={filters.search || ''}
                                onChange={(e) => updateFilters({ ...filters, search: e.target.value || undefined })}
                                className="pl-10 pr-4 py-3 text-lg"
                            />
                        </div>
                    </div>

                    <div className="flex gap-8">
                        {/* Desktop Filters */}
                        <aside className="hidden lg:block w-64 flex-shrink-0">
                            <div className="bg-white rounded-lg shadow p-6 sticky top-28">
                                <div className="flex justify-between items-center mb-4">
                                    <h2 className="font-semibold text-lg">Filters</h2>
                                </div>
                                {filterContent}
                            </div>
                        </aside>

                        {/* Main Content */}
                        <main className="flex-1">
                            {/* Mobile Filter & Sort */}
                            <div className="flex justify-between items-center mb-6 lg:justify-end">
                                <Sheet open={mobileFiltersOpen} onOpenChange={setMobileFiltersOpen}>
                                    <SheetTrigger asChild>
                                        <Button variant="outline" className="lg:hidden">
                                            <Filter className="h-4 w-4 mr-2" />
                                            Filters
                                        </Button>
                                    </SheetTrigger>
                                    <SheetContent side="left">
                                        <SheetHeader>
                                            <SheetTitle>Filters</SheetTitle>
                                        </SheetHeader>
                                        <div className="mt-6">
                                            {filterContent}
                                        </div>
                                    </SheetContent>
                                </Sheet>

                                <Select 
                                    value={filters.sort || 'newest'}
                                    onValueChange={(value) => updateFilters({ ...filters, sort: value })}
                                >
                                    <SelectTrigger className="w-48">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {sortOptions.map((option) => (
                                            <SelectItem key={option.value} value={option.value}>
                                                {option.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>

                            {/* Results */}
                            {listings.length === 0 ? (
                                <div className="text-center py-12 bg-gray-50 rounded-lg">
                                    <p className="text-gray-500">No listings found matching your criteria.</p>
                                </div>
                            ) : (
                                <>
                                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                        {listings.map((listing) => (
                                            <Link
                                                key={`${listing.listing_type}-${listing.id}`}
                                                href={listing.listing_url}
                                                className="bg-white rounded-lg shadow hover:shadow-lg transition-shadow"
                                            >
                                                <div className="relative h-48 bg-gray-200 rounded-t-lg overflow-hidden">
                                                    {listing.photos && listing.photos.length > 0 ? (
                                                        <img
                                                            src={`/storage/${listing.photos[0]}`}
                                                            alt={listing.name || listing.title}
                                                            className="w-full h-full object-cover"
                                                        />
                                                    ) : (
                                                        <div className="flex items-center justify-center h-full">
                                                            <span className="text-gray-400">No image</span>
                                                        </div>
                                                    )}
                                                    <span className="absolute top-2 left-2 bg-cyan-600 text-white px-2 py-1 rounded text-xs">
                                                        {listing.listing_type}
                                                    </span>
                                                </div>
                                                <div className="p-4">
                                                    <h3 className="font-semibold text-lg mb-1">{listing.name || listing.title}</h3>
                                                    {listing.breed && (
                                                        <p className="text-sm text-gray-600">{listing.breed}</p>
                                                    )}
                                                    {listing.location && (
                                                        <p className="text-sm text-gray-600">{listing.location}</p>
                                                    )}
                                                    {listing.price && (
                                                        <p className="text-lg font-bold text-cyan-600 mt-2">${listing.price}</p>
                                                    )}
                                                </div>
                                            </Link>
                                        ))}
                                    </div>

                                    {/* Infinite Scroll Trigger */}
                                    {currentPage < lastPage && (
                                        <WhenVisible 
                                            fallback={
                                                <div className="text-center py-8">
                                                    <Button variant="outline" onClick={loadMore}>
                                                        Load More
                                                    </Button>
                                                </div>
                                            }
                                            buffer={200}
                                        >
                                            <div className="text-center py-8">
                                                <div className="inline-flex items-center">
                                                    <svg className="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" />
                                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                    </svg>
                                                    Loading more listings...
                                                </div>
                                            </div>
                                            {/* Trigger load more when visible */}
                                            <div className="hidden" ref={() => loadMore()} />
                                        </WhenVisible>
                                    )}

                                    {currentPage >= lastPage && listings.length > 0 && (
                                        <div className="text-center py-8 text-gray-500">
                                            No more listings to show
                                        </div>
                                    )}
                                </>
                            )}
                        </main>
                    </div>
                </div>

                {/* CTA Section with Background Image */}
                <div id="pricing" className="relative isolate overflow-hidden bg-gray-900">
                    <img
                        src="https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?w=1600&q=80"
                        alt="Cattle sunset"
                        className="absolute inset-0 -z-10 h-full w-full object-cover opacity-30"
                    />
                    <div className="px-6 py-24 sm:px-6 sm:py-32 lg:px-8">
                        <div className="mx-auto max-w-2xl text-center">
                            <h2 className="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                                Ready to list your cattle?
                            </h2>
                            <p className="mx-auto mt-6 max-w-xl text-lg leading-8 text-gray-300">
                                Join Australia's fastest-growing cattle marketplace. Only $15/month for unlimited listings.
                            </p>
                            <div className="mt-10 flex items-center justify-center gap-x-6">
                                <Link href={route('register')} className="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-cyan-600 shadow-sm hover:bg-cyan-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                    Get Started
                                </Link>
                                <Link href={route('login')} className="text-sm font-semibold leading-6 text-white">
                                    Already have an account? Sign in <span aria-hidden="true">→</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Footer */}
                <footer className="bg-gray-900">
                    <div className="mx-auto max-w-7xl overflow-hidden px-6 py-20 sm:py-24 lg:px-8">
                        <nav className="-mb-6 columns-2 sm:flex sm:justify-center sm:space-x-12" aria-label="Footer">
                            <div className="pb-6">
                                <Link href={route('about')} className="text-sm leading-6 text-gray-300 hover:text-white">About</Link>
                            </div>
                            <div className="pb-6">
                                <a href="#" className="text-sm leading-6 text-gray-300 hover:text-white">Contact</a>
                            </div>
                            <div className="pb-6">
                                <a href="#" className="text-sm leading-6 text-gray-300 hover:text-white">Terms</a>
                            </div>
                            <div className="pb-6">
                                <a href="#" className="text-sm leading-6 text-gray-300 hover:text-white">Privacy</a>
                            </div>
                        </nav>
                        <p className="mt-10 text-center text-xs leading-5 text-gray-400">
                            &copy; 2024 StudList. All rights reserved.
                        </p>
                    </div>
                </footer>
            </div>
        </>
    );
}