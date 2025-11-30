<x-layouts.public title="Search Listings">
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-primary-light)] py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="font-heading text-4xl md:text-5xl font-bold text-white mb-4">
                    Find Your Perfect Listing
                </h1>
                <p class="text-white/80 text-lg max-w-2xl mx-auto">
                    Browse through our marketplace of quality steers, studs, genetics, equipment and services.
                </p>
            </div>

            <!-- Search Bar -->
            <form action="{{ route('search') }}" method="GET" class="mt-8 max-w-3xl mx-auto">
                <div class="relative">
                    <input 
                        type="text" 
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Search by name, breed, or location..."
                        class="w-full px-6 py-4 pl-14 rounded-xl border-0 shadow-lg text-[var(--color-text)] placeholder-[var(--color-text-light)] focus:ring-2 focus:ring-[var(--color-accent)]"
                    >
                    <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-[var(--color-text-light)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 btn-primary py-2 px-6">
                        Search
                    </button>
                </div>

                <!-- Quick Category Filters -->
                <div class="flex flex-wrap justify-center gap-3 mt-6">
                    @php
                        $selectedCategories = $filters['category'] ?? [];
                    @endphp
                    
                    @foreach(['steers' => 'Steers', 'studs' => 'Studs', 'genetics' => 'Genetics', 'equipment' => 'Equipment', 'services' => 'Services'] as $value => $label)
                        <label class="inline-flex items-center gap-2 px-4 py-2 rounded-full cursor-pointer transition-all
                            {{ in_array($value, $selectedCategories) ? 'bg-white text-[var(--color-primary)] shadow-md' : 'bg-white/20 text-white hover:bg-white/30' }}">
                            <input 
                                type="checkbox" 
                                name="category[]" 
                                value="{{ $value }}"
                                {{ in_array($value, $selectedCategories) ? 'checked' : '' }}
                                onchange="this.form.submit()"
                                class="sr-only"
                            >
                            <span class="text-sm font-medium">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </form>
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
                            @if(!empty($filters['breed']) || !empty($filters['location']) || !empty($filters['min_price']) || !empty($filters['max_price']))
                                <a href="{{ route('search', ['category' => $filters['category'] ?? []]) }}" class="text-sm text-[var(--color-accent)] hover:underline">
                                    Clear All
                                </a>
                            @endif
                        </div>

                        <form action="{{ route('search') }}" method="GET" id="filter-form">
                            <!-- Preserve category selection -->
                            @foreach(($filters['category'] ?? []) as $cat)
                                <input type="hidden" name="category[]" value="{{ $cat }}">
                            @endforeach

                            <!-- Breed Filter -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-[var(--color-text)] mb-2">Breed</label>
                                <input 
                                    type="text" 
                                    name="breed"
                                    value="{{ $filters['breed'] ?? '' }}"
                                    placeholder="e.g. Angus, Hereford"
                                    class="w-full px-4 py-2.5 rounded-lg border border-[var(--color-border)] bg-white text-[var(--color-text)] placeholder-[var(--color-text-light)]/50 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent"
                                >
                            </div>

                            <!-- Location Filter -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-[var(--color-text)] mb-2">Location</label>
                                <input 
                                    type="text" 
                                    name="location"
                                    value="{{ $filters['location'] ?? '' }}"
                                    placeholder="e.g. NSW, Queensland"
                                    class="w-full px-4 py-2.5 rounded-lg border border-[var(--color-border)] bg-white text-[var(--color-text)] placeholder-[var(--color-text-light)]/50 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent"
                                >
                            </div>

                            <!-- Price Range -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-[var(--color-text)] mb-2">Price Range</label>
                                <div class="flex gap-3">
                                    <input 
                                        type="number" 
                                        name="min_price"
                                        value="{{ $filters['min_price'] ?? '' }}"
                                        placeholder="Min"
                                        class="w-full px-4 py-2.5 rounded-lg border border-[var(--color-border)] bg-white text-[var(--color-text)] placeholder-[var(--color-text-light)]/50 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent"
                                    >
                                    <input 
                                        type="number" 
                                        name="max_price"
                                        value="{{ $filters['max_price'] ?? '' }}"
                                        placeholder="Max"
                                        class="w-full px-4 py-2.5 rounded-lg border border-[var(--color-border)] bg-white text-[var(--color-text)] placeholder-[var(--color-text-light)]/50 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent"
                                    >
                                </div>
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
                            <span class="font-semibold text-[var(--color-text)]">{{ $listings['total'] }}</span> listings found
                        </p>

                        <form action="{{ route('search') }}" method="GET" class="flex items-center gap-3">
                            <!-- Preserve all current filters -->
                            @foreach(($filters['category'] ?? []) as $cat)
                                <input type="hidden" name="category[]" value="{{ $cat }}">
                            @endforeach
                            @if(!empty($filters['search']))
                                <input type="hidden" name="search" value="{{ $filters['search'] }}">
                            @endif
                            @if(!empty($filters['breed']))
                                <input type="hidden" name="breed" value="{{ $filters['breed'] }}">
                            @endif
                            @if(!empty($filters['location']))
                                <input type="hidden" name="location" value="{{ $filters['location'] }}">
                            @endif
                            @if(!empty($filters['min_price']))
                                <input type="hidden" name="min_price" value="{{ $filters['min_price'] }}">
                            @endif
                            @if(!empty($filters['max_price']))
                                <input type="hidden" name="max_price" value="{{ $filters['max_price'] }}">
                            @endif

                            <label class="text-sm text-[var(--color-text-light)]">Sort by:</label>
                            <select 
                                name="sort" 
                                onchange="this.form.submit()"
                                class="px-4 py-2 rounded-lg border border-[var(--color-border)] bg-white text-[var(--color-text)] focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent"
                            >
                                <option value="newest" {{ ($filters['sort'] ?? 'newest') === 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="price_low" {{ ($filters['sort'] ?? '') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ ($filters['sort'] ?? '') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name" {{ ($filters['sort'] ?? '') === 'name' ? 'selected' : '' }}>Name A-Z</option>
                            </select>
                        </form>
                    </div>

                    @if(count($listings['data']) > 0)
                        <!-- Listings Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($listings['data'] as $listing)
                                @include('components.public.listing-card', [
                                    'listing' => $listing, 
                                    'type' => $listing->listing_type
                                ])
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($listings['last_page'] > 1)
                            <div class="mt-12 flex justify-center">
                                <div class="flex items-center gap-2">
                                    @if($listings['current_page'] > 1)
                                        <a href="{{ route('search', array_merge($filters, ['page' => $listings['current_page'] - 1])) }}" 
                                           class="px-4 py-2 rounded-lg border border-[var(--color-border)] text-[var(--color-text)] hover:bg-[var(--color-cream)] transition-colors">
                                            Previous
                                        </a>
                                    @endif

                                    @for($i = max(1, $listings['current_page'] - 2); $i <= min($listings['last_page'], $listings['current_page'] + 2); $i++)
                                        <a href="{{ route('search', array_merge($filters, ['page' => $i])) }}" 
                                           class="px-4 py-2 rounded-lg {{ $i === $listings['current_page'] ? 'bg-[var(--color-primary)] text-white' : 'border border-[var(--color-border)] text-[var(--color-text)] hover:bg-[var(--color-cream)]' }} transition-colors">
                                            {{ $i }}
                                        </a>
                                    @endfor

                                    @if($listings['current_page'] < $listings['last_page'])
                                        <a href="{{ route('search', array_merge($filters, ['page' => $listings['current_page'] + 1])) }}" 
                                           class="px-4 py-2 rounded-lg border border-[var(--color-border)] text-[var(--color-text)] hover:bg-[var(--color-cream)] transition-colors">
                                            Next
                                        </a>
                                    @endif
                                </div>
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
                            <h3 class="font-heading text-xl font-semibold text-[var(--color-text)] mb-2">No listings found</h3>
                            <p class="text-[var(--color-text-light)] mb-6">
                                We couldn't find any listings matching your criteria. Try adjusting your filters or search terms.
                            </p>
                            <a href="{{ route('search') }}" class="btn-outline inline-block">
                                Clear All Filters
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-[var(--color-cream)]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-heading text-3xl font-bold text-[var(--color-text)] mb-4">
                Can't Find What You're Looking For?
            </h2>
            <p class="text-[var(--color-text-light)] mb-8">
                Create an account to get notified when new listings matching your criteria are posted.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}" class="btn-primary">
                    Create Free Account
                </a>
                <a href="{{ route('home') }}" class="btn-outline">
                    Back to Home
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>

