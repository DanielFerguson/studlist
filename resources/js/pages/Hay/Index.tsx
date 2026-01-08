import { useState, useMemo } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import Map, { Marker, Popup } from 'react-map-gl';
import 'mapbox-gl/dist/mapbox-gl.css';
import type { HayListing, HayFilters, PaginatedListings } from '@/types/hay';

interface Props {
    listings: PaginatedListings;
    listingsWithCoordinates: HayListing[];
    filters: HayFilters;
    hayTypes: string[];
    baleTypes: string[];
    qualityGrades: string[];
    mapboxToken: string;
}

export default function HayIndex({
    listings,
    listingsWithCoordinates,
    filters,
    hayTypes,
    baleTypes,
    qualityGrades,
    mapboxToken,
}: Props) {
    const [selectedListing, setSelectedListing] = useState<HayListing | null>(null);

    const [localFilters, setLocalFilters] = useState<HayFilters>({
        hay_type: filters.hay_type || [],
        bale_type: filters.bale_type || [],
        quality_grade: filters.quality_grade || [],
        location: filters.location || '',
        min_price: filters.min_price,
        max_price: filters.max_price,
        delivery_available: filters.delivery_available || false,
        test_results: filters.test_results || false,
    });

    const handleFilterChange = (key: keyof HayFilters, value: unknown) => {
        setLocalFilters(prev => ({ ...prev, [key]: value }));
    };

    const handleCheckboxArrayChange = (
        key: 'hay_type' | 'bale_type' | 'quality_grade',
        value: string,
        checked: boolean
    ) => {
        setLocalFilters(prev => ({
            ...prev,
            [key]: checked
                ? [...(prev[key] || []), value]
                : (prev[key] || []).filter(v => v !== value),
        }));
    };

    const applyFilters = () => {
        router.get('/hay', localFilters as Record<string, unknown>, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const clearFilters = () => {
        router.get('/hay');
    };

    const hasActiveFilters = () => {
        return (
            (localFilters.hay_type?.length ?? 0) > 0 ||
            (localFilters.bale_type?.length ?? 0) > 0 ||
            (localFilters.quality_grade?.length ?? 0) > 0 ||
            localFilters.location ||
            localFilters.min_price ||
            localFilters.max_price ||
            localFilters.delivery_available ||
            localFilters.test_results
        );
    };

    const initialViewState = useMemo(() => {
        if (listingsWithCoordinates.length > 0) {
            const lngs = listingsWithCoordinates.map(l => l.longitude!);
            const lats = listingsWithCoordinates.map(l => l.latitude!);
            return {
                longitude: (Math.min(...lngs) + Math.max(...lngs)) / 2,
                latitude: (Math.min(...lats) + Math.max(...lats)) / 2,
                zoom: 4,
            };
        }
        return {
            longitude: 133.7751,
            latitude: -25.2744,
            zoom: 3,
        };
    }, [listingsWithCoordinates]);

    return (
        <>
            <Head title="Hay Listings" />

            {/* Hero Section with Map */}
            <section className="bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-primary-light)] py-8">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Breadcrumb */}
                    <nav className="mb-4">
                        <ol className="flex items-center gap-2 text-sm">
                            <li>
                                <Link href="/" className="text-white/70 hover:text-white">
                                    Home
                                </Link>
                            </li>
                            <li><span className="text-white/50">/</span></li>
                            <li><span className="text-white font-medium">Hay</span></li>
                        </ol>
                    </nav>

                    <h1 className="font-heading text-3xl md:text-4xl font-bold text-white mb-2">Hay Listings</h1>
                    <p className="text-white/80 mb-6">Find quality hay from producers across Australia</p>

                    {/* Map */}
                    <div className="w-full h-[400px] rounded-xl border-2 border-white/20 overflow-hidden shadow-lg">
                        <Map
                            mapboxAccessToken={mapboxToken}
                            initialViewState={initialViewState}
                            style={{ width: '100%', height: '100%' }}
                            mapStyle="mapbox://styles/mapbox/streets-v12"
                        >
                            {listingsWithCoordinates.map(listing => (
                                <Marker
                                    key={listing.id}
                                    longitude={listing.longitude!}
                                    latitude={listing.latitude!}
                                    anchor="bottom"
                                    onClick={e => {
                                        e.originalEvent.stopPropagation();
                                        setSelectedListing(listing);
                                    }}
                                >
                                    <div className="bg-[var(--color-accent)] text-white w-8 h-8 rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:scale-110 transition-transform">
                                        <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clipRule="evenodd" />
                                        </svg>
                                    </div>
                                </Marker>
                            ))}

                            {selectedListing && (
                                <Popup
                                    longitude={selectedListing.longitude!}
                                    latitude={selectedListing.latitude!}
                                    anchor="bottom"
                                    onClose={() => setSelectedListing(null)}
                                    closeButton={true}
                                    closeOnClick={false}
                                >
                                    <div className="p-2 min-w-[200px]">
                                        <h4 className="font-semibold mb-1 text-[var(--color-text)]">{selectedListing.title}</h4>
                                        <p className="text-sm text-gray-600 mb-1">
                                            {selectedListing.hay_type} - {selectedListing.bale_type}
                                        </p>
                                        <p className="text-sm text-gray-600 mb-1">
                                            {selectedListing.quantity?.toLocaleString()} bales
                                        </p>
                                        <p className="text-sm font-semibold text-[var(--color-accent)] mb-2">
                                            {selectedListing.display_price || 'Negotiable'}
                                        </p>
                                        <Link
                                            href={`/hay/${selectedListing.id}`}
                                            className="text-sm text-blue-600 hover:underline"
                                        >
                                            View Details
                                        </Link>
                                    </div>
                                </Popup>
                            )}
                        </Map>
                    </div>
                </div>
            </section>

            {/* Main Content */}
            <section className="py-12 bg-[var(--color-warm-white)]">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col lg:flex-row gap-8">
                        {/* Sidebar Filters */}
                        <aside className="lg:w-72 flex-shrink-0">
                            <div className="card-rustic p-6 sticky top-28">
                                <div className="flex items-center justify-between mb-6">
                                    <h3 className="font-heading text-lg font-semibold text-[var(--color-text)]">Filters</h3>
                                    {hasActiveFilters() && (
                                        <button
                                            onClick={clearFilters}
                                            className="text-sm text-[var(--color-accent)] hover:underline"
                                        >
                                            Clear All
                                        </button>
                                    )}
                                </div>

                                {/* Hay Type */}
                                <div className="mb-6">
                                    <label className="block text-sm font-medium text-[var(--color-text)] mb-2">Hay Type</label>
                                    <div className="space-y-2">
                                        {hayTypes.map(type => (
                                            <label key={type} className="flex items-center gap-2 cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    checked={(localFilters.hay_type || []).includes(type)}
                                                    onChange={e => handleCheckboxArrayChange('hay_type', type, e.target.checked)}
                                                    className="rounded border-[var(--color-border)] text-[var(--color-accent)] focus:ring-[var(--color-accent)]"
                                                />
                                                <span className="text-sm text-[var(--color-text)]">{type}</span>
                                            </label>
                                        ))}
                                    </div>
                                </div>

                                {/* Bale Type */}
                                <div className="mb-6">
                                    <label className="block text-sm font-medium text-[var(--color-text)] mb-2">Bale Type</label>
                                    <div className="space-y-2">
                                        {baleTypes.map(type => (
                                            <label key={type} className="flex items-center gap-2 cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    checked={(localFilters.bale_type || []).includes(type)}
                                                    onChange={e => handleCheckboxArrayChange('bale_type', type, e.target.checked)}
                                                    className="rounded border-[var(--color-border)] text-[var(--color-accent)] focus:ring-[var(--color-accent)]"
                                                />
                                                <span className="text-sm text-[var(--color-text)]">{type}</span>
                                            </label>
                                        ))}
                                    </div>
                                </div>

                                {/* Quality Grade */}
                                <div className="mb-6">
                                    <label className="block text-sm font-medium text-[var(--color-text)] mb-2">Quality Grade</label>
                                    <div className="space-y-2">
                                        {qualityGrades.map(grade => (
                                            <label key={grade} className="flex items-center gap-2 cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    checked={(localFilters.quality_grade || []).includes(grade)}
                                                    onChange={e => handleCheckboxArrayChange('quality_grade', grade, e.target.checked)}
                                                    className="rounded border-[var(--color-border)] text-[var(--color-accent)] focus:ring-[var(--color-accent)]"
                                                />
                                                <span className="text-sm text-[var(--color-text)]">{grade}</span>
                                            </label>
                                        ))}
                                    </div>
                                </div>

                                {/* Location */}
                                <div className="mb-6">
                                    <label className="block text-sm font-medium text-[var(--color-text)] mb-2">Location</label>
                                    <input
                                        type="text"
                                        value={localFilters.location || ''}
                                        onChange={e => handleFilterChange('location', e.target.value)}
                                        placeholder="e.g., Tamworth, NSW"
                                        className="w-full px-4 py-2.5 rounded-lg border border-[var(--color-border)] bg-white text-[var(--color-text)] placeholder-[var(--color-text-light)]/50 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent"
                                    />
                                </div>

                                {/* Price Range */}
                                <div className="mb-6">
                                    <label className="block text-sm font-medium text-[var(--color-text)] mb-2">Price Range</label>
                                    <div className="flex gap-3">
                                        <input
                                            type="number"
                                            value={localFilters.min_price || ''}
                                            onChange={e => handleFilterChange('min_price', e.target.value ? Number(e.target.value) : undefined)}
                                            placeholder="Min"
                                            className="w-full px-4 py-2.5 rounded-lg border border-[var(--color-border)] bg-white text-[var(--color-text)] placeholder-[var(--color-text-light)]/50 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent"
                                        />
                                        <input
                                            type="number"
                                            value={localFilters.max_price || ''}
                                            onChange={e => handleFilterChange('max_price', e.target.value ? Number(e.target.value) : undefined)}
                                            placeholder="Max"
                                            className="w-full px-4 py-2.5 rounded-lg border border-[var(--color-border)] bg-white text-[var(--color-text)] placeholder-[var(--color-text-light)]/50 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent"
                                        />
                                    </div>
                                </div>

                                {/* Delivery Available */}
                                <div className="mb-4">
                                    <label className="flex items-center gap-2 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            checked={localFilters.delivery_available || false}
                                            onChange={e => handleFilterChange('delivery_available', e.target.checked)}
                                            className="rounded border-[var(--color-border)] text-[var(--color-accent)] focus:ring-[var(--color-accent)]"
                                        />
                                        <span className="text-sm text-[var(--color-text)]">Delivery Available</span>
                                    </label>
                                </div>

                                {/* Test Results */}
                                <div className="mb-6">
                                    <label className="flex items-center gap-2 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            checked={localFilters.test_results || false}
                                            onChange={e => handleFilterChange('test_results', e.target.checked)}
                                            className="rounded border-[var(--color-border)] text-[var(--color-accent)] focus:ring-[var(--color-accent)]"
                                        />
                                        <span className="text-sm text-[var(--color-text)]">Test Results Available</span>
                                    </label>
                                </div>

                                <button onClick={applyFilters} className="w-full btn-primary">
                                    Apply Filters
                                </button>
                            </div>
                        </aside>

                        {/* Listings Grid */}
                        <div className="flex-1">
                            {/* Results Header */}
                            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                                <p className="text-[var(--color-text-light)]">
                                    <span className="font-semibold text-[var(--color-text)]">{listings.total}</span> listings found
                                </p>
                            </div>

                            {listings.data.length > 0 ? (
                                <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                                    {listings.data.map(listing => (
                                        <Link
                                            key={listing.id}
                                            href={`/hay/${listing.id}`}
                                            className="card-rustic overflow-hidden hover:shadow-lg transition-shadow group"
                                        >
                                            {listing.photos && listing.photos.length > 0 ? (
                                                <div className="aspect-[16/10] relative overflow-hidden">
                                                    <img
                                                        src={`/storage/${listing.photos[0]}`}
                                                        alt={listing.title}
                                                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                    />
                                                    <span className="absolute top-3 left-3 bg-[var(--color-accent)] text-white text-xs font-semibold px-3 py-1 rounded-full">
                                                        {listing.hay_type}
                                                    </span>
                                                </div>
                                            ) : (
                                                <div className="aspect-[16/10] bg-[var(--color-cream)] flex items-center justify-center relative">
                                                    <svg className="w-16 h-16 text-[var(--color-border)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span className="absolute top-3 left-3 bg-[var(--color-accent)] text-white text-xs font-semibold px-3 py-1 rounded-full">
                                                        {listing.hay_type}
                                                    </span>
                                                </div>
                                            )}

                                            <div className="p-5">
                                                <h3 className="font-heading text-lg font-semibold text-[var(--color-text)] mb-2 line-clamp-2 group-hover:text-[var(--color-accent)] transition-colors">
                                                    {listing.title}
                                                </h3>

                                                <div className="flex flex-wrap gap-2 mb-3">
                                                    <span className="text-xs bg-[var(--color-cream)] px-2 py-1 rounded">{listing.bale_type}</span>
                                                    {listing.quality_grade && (
                                                        <span className="text-xs bg-[var(--color-cream)] px-2 py-1 rounded">{listing.quality_grade}</span>
                                                    )}
                                                </div>

                                                <div className="flex items-center gap-2 text-sm text-[var(--color-text-light)] mb-3">
                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    {listing.location}
                                                </div>

                                                <div className="flex items-center justify-between">
                                                    <div>
                                                        <span className="text-xs text-[var(--color-text-light)]">{listing.quantity?.toLocaleString()} bales</span>
                                                    </div>
                                                    <div className="text-right">
                                                        <span className="font-heading text-lg font-bold text-[var(--color-accent)]">
                                                            {listing.display_price || 'Negotiable'}
                                                        </span>
                                                    </div>
                                                </div>

                                                {listing.delivery_available && (
                                                    <div className="mt-3 pt-3 border-t border-[var(--color-border)]">
                                                        <span className="inline-flex items-center gap-1 text-xs text-green-600">
                                                            <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Delivery available
                                                            {listing.delivery_radius_km && ` (${listing.delivery_radius_km}km)`}
                                                        </span>
                                                    </div>
                                                )}
                                            </div>
                                        </Link>
                                    ))}
                                </div>
                            ) : (
                                <div className="card-rustic p-12 text-center">
                                    <div className="w-20 h-20 mx-auto mb-6 rounded-full bg-[var(--color-cream)] flex items-center justify-center">
                                        <svg className="w-10 h-10 text-[var(--color-text-light)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <h3 className="font-heading text-xl font-semibold text-[var(--color-text)] mb-2">No hay listings found</h3>
                                    <p className="text-[var(--color-text-light)] mb-6">
                                        Try adjusting your filters or check back later for new listings.
                                    </p>
                                    <button onClick={clearFilters} className="btn-outline">
                                        Clear All Filters
                                    </button>
                                </div>
                            )}

                            {/* Pagination */}
                            {listings.last_page > 1 && (
                                <div className="mt-12 flex justify-center">
                                    <div className="flex items-center gap-2">
                                        {listings.current_page > 1 && (
                                            <Link
                                                href={`/hay?page=${listings.current_page - 1}`}
                                                className="px-4 py-2 rounded-lg border border-[var(--color-border)] text-[var(--color-text)] hover:bg-[var(--color-cream)] transition-colors"
                                                preserveScroll
                                            >
                                                Previous
                                            </Link>
                                        )}
                                        {Array.from({ length: Math.min(5, listings.last_page) }, (_, i) => {
                                            const page = Math.max(1, Math.min(listings.last_page - 4, listings.current_page - 2)) + i;
                                            if (page > listings.last_page) return null;
                                            return (
                                                <Link
                                                    key={page}
                                                    href={`/hay?page=${page}`}
                                                    className={`px-4 py-2 rounded-lg transition-colors ${
                                                        page === listings.current_page
                                                            ? 'bg-[var(--color-primary)] text-white'
                                                            : 'border border-[var(--color-border)] text-[var(--color-text)] hover:bg-[var(--color-cream)]'
                                                    }`}
                                                    preserveScroll
                                                >
                                                    {page}
                                                </Link>
                                            );
                                        })}
                                        {listings.current_page < listings.last_page && (
                                            <Link
                                                href={`/hay?page=${listings.current_page + 1}`}
                                                className="px-4 py-2 rounded-lg border border-[var(--color-border)] text-[var(--color-text)] hover:bg-[var(--color-cream)] transition-colors"
                                                preserveScroll
                                            >
                                                Next
                                            </Link>
                                        )}
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </section>
        </>
    );
}
