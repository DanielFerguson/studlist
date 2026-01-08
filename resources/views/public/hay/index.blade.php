<x-layouts.public title="Hay Listings">
    @push('styles')
        <link href="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css" rel="stylesheet">
        <style>
            .hay-popup-title {
                font-weight: 600;
                margin-bottom: 4px;
                color: #3D2914;
            }
            .hay-popup-info {
                font-size: 14px;
                color: #5D4E37;
                margin-bottom: 4px;
            }
            .hay-popup-price {
                font-size: 14px;
                font-weight: 600;
                color: #CC5500;
                margin-bottom: 8px;
            }
            .hay-popup-link {
                font-size: 14px;
                color: #2563eb;
                text-decoration: none;
            }
            .hay-popup-link:hover {
                text-decoration: underline;
            }
        </style>
    @endpush

    <!-- Hero Section with Map -->
    <section class="bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-primary-light)] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-4">
                <ol class="flex items-center gap-2 text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="text-white/70 hover:text-white">Home</a>
                    </li>
                    <li><span class="text-white/50">/</span></li>
                    <li><span class="text-white font-medium">Hay</span></li>
                </ol>
            </nav>

            <h1 class="font-heading text-3xl md:text-4xl font-bold text-white mb-2">Hay Listings</h1>
            <p class="text-white/80 mb-6">Find quality hay from producers across Australia</p>

            <!-- Map -->
            <div id="hay-map" class="w-full h-[400px] rounded-xl border-2 border-white/20 overflow-hidden shadow-lg"></div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 bg-[var(--color-warm-white)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filters -->
                <aside class="lg:w-72 flex-shrink-0">
                    <div class="card-rustic p-6 sticky top-28">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-heading text-lg font-semibold text-[var(--color-text)]">Filters</h3>
                            @if(!empty($filters['hay_type']) || !empty($filters['bale_type']) || !empty($filters['quality_grade']) || !empty($filters['location']) || !empty($filters['min_price']) || !empty($filters['max_price']) || !empty($filters['delivery_available']) || !empty($filters['test_results']))
                                <a href="{{ route('hay.index') }}" class="text-sm text-[var(--color-accent)] hover:underline">
                                    Clear All
                                </a>
                            @endif
                        </div>

                        <form action="{{ route('hay.index') }}" method="GET">
                            <!-- Hay Type -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-[var(--color-text)] mb-2">Hay Type</label>
                                <select name="hay_type"
                                    class="w-full px-4 py-2.5 rounded-lg border border-[var(--color-border)] bg-white text-[var(--color-text)] focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                                    <option value="">All Types</option>
                                    @foreach(App\Enums\HayType::cases() as $type)
                                        <option value="{{ $type->value }}" {{ ($filters['hay_type'] ?? '') === $type->value ? 'selected' : '' }}>
                                            {{ $type->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Bale Type -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-[var(--color-text)] mb-2">Bale Type</label>
                                <select name="bale_type"
                                    class="w-full px-4 py-2.5 rounded-lg border border-[var(--color-border)] bg-white text-[var(--color-text)] focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                                    <option value="">All Types</option>
                                    @foreach(App\Enums\BaleType::cases() as $type)
                                        <option value="{{ $type->value }}" {{ ($filters['bale_type'] ?? '') === $type->value ? 'selected' : '' }}>
                                            {{ $type->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Quality Grade -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-[var(--color-text)] mb-2">Quality Grade</label>
                                <select name="quality_grade"
                                    class="w-full px-4 py-2.5 rounded-lg border border-[var(--color-border)] bg-white text-[var(--color-text)] focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                                    <option value="">All Grades</option>
                                    @foreach(App\Enums\HayQualityGrade::cases() as $grade)
                                        <option value="{{ $grade->value }}" {{ ($filters['quality_grade'] ?? '') === $grade->value ? 'selected' : '' }}>
                                            {{ $grade->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Location -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-[var(--color-text)] mb-2">Location</label>
                                <input type="text" name="location" value="{{ $filters['location'] ?? '' }}"
                                    placeholder="e.g., Tamworth, NSW"
                                    class="w-full px-4 py-2.5 rounded-lg border border-[var(--color-border)] bg-white text-[var(--color-text)] placeholder-[var(--color-text-light)]/50 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                            </div>

                            <!-- Price Range -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-[var(--color-text)] mb-2">Price Range</label>
                                <div class="flex gap-3">
                                    <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}"
                                        placeholder="Min"
                                        class="w-full px-4 py-2.5 rounded-lg border border-[var(--color-border)] bg-white text-[var(--color-text)] placeholder-[var(--color-text-light)]/50 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                                    <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}"
                                        placeholder="Max"
                                        class="w-full px-4 py-2.5 rounded-lg border border-[var(--color-border)] bg-white text-[var(--color-text)] placeholder-[var(--color-text-light)]/50 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                                </div>
                            </div>

                            <!-- Delivery Available -->
                            <div class="mb-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="delivery_available" value="1"
                                        {{ isset($filters['delivery_available']) ? 'checked' : '' }}
                                        class="rounded border-[var(--color-border)] text-[var(--color-accent)] focus:ring-[var(--color-accent)]">
                                    <span class="text-sm text-[var(--color-text)]">Delivery Available</span>
                                </label>
                            </div>

                            <!-- Test Results -->
                            <div class="mb-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="test_results" value="1"
                                        {{ isset($filters['test_results']) ? 'checked' : '' }}
                                        class="rounded border-[var(--color-border)] text-[var(--color-accent)] focus:ring-[var(--color-accent)]">
                                    <span class="text-sm text-[var(--color-text)]">Test Results Available</span>
                                </label>
                            </div>

                            <button type="submit" class="w-full btn-primary">
                                Apply Filters
                            </button>
                        </form>
                    </div>
                </aside>

                <!-- Listings Grid -->
                <div class="flex-1">
                    <!-- Results Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                        <p class="text-[var(--color-text-light)]">
                            <span class="font-semibold text-[var(--color-text)]">{{ $listings->total() }}</span> listings found
                        </p>
                    </div>

                    @if($listings->count() > 0)
                        <!-- Listings Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($listings as $listing)
                                <a href="{{ route('hay.show', $listing) }}" class="card-rustic overflow-hidden hover:shadow-lg transition-shadow group">
                                    @if($listing->photos && count($listing->photos) > 0)
                                        <div class="aspect-[16/10] relative overflow-hidden">
                                            <img src="{{ Storage::url($listing->photos[0]) }}" alt="{{ $listing->title }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            <span class="absolute top-3 left-3 bg-[var(--color-accent)] text-white text-xs font-semibold px-3 py-1 rounded-full">
                                                {{ $listing->hay_type }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="aspect-[16/10] bg-[var(--color-cream)] flex items-center justify-center relative">
                                            <svg class="w-16 h-16 text-[var(--color-border)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="absolute top-3 left-3 bg-[var(--color-accent)] text-white text-xs font-semibold px-3 py-1 rounded-full">
                                                {{ $listing->hay_type }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="p-5">
                                        <h3 class="font-heading text-lg font-semibold text-[var(--color-text)] mb-2 line-clamp-2 group-hover:text-[var(--color-accent)] transition-colors">
                                            {{ $listing->title }}
                                        </h3>

                                        <div class="flex flex-wrap gap-2 mb-3">
                                            <span class="text-xs bg-[var(--color-cream)] px-2 py-1 rounded">{{ $listing->bale_type }}</span>
                                            @if($listing->quality_grade)
                                                <span class="text-xs bg-[var(--color-cream)] px-2 py-1 rounded">{{ $listing->quality_grade }}</span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-2 text-sm text-[var(--color-text-light)] mb-3">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $listing->location }}
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="text-xs text-[var(--color-text-light)]">{{ number_format($listing->quantity) }} bales</span>
                                            </div>
                                            <div class="text-right">
                                                <span class="font-heading text-lg font-bold text-[var(--color-accent)]">
                                                    {{ $listing->display_price ?? 'Negotiable' }}
                                                </span>
                                            </div>
                                        </div>

                                        @if($listing->delivery_available)
                                            <div class="mt-3 pt-3 border-t border-[var(--color-border)]">
                                                <span class="inline-flex items-center gap-1 text-xs text-green-600">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Delivery available
                                                    @if($listing->delivery_radius_km)
                                                        ({{ $listing->delivery_radius_km }}km)
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($listings->hasPages())
                            <div class="mt-12">
                                {{ $listings->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="card-rustic p-12 text-center">
                            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-[var(--color-cream)] flex items-center justify-center">
                                <svg class="w-10 h-10 text-[var(--color-text-light)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-heading text-xl font-semibold text-[var(--color-text)] mb-2">No hay listings found</h3>
                            <p class="text-[var(--color-text-light)] mb-6">
                                Try adjusting your filters or check back later for new listings.
                            </p>
                            <a href="{{ route('hay.index') }}" class="btn-outline inline-block">
                                Clear All Filters
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script src="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mapContainer = document.getElementById('hay-map');
                if (!mapContainer) return;

                mapboxgl.accessToken = '{{ $mapboxToken }}';

                const map = new mapboxgl.Map({
                    container: 'hay-map',
                    style: 'mapbox://styles/mapbox/streets-v12',
                    center: [133.7751, -25.2744],
                    zoom: 3
                });

                map.addControl(new mapboxgl.NavigationControl());

                const listings = @json($listingsWithCoordinates);

                function createPopupContent(listing) {
                    const container = document.createElement('div');
                    container.style.padding = '8px';

                    const title = document.createElement('h4');
                    title.className = 'hay-popup-title';
                    title.textContent = listing.title || '';
                    container.appendChild(title);

                    const info = document.createElement('p');
                    info.className = 'hay-popup-info';
                    info.textContent = (listing.hay_type || '') + ' - ' + (listing.bale_type || '');
                    container.appendChild(info);

                    if (listing.quantity) {
                        const qty = document.createElement('p');
                        qty.className = 'hay-popup-info';
                        qty.textContent = Number(listing.quantity).toLocaleString() + ' bales';
                        container.appendChild(qty);
                    }

                    const price = document.createElement('p');
                    price.className = 'hay-popup-price';
                    price.textContent = listing.display_price || 'Negotiable';
                    container.appendChild(price);

                    const link = document.createElement('a');
                    link.className = 'hay-popup-link';
                    link.href = '/hay/' + listing.id;
                    link.textContent = 'View Details →';
                    container.appendChild(link);

                    return container;
                }

                map.on('load', function() {
                    // Convert listings to GeoJSON
                    const geojson = {
                        type: 'FeatureCollection',
                        features: listings.filter(l => l.latitude && l.longitude).map(listing => ({
                            type: 'Feature',
                            properties: {
                                id: listing.id,
                                title: listing.title,
                                hay_type: listing.hay_type,
                                bale_type: listing.bale_type,
                                quantity: listing.quantity,
                                display_price: listing.display_price
                            },
                            geometry: {
                                type: 'Point',
                                coordinates: [listing.longitude, listing.latitude]
                            }
                        }))
                    };

                    // Add clustered source
                    map.addSource('hay-listings', {
                        type: 'geojson',
                        data: geojson,
                        cluster: true,
                        clusterMaxZoom: 14,
                        clusterRadius: 50
                    });

                    // Cluster circles layer
                    map.addLayer({
                        id: 'clusters',
                        type: 'circle',
                        source: 'hay-listings',
                        filter: ['has', 'point_count'],
                        paint: {
                            'circle-color': '#CC5500',
                            'circle-radius': [
                                'step', ['get', 'point_count'],
                                20,
                                10, 25,
                                50, 30
                            ],
                            'circle-stroke-width': 3,
                            'circle-stroke-color': '#fff'
                        }
                    });

                    // Cluster count labels
                    map.addLayer({
                        id: 'cluster-count',
                        type: 'symbol',
                        source: 'hay-listings',
                        filter: ['has', 'point_count'],
                        layout: {
                            'text-field': ['get', 'point_count_abbreviated'],
                            'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                            'text-size': 14
                        },
                        paint: {
                            'text-color': '#ffffff'
                        }
                    });

                    // Unclustered individual points
                    map.addLayer({
                        id: 'unclustered-point',
                        type: 'circle',
                        source: 'hay-listings',
                        filter: ['!', ['has', 'point_count']],
                        paint: {
                            'circle-color': '#CC5500',
                            'circle-radius': 10,
                            'circle-stroke-width': 2,
                            'circle-stroke-color': '#fff'
                        }
                    });

                    // Click cluster to zoom in
                    map.on('click', 'clusters', (e) => {
                        const features = map.queryRenderedFeatures(e.point, { layers: ['clusters'] });
                        const clusterId = features[0].properties.cluster_id;
                        map.getSource('hay-listings').getClusterExpansionZoom(clusterId, (err, zoom) => {
                            if (err) return;
                            map.easeTo({
                                center: features[0].geometry.coordinates,
                                zoom: zoom
                            });
                        });
                    });

                    // Click unclustered point to show popup
                    map.on('click', 'unclustered-point', (e) => {
                        const properties = e.features[0].properties;
                        const coordinates = e.features[0].geometry.coordinates.slice();

                        const popupContent = createPopupContent({
                            id: properties.id,
                            title: properties.title,
                            hay_type: properties.hay_type,
                            bale_type: properties.bale_type,
                            quantity: properties.quantity,
                            display_price: properties.display_price
                        });

                        new mapboxgl.Popup({ offset: 15, maxWidth: '280px' })
                            .setLngLat(coordinates)
                            .setDOMContent(popupContent)
                            .addTo(map);
                    });

                    // Change cursor on hover
                    map.on('mouseenter', 'clusters', () => map.getCanvas().style.cursor = 'pointer');
                    map.on('mouseleave', 'clusters', () => map.getCanvas().style.cursor = '');
                    map.on('mouseenter', 'unclustered-point', () => map.getCanvas().style.cursor = 'pointer');
                    map.on('mouseleave', 'unclustered-point', () => map.getCanvas().style.cursor = '');

                    // Fit bounds to show all markers
                    if (geojson.features.length > 0) {
                        const bounds = new mapboxgl.LngLatBounds();
                        geojson.features.forEach(f => bounds.extend(f.geometry.coordinates));
                        map.fitBounds(bounds, { padding: 50, maxZoom: 10 });
                    }
                });
            });
        </script>
    @endpush
</x-layouts.public>
