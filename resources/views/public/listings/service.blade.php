<x-layouts.public :title="$listing->business_name">
    <article class="py-12 bg-[var(--color-warm-white)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center gap-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-[var(--color-text-light)] hover:text-teal-600">Home</a></li>
                    <li><span class="text-[var(--color-text-light)]">/</span></li>
                    <li><a href="{{ route('search', ['category' => ['services']]) }}" class="text-[var(--color-text-light)] hover:text-teal-600">Services</a></li>
                    <li><span class="text-[var(--color-text-light)]">/</span></li>
                    <li><span class="text-[var(--color-text)] font-medium">{{ $listing->business_name }}</span></li>
                </ol>
            </nav>

            <div class="grid lg:grid-cols-3 gap-10">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Header Card -->
                    <div class="card-rustic p-8 mb-8">
                        <div class="flex items-start gap-6">
                            <!-- Icon -->
                            <div class="w-20 h-20 rounded-2xl bg-teal-600 flex items-center justify-center flex-shrink-0">
                                @if($listing->type === 'Photographer')
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                @elseif($listing->type === 'Fitter & Feeder')
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                @else
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                @endif
                            </div>

                            <div class="flex-1">
                                <span class="inline-block px-3 py-1 bg-teal-600/10 text-teal-700 text-sm font-medium rounded-full mb-3">
                                    {{ $listing->type }}
                                </span>
                                <h1 class="font-heading text-3xl md:text-4xl font-bold text-[var(--color-text)] mb-2">
                                    {{ $listing->business_name }}
                                </h1>
                                @if($listing->contact_name)
                                    <p class="text-[var(--color-text-light)]">{{ $listing->contact_name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Locations Covered -->
                    @if($listing->locations_covered && count($listing->locations_covered) > 0)
                        <div class="card-rustic p-8 mb-8">
                            <h2 class="font-heading text-xl font-semibold text-[var(--color-text)] mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Service Areas
                            </h2>
                            <div class="flex flex-wrap gap-2">
                                @foreach($listing->locations_covered as $location)
                                    <span class="px-4 py-2 bg-[var(--color-cream)] rounded-full text-[var(--color-text)] font-medium">
                                        {{ $location }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    @if($listing->description)
                        <div class="card-rustic p-8 mb-8">
                            <h2 class="font-heading text-xl font-semibold text-[var(--color-text)] mb-4">About This Service</h2>
                            <div class="prose prose-brown max-w-none text-[var(--color-text-light)] leading-relaxed">
                                {!! nl2br(e($listing->description)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Links -->
                    @if($listing->links && (isset($listing->links['website']) || isset($listing->links['facebook'])))
                        <div class="card-rustic p-8">
                            <h2 class="font-heading text-xl font-semibold text-[var(--color-text)] mb-4">Find Us Online</h2>
                            <div class="flex flex-wrap gap-4">
                                @if(isset($listing->links['website']) && $listing->links['website'])
                                    <a href="{{ $listing->links['website'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--color-cream)] hover:bg-teal-600/10 rounded-lg transition-colors">
                                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                        </svg>
                                        <span class="font-medium text-[var(--color-text)]">Website</span>
                                    </a>
                                @endif
                                @if(isset($listing->links['facebook']) && $listing->links['facebook'])
                                    <a href="{{ $listing->links['facebook'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--color-cream)] hover:bg-teal-600/10 rounded-lg transition-colors">
                                        <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                        <span class="font-medium text-[var(--color-text)]">Facebook</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Contact Card -->
                    <div class="card-rustic p-6 sticky top-28">
                        <h3 class="font-heading text-lg font-semibold text-[var(--color-text)] mb-4">Contact</h3>

                        @if($listing->phone_contact)
                            <a href="tel:{{ $listing->phone_contact }}" class="flex items-center gap-3 p-4 rounded-lg bg-[var(--color-cream)] hover:bg-teal-600/10 transition-colors mb-3" data-track-contact="phone">
                                <div class="w-10 h-10 rounded-full bg-teal-600 flex items-center justify-center">
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
                            <a href="mailto:{{ $listing->email_contact }}" class="flex items-center gap-3 p-4 rounded-lg bg-[var(--color-cream)] hover:bg-teal-600/10 transition-colors mb-3" data-track-contact="email">
                                <div class="w-10 h-10 rounded-full bg-teal-600 flex items-center justify-center">
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

                        <!-- Share Button -->
                        <button id="share-listing" class="w-full flex items-center justify-center gap-2 p-4 rounded-lg border-2 border-[var(--color-border)] hover:border-teal-600 hover:bg-[var(--color-cream)] transition-colors mb-6">
                            <svg class="w-5 h-5 text-[var(--color-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                            <span class="font-medium text-[var(--color-text)]">Share Listing</span>
                        </button>

                        @if($listing->abn)
                            <div class="p-4 bg-[var(--color-cream)] rounded-lg mb-6">
                                <span class="text-xs text-[var(--color-text-light)]">ABN</span>
                                <p class="font-medium text-[var(--color-text)]">{{ $listing->abn }}</p>
                            </div>
                        @endif

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
                <a href="{{ route('search', ['category' => ['services']]) }}" class="btn-outline inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Services
                </a>
            </div>
        </div>
    </article>
    <x-listing-tracking :listing="$listing" listingType="service" />
</x-layouts.public>








