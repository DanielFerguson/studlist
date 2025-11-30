<x-layouts.public title="Australia's Premier Cattle Marketplace">
    <!-- Hero Section -->
    <section class="relative min-h-[85vh] flex items-center">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=1920&q=80"
                alt="Australian cattle farm at sunset" class="w-full h-full object-cover">
            <div class="hero-overlay absolute inset-0"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="max-w-3xl">
                <span
                    class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm text-white text-sm font-medium rounded-full mb-6">
                    Australia's Trusted Cattle Marketplace
                </span>
                <h1 class="font-heading text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight mb-6">
                    Where Quality<br>
                    <span class="text-[var(--color-cream)]">Meets Trust</span>
                </h1>
                <p class="text-xl text-white/90 leading-relaxed mb-10 max-w-2xl">
                    Connect with buyers and sellers across Australia. List your steers, studs, genetics, equipment and
                    services with the nation's most trusted cattle trading platform.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('search') }}"
                        class="btn-primary text-lg px-8 py-4 flex items-center justify-center">
                        Browse Listings
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-white/10 backdrop-blur-sm border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-[var(--color-primary)] transition-all text-lg px-8 py-4">
                        Start Selling
                    </a>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 animate-bounce">
            <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3">
                </path>
            </svg>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="relative -mt-20 z-20 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div
            class="bg-white rounded-2xl shadow-2xl border border-[var(--color-border)] grid grid-cols-2 md:grid-cols-4 divide-x divide-[var(--color-border)]">
            <div class="p-6 md:p-8 text-center">
                <div class="font-heading text-3xl md:text-4xl font-bold text-[var(--color-primary)]">
                    {{ $steerListings->count() + $studListings->count() }}+
                </div>
                <div class="text-sm text-[var(--color-text-light)] mt-1">Active Listings</div>
            </div>
            <div class="p-6 md:p-8 text-center">
                <div class="font-heading text-3xl md:text-4xl font-bold text-[var(--color-secondary)]">
                    100%
                </div>
                <div class="text-sm text-[var(--color-text-light)] mt-1">Australian</div>
            </div>
            <div class="p-6 md:p-8 text-center">
                <div class="font-heading text-3xl md:text-4xl font-bold text-[var(--color-accent)]">
                    5+
                </div>
                <div class="text-sm text-[var(--color-text-light)] mt-1">Categories</div>
            </div>
            <div class="p-6 md:p-8 text-center">
                <div class="font-heading text-3xl md:text-4xl font-bold text-[var(--color-primary-light)]">
                    $15
                </div>
                <div class="text-sm text-[var(--color-text-light)] mt-1">Per Month</div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-24 bg-[var(--color-warm-white)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-1.5 bg-[var(--color-primary)]/10 text-[var(--color-primary)] text-sm font-medium rounded-full mb-4">
                    Browse Categories
                </span>
                <h2 class="font-heading text-4xl md:text-5xl font-bold text-[var(--color-text)]">
                    Find What You Need
                </h2>
                <p class="text-[var(--color-text-light)] mt-4 text-lg max-w-2xl mx-auto">
                    From premium steers to quality genetics, we've got everything for your cattle operation.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <!-- Steers -->
                <a href="{{ route('search', ['category' => ['steers']]) }}" class="group">
                    <div class="card-rustic aspect-square flex flex-col items-center justify-center p-6 text-center">
                        <div
                            class="w-16 h-16 rounded-full bg-[var(--color-primary)]/10 flex items-center justify-center mb-4 group-hover:bg-[var(--color-primary)] transition-all">
                            <svg class="w-8 h-8 text-[var(--color-primary)] group-hover:text-white transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <h3
                            class="font-heading font-semibold text-[var(--color-text)] group-hover:text-[var(--color-primary)] transition-colors">
                            Steers</h3>
                        <p class="text-xs text-[var(--color-text-light)] mt-1">{{ $steerListings->count() }} listings
                        </p>
                    </div>
                </a>

                <!-- Studs -->
                <a href="{{ route('search', ['category' => ['studs']]) }}" class="group">
                    <div class="card-rustic aspect-square flex flex-col items-center justify-center p-6 text-center">
                        <div
                            class="w-16 h-16 rounded-full bg-[var(--color-secondary)]/10 flex items-center justify-center mb-4 group-hover:bg-[var(--color-secondary)] transition-all">
                            <svg class="w-8 h-8 text-[var(--color-secondary)] group-hover:text-white transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                </path>
                            </svg>
                        </div>
                        <h3
                            class="font-heading font-semibold text-[var(--color-text)] group-hover:text-[var(--color-secondary)] transition-colors">
                            Studs</h3>
                        <p class="text-xs text-[var(--color-text-light)] mt-1">{{ $studListings->count() }} listings</p>
                    </div>
                </a>

                <!-- Genetics -->
                <a href="{{ route('search', ['category' => ['genetics']]) }}" class="group">
                    <div class="card-rustic aspect-square flex flex-col items-center justify-center p-6 text-center">
                        <div
                            class="w-16 h-16 rounded-full bg-[var(--color-accent)]/10 flex items-center justify-center mb-4 group-hover:bg-[var(--color-accent)] transition-all">
                            <svg class="w-8 h-8 text-[var(--color-accent)] group-hover:text-white transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                        </div>
                        <h3
                            class="font-heading font-semibold text-[var(--color-text)] group-hover:text-[var(--color-accent)] transition-colors">
                            Genetics</h3>
                        <p class="text-xs text-[var(--color-text-light)] mt-1">{{ $geneticsListings->count() }} listings
                        </p>
                    </div>
                </a>

                <!-- Equipment -->
                <a href="{{ route('search', ['category' => ['equipment']]) }}" class="group">
                    <div class="card-rustic aspect-square flex flex-col items-center justify-center p-6 text-center">
                        <div
                            class="w-16 h-16 rounded-full bg-amber-600/10 flex items-center justify-center mb-4 group-hover:bg-amber-600 transition-all">
                            <svg class="w-8 h-8 text-amber-600 group-hover:text-white transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3
                            class="font-heading font-semibold text-[var(--color-text)] group-hover:text-amber-600 transition-colors">
                            Equipment</h3>
                        <p class="text-xs text-[var(--color-text-light)] mt-1">{{ $showEquipmentListings->count() }}
                            listings</p>
                    </div>
                </a>

                <!-- Services -->
                <a href="{{ route('search', ['category' => ['services']]) }}" class="group">
                    <div class="card-rustic aspect-square flex flex-col items-center justify-center p-6 text-center">
                        <div
                            class="w-16 h-16 rounded-full bg-teal-600/10 flex items-center justify-center mb-4 group-hover:bg-teal-600 transition-all">
                            <svg class="w-8 h-8 text-teal-600 group-hover:text-white transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3
                            class="font-heading font-semibold text-[var(--color-text)] group-hover:text-teal-600 transition-colors">
                            Services</h3>
                        <p class="text-xs text-[var(--color-text-light)] mt-1">{{ $serviceListings->count() }} listings
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Steers Section -->
    @if($steerListings->count() > 0)
    <section class="py-24 bg-[var(--color-cream)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
                <div>
                    <span
                        class="inline-block px-4 py-1.5 bg-[var(--color-primary)]/10 text-[var(--color-primary)] text-sm font-medium rounded-full mb-4">
                        Featured Steers
                    </span>
                    <h2 class="font-heading text-4xl font-bold text-[var(--color-text)]">
                        Premium Cattle Ready
                    </h2>
                </div>
                <a href="{{ route('search', ['category' => ['steers']]) }}"
                    class="text-[var(--color-primary)] font-medium hover:underline flex items-center gap-2">
                    View All Steers
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($steerListings->take(3) as $listing)
                @include('components.public.listing-card', ['listing' => $listing, 'type' => 'steer'])
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Studs Section -->
    @if($studListings->count() > 0)
    <section class="py-24 bg-[var(--color-warm-white)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
                <div>
                    <span
                        class="inline-block px-4 py-1.5 bg-[var(--color-secondary)]/10 text-[var(--color-secondary)] text-sm font-medium rounded-full mb-4">
                        Featured Studs
                    </span>
                    <h2 class="font-heading text-4xl font-bold text-[var(--color-text)]">
                        Elite Breeding Bulls
                    </h2>
                </div>
                <a href="{{ route('search', ['category' => ['studs']]) }}"
                    class="text-[var(--color-secondary)] font-medium hover:underline flex items-center gap-2">
                    View All Studs
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($studListings->take(3) as $listing)
                @include('components.public.listing-card', ['listing' => $listing, 'type' => 'stud'])
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- How It Works Section -->
    <section class="py-24 bg-[var(--color-text)] relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0"
                style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
            </div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-1.5 bg-[var(--color-accent)]/20 text-[var(--color-cream)] text-sm font-medium rounded-full mb-4">
                    Simple Process
                </span>
                <h2 class="font-heading text-4xl md:text-5xl font-bold text-white">
                    How It Works
                </h2>
                <p class="text-white/70 mt-4 text-lg max-w-2xl mx-auto">
                    Getting started is easy. List your cattle in minutes and reach buyers across Australia.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div
                        class="w-20 h-20 rounded-2xl bg-gradient-to-br from-[var(--color-accent)] to-[var(--color-primary)] flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <span class="font-heading text-3xl font-bold text-white">1</span>
                    </div>
                    <h3 class="font-heading text-xl font-semibold text-white mb-3">Create Account</h3>
                    <p class="text-white/60">Sign up in seconds. No long forms or complicated verification.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div
                        class="w-20 h-20 rounded-2xl bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-secondary)] flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <span class="font-heading text-3xl font-bold text-white">2</span>
                    </div>
                    <h3 class="font-heading text-xl font-semibold text-white mb-3">Add Your Listing</h3>
                    <p class="text-white/60">Upload photos and details. Our form makes it easy to showcase your cattle.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div
                        class="w-20 h-20 rounded-2xl bg-gradient-to-br from-[var(--color-secondary)] to-[var(--color-secondary-light)] flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <span class="font-heading text-3xl font-bold text-white">3</span>
                    </div>
                    <h3 class="font-heading text-xl font-semibold text-white mb-3">Connect with Buyers</h3>
                    <p class="text-white/60">Your listing goes live instantly. Interested buyers contact you directly.
                    </p>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-3 btn-primary text-lg px-10 py-5">
                    Get Started for $15/month
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section
        class="py-24 bg-gradient-to-br from-[var(--color-primary)] via-[var(--color-primary-light)] to-[var(--color-secondary)] relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1570042225831-d98fa7577f1e?w=1920&q=80" alt="Cattle farm"
                class="w-full h-full object-cover mix-blend-overlay opacity-30">
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-heading text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to List Your Cattle?
            </h2>
            <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto">
                Join hundreds of Australian farmers and breeders. Your next sale is just a listing away.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}"
                    class="bg-white text-[var(--color-primary)] font-semibold px-8 py-4 rounded-lg hover:bg-[var(--color-cream)] transition-colors shadow-lg">
                    Create Free Account
                </a>
                <a href="{{ route('about') }}"
                    class="border-2 border-white text-white font-semibold px-8 py-4 rounded-lg hover:bg-white/10 transition-colors">
                    Learn More
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>