<x-layouts.public :title="$listing->title">
    <article class="py-12 bg-[var(--color-warm-white)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center gap-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-[var(--color-text-light)] hover:text-[var(--color-accent)]">Home</a></li>
                    <li><span class="text-[var(--color-text-light)]">/</span></li>
                    <li><a href="{{ route('hay.index') }}" class="text-[var(--color-text-light)] hover:text-[var(--color-accent)]">Hay</a></li>
                    <li><span class="text-[var(--color-text-light)]">/</span></li>
                    <li><span class="text-[var(--color-text)] font-medium">{{ $listing->title }}</span></li>
                </ol>
            </nav>

            <div class="grid lg:grid-cols-3 gap-10">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Image Gallery -->
                    <div class="card-rustic overflow-hidden mb-8">
                        @if($listing->photos && count($listing->photos) > 0)
                            <div class="aspect-[16/10] relative">
                                <img
                                    src="{{ Storage::url($listing->photos[0]) }}"
                                    alt="{{ $listing->title }}"
                                    class="w-full h-full object-cover"
                                    id="main-image"
                                >
                                <span class="absolute top-4 left-4 bg-[var(--color-accent)] text-white text-sm font-semibold px-4 py-1.5 rounded-full">
                                    Hay
                                </span>
                            </div>
                            @if(count($listing->photos) > 1)
                                <div class="p-4 flex gap-3 overflow-x-auto">
                                    @foreach($listing->photos as $index => $photo)
                                        <button
                                            onclick="document.getElementById('main-image').src='{{ Storage::url($photo) }}'"
                                            class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden border-2 border-[var(--color-border)] hover:border-[var(--color-accent)] transition-colors"
                                            data-track-image="{{ $index }}"
                                        >
                                            <img src="{{ Storage::url($photo) }}" alt="" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="aspect-[16/10] bg-[var(--color-cream)] flex items-center justify-center">
                                <svg class="w-24 h-24 text-[var(--color-border)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Listing Title & Quick Info -->
                    <div class="mb-8">
                        <h1 class="font-heading text-3xl md:text-4xl font-bold text-[var(--color-text)] mb-4">
                            {{ $listing->title }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-4 text-[var(--color-text-light)]">
                            @if($listing->hay_type)
                                <span class="inline-flex items-center gap-1.5 bg-[var(--color-accent)]/10 text-[var(--color-accent)] px-3 py-1.5 rounded-full text-sm font-medium">
                                    {{ $listing->hay_type }}
                                </span>
                            @endif
                            @if($listing->bale_type)
                                <span class="inline-flex items-center gap-1.5 bg-[var(--color-cream)] px-3 py-1.5 rounded-full text-sm">
                                    {{ $listing->bale_type }}
                                </span>
                            @endif
                            @if($listing->quantity)
                                <span class="inline-flex items-center gap-1.5 bg-[var(--color-cream)] px-3 py-1.5 rounded-full text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                    {{ number_format($listing->quantity) }} bales available
                                </span>
                            @endif
                            @if($listing->delivery_available)
                                <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-700 px-3 py-1.5 rounded-full text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Delivery Available
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="card-rustic p-8 mb-8">
                        <h2 class="font-heading text-xl font-semibold text-[var(--color-text)] mb-6">Hay Details</h2>

                        <div class="grid sm:grid-cols-2 gap-6">
                            @if($listing->hay_type)
                                <div>
                                    <dt class="text-sm text-[var(--color-text-light)] mb-1">Hay Type</dt>
                                    <dd class="font-medium text-[var(--color-text)]">{{ $listing->hay_type }}</dd>
                                </div>
                            @endif
                            @if($listing->bale_type)
                                <div>
                                    <dt class="text-sm text-[var(--color-text-light)] mb-1">Bale Type</dt>
                                    <dd class="font-medium text-[var(--color-text)]">{{ $listing->bale_type }}</dd>
                                </div>
                            @endif
                            @if($listing->weight_per_bale)
                                <div>
                                    <dt class="text-sm text-[var(--color-text-light)] mb-1">Weight per Bale</dt>
                                    <dd class="font-medium text-[var(--color-text)]">{{ number_format($listing->weight_per_bale) }} kg</dd>
                                </div>
                            @endif
                            @if($listing->season_cut)
                                <div>
                                    <dt class="text-sm text-[var(--color-text-light)] mb-1">Season/Cut</dt>
                                    <dd class="font-medium text-[var(--color-text)]">{{ $listing->season_cut }}</dd>
                                </div>
                            @endif
                            @if($listing->cut_year)
                                <div>
                                    <dt class="text-sm text-[var(--color-text-light)] mb-1">Cut Year</dt>
                                    <dd class="font-medium text-[var(--color-text)]">{{ $listing->cut_year }}</dd>
                                </div>
                            @endif
                            @if($listing->storage_type)
                                <div>
                                    <dt class="text-sm text-[var(--color-text-light)] mb-1">Storage</dt>
                                    <dd class="font-medium text-[var(--color-text)]">{{ $listing->storage_type }}</dd>
                                </div>
                            @endif
                            @if($listing->location)
                                <div>
                                    <dt class="text-sm text-[var(--color-text-light)] mb-1">Location</dt>
                                    <dd class="font-medium text-[var(--color-text)]">{{ $listing->location }}</dd>
                                </div>
                            @endif
                            @if($listing->minimum_order_quantity)
                                <div>
                                    <dt class="text-sm text-[var(--color-text-light)] mb-1">Minimum Order</dt>
                                    <dd class="font-medium text-[var(--color-text)]">{{ number_format($listing->minimum_order_quantity) }} bales</dd>
                                </div>
                            @endif
                            @if($listing->delivery_radius_km)
                                <div>
                                    <dt class="text-sm text-[var(--color-text-light)] mb-1">Delivery Radius</dt>
                                    <dd class="font-medium text-[var(--color-text)]">{{ number_format($listing->delivery_radius_km) }} km</dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quality & Testing -->
                    @if($listing->quality_grade || $listing->test_results_available || $listing->protein_percentage || $listing->moisture_percentage || $listing->energy_mj_kg || $listing->nitrate_level)
                        <div class="card-rustic p-8 mb-8">
                            <h2 class="font-heading text-xl font-semibold text-[var(--color-text)] mb-6">Quality & Testing</h2>

                            <div class="grid sm:grid-cols-2 gap-6">
                                @if($listing->quality_grade)
                                    <div>
                                        <dt class="text-sm text-[var(--color-text-light)] mb-1">Quality Grade</dt>
                                        <dd class="font-medium text-[var(--color-text)]">{{ $listing->quality_grade }}</dd>
                                    </div>
                                @endif
                                @if($listing->protein_percentage)
                                    <div>
                                        <dt class="text-sm text-[var(--color-text-light)] mb-1">Protein</dt>
                                        <dd class="font-medium text-[var(--color-text)]">{{ $listing->protein_percentage }}%</dd>
                                    </div>
                                @endif
                                @if($listing->moisture_percentage)
                                    <div>
                                        <dt class="text-sm text-[var(--color-text-light)] mb-1">Moisture</dt>
                                        <dd class="font-medium text-[var(--color-text)]">{{ $listing->moisture_percentage }}%</dd>
                                    </div>
                                @endif
                                @if($listing->energy_mj_kg)
                                    <div>
                                        <dt class="text-sm text-[var(--color-text-light)] mb-1">Energy</dt>
                                        <dd class="font-medium text-[var(--color-text)]">{{ $listing->energy_mj_kg }} MJ/kg DM</dd>
                                    </div>
                                @endif
                                @if($listing->nitrate_level)
                                    <div>
                                        <dt class="text-sm text-[var(--color-text-light)] mb-1">Nitrate Level</dt>
                                        <dd class="font-medium text-[var(--color-text)]">{{ $listing->nitrate_level }}</dd>
                                    </div>
                                @endif
                                @if($listing->weather_damaged)
                                    <div>
                                        <dt class="text-sm text-[var(--color-text-light)] mb-1">Weather Damage</dt>
                                        <dd class="font-medium text-yellow-600">Yes - Some weather damage</dd>
                                    </div>
                                @endif
                            </div>

                            @if($listing->test_results_available)
                                <div class="mt-6 p-4 bg-green-50 rounded-lg">
                                    <div class="flex items-center gap-2 text-green-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-medium">Feed test results available</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Description -->
                    @if($listing->description)
                        <div class="card-rustic p-8">
                            <h2 class="font-heading text-xl font-semibold text-[var(--color-text)] mb-4">Description</h2>
                            <div class="prose prose-brown max-w-none text-[var(--color-text-light)] leading-relaxed">
                                {!! nl2br(e($listing->description)) !!}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Price & Contact Card -->
                    <div class="card-rustic p-6 sticky top-28">
                        <div class="text-center pb-6 mb-6 border-b border-[var(--color-border)]">
                            <span class="text-sm text-[var(--color-text-light)]">Price</span>
                            @if($listing->price_type === 'Negotiable')
                                <div class="font-heading text-3xl font-bold text-[var(--color-accent)]">
                                    Negotiable
                                </div>
                            @else
                                <div class="font-heading text-3xl font-bold text-[var(--color-accent)]">
                                    {{ $listing->display_price }}
                                </div>
                                @if($listing->price_per_bale && $listing->price_per_tonne)
                                    <div class="text-sm text-[var(--color-text-light)] mt-1">
                                        Also available: ${{ number_format($listing->price_per_tonne, 2) }}/tonne
                                    </div>
                                @endif
                            @endif
                            @if($listing->quantity)
                                <span class="text-sm text-[var(--color-text-light)]">{{ number_format($listing->quantity) }} bales available</span>
                            @endif
                        </div>

                        <h3 class="font-heading text-lg font-semibold text-[var(--color-text)] mb-4">Contact Seller</h3>

                        @if($listing->business_contact)
                            <div class="mb-4">
                                <span class="text-sm text-[var(--color-text-light)]">Business</span>
                                <p class="font-medium text-[var(--color-text)]">{{ $listing->business_contact }}</p>
                            </div>
                        @endif

                        @if($listing->phone_contact)
                            <a href="tel:{{ $listing->phone_contact }}" class="flex items-center gap-3 p-4 rounded-lg bg-[var(--color-cream)] hover:bg-[var(--color-accent)]/10 transition-colors mb-3" data-track-contact="phone">
                                <div class="w-10 h-10 rounded-full bg-[var(--color-accent)] flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-xs text-[var(--color-text-light)]">Phone</span>
                                    <p class="font-medium text-[var(--color-text)]">{{ $listing->phone_contact }}</p>
                                </div>
                            </a>
                        @endif

                        @if($listing->email_contact)
                            <a href="mailto:{{ $listing->email_contact }}" class="flex items-center gap-3 p-4 rounded-lg bg-[var(--color-cream)] hover:bg-[var(--color-accent)]/10 transition-colors mb-3" data-track-contact="email">
                                <div class="w-10 h-10 rounded-full bg-[var(--color-accent)] flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-xs text-[var(--color-text-light)]">Email</span>
                                    <p class="font-medium text-[var(--color-text)] break-all">{{ $listing->email_contact }}</p>
                                </div>
                            </a>
                        @endif

                        @if($listing->pic_number)
                            <div class="mb-4 p-3 bg-[var(--color-cream)] rounded-lg">
                                <span class="text-xs text-[var(--color-text-light)]">PIC Number</span>
                                <p class="font-medium text-[var(--color-text)]">{{ $listing->pic_number }}</p>
                            </div>
                        @endif

                        <!-- Share Button -->
                        <button id="share-listing" class="w-full flex items-center justify-center gap-2 p-4 rounded-lg border-2 border-[var(--color-border)] hover:border-[var(--color-accent)] hover:bg-[var(--color-cream)] transition-colors mb-6">
                            <svg class="w-5 h-5 text-[var(--color-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                            <span class="font-medium text-[var(--color-text)]">Share Listing</span>
                        </button>

                        <!-- Free Listing Badge -->
                        <div class="p-4 bg-[var(--color-secondary)]/10 rounded-lg text-center mb-6">
                            <span class="text-[var(--color-secondary)] font-medium text-sm">Free Listing</span>
                        </div>

                        <!-- Listed By -->
                        @if($listing->user)
                            <div class="pt-6 border-t border-[var(--color-border)]">
                                <span class="text-xs text-[var(--color-text-light)]">Listed by</span>
                                <p class="font-medium text-[var(--color-text)]">{{ $listing->user->name }}</p>
                                <p class="text-sm text-[var(--color-text-light)]">
                                    Listed {{ $listing->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-12 text-center">
                <a href="{{ route('hay.index') }}" class="btn-outline inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Hay Listings
                </a>
            </div>
        </div>
    </article>

    <x-listing-tracking :listing="$listing" listingType="hay" />
</x-layouts.public>
