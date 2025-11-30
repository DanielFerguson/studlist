<x-layouts.public title="About Us">
    <!-- Hero Section -->
    <section class="relative py-24 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img 
                src="https://images.unsplash.com/photo-1516467508483-a7212febe31a?w=1920&q=80" 
                alt="Australian cattle farm"
                class="w-full h-full object-cover"
            >
            <div class="hero-overlay absolute inset-0"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-heading text-5xl md:text-6xl font-bold text-white mb-6">
                About StudList
            </h1>
            <p class="text-xl text-white/90 leading-relaxed max-w-2xl mx-auto">
                Connecting Australian cattle farmers and breeders with quality buyers since 2024.
            </p>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="py-20 bg-[var(--color-warm-white)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="inline-block px-4 py-1.5 bg-[var(--color-primary)]/10 text-[var(--color-primary)] text-sm font-medium rounded-full mb-4">
                        Our Mission
                    </span>
                    <h2 class="font-heading text-4xl font-bold text-[var(--color-text)] mb-6">
                        Building Australia's Premier Cattle Marketplace
                    </h2>
                    <p class="text-[var(--color-text-light)] text-lg leading-relaxed mb-6">
                        StudList was born from a simple idea: make it easier for Australian farmers and breeders to connect. We understand the challenges of buying and selling cattle — the time spent searching, the difficulty in reaching the right buyers, and the complexity of showcasing your animals properly.
                    </p>
                    <p class="text-[var(--color-text-light)] text-lg leading-relaxed">
                        Our platform brings together sellers of premium steers, studs, genetics, equipment, and services with buyers who appreciate quality. We're proud to serve the Australian cattle industry with a modern, easy-to-use marketplace.
                    </p>
                </div>

                <div class="relative">
                    <div class="card-rustic overflow-hidden">
                        <img 
                            src="https://images.unsplash.com/photo-1527153857715-3908f2bae5e8?w=800&q=80" 
                            alt="Cattle grazing"
                            class="w-full aspect-[4/3] object-cover"
                        >
                    </div>
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-[var(--color-primary)] rounded-2xl flex items-center justify-center shadow-xl">
                        <div class="text-center text-white">
                            <div class="font-heading text-3xl font-bold">100%</div>
                            <div class="text-sm">Australian</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-20 bg-[var(--color-cream)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-[var(--color-secondary)]/10 text-[var(--color-secondary)] text-sm font-medium rounded-full mb-4">
                    Our Values
                </span>
                <h2 class="font-heading text-4xl font-bold text-[var(--color-text)]">
                    What We Stand For
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Value 1 -->
                <div class="card-rustic p-8 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-primary-light)] flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-semibold text-[var(--color-text)] mb-3">Trust & Quality</h3>
                    <p class="text-[var(--color-text-light)]">
                        We believe in connecting genuine sellers with serious buyers. Quality listings from trusted sources.
                    </p>
                </div>

                <!-- Value 2 -->
                <div class="card-rustic p-8 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[var(--color-secondary)] to-[var(--color-secondary-light)] flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-semibold text-[var(--color-text)] mb-3">Simplicity</h3>
                    <p class="text-[var(--color-text-light)]">
                        Easy to use, no complicated processes. List your cattle in minutes and reach buyers instantly.
                    </p>
                </div>

                <!-- Value 3 -->
                <div class="card-rustic p-8 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[var(--color-accent)] to-[var(--color-primary-light)] flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-semibold text-[var(--color-text)] mb-3">Australian Focused</h3>
                    <p class="text-[var(--color-text-light)]">
                        Built for Australian farmers, by Australians. We understand the local industry and its needs.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How We Work Section -->
    <section class="py-20 bg-[var(--color-warm-white)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-[var(--color-accent)]/10 text-[var(--color-accent)] text-sm font-medium rounded-full mb-4">
                    Pricing
                </span>
                <h2 class="font-heading text-4xl font-bold text-[var(--color-text)]">
                    Simple, Fair Pricing
                </h2>
                <p class="text-[var(--color-text-light)] mt-4 max-w-2xl mx-auto">
                    We keep our pricing straightforward so you can focus on what matters — your cattle.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Paid Listings -->
                <div class="card-rustic p-8">
                    <div class="text-center mb-6">
                        <h3 class="font-heading text-2xl font-bold text-[var(--color-text)] mb-2">Steers & Studs</h3>
                        <div class="font-heading text-4xl font-bold text-[var(--color-primary)]">
                            $15<span class="text-lg text-[var(--color-text-light)] font-normal">/month</span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[var(--color-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-[var(--color-text-light)]">Full listing with photos</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[var(--color-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-[var(--color-text-light)]">Featured on homepage</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[var(--color-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-[var(--color-text-light)]">Direct contact details</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[var(--color-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-[var(--color-text-light)]">Cancel anytime</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-primary block text-center w-full">
                        Start Listing
                    </a>
                </div>

                <!-- Free Listings -->
                <div class="card-rustic p-8 border-2 border-[var(--color-secondary)]">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="bg-[var(--color-secondary)] text-white text-sm font-medium px-4 py-1 rounded-full">
                            Free Forever
                        </span>
                    </div>
                    <div class="text-center mb-6 pt-4">
                        <h3 class="font-heading text-2xl font-bold text-[var(--color-text)] mb-2">Genetics, Equipment & Services</h3>
                        <div class="font-heading text-4xl font-bold text-[var(--color-secondary)]">
                            $0
                        </div>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[var(--color-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-[var(--color-text-light)]">Full listing with photos</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[var(--color-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-[var(--color-text-light)]">Visible in search results</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[var(--color-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-[var(--color-text-light)]">Direct contact details</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[var(--color-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-[var(--color-text-light)]">No hidden fees</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-secondary block text-center w-full">
                        Create Free Account
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-secondary)] relative overflow-hidden">
        <div class="absolute inset-0">
            <img 
                src="https://images.unsplash.com/photo-1527153857715-3908f2bae5e8?w=1920&q=80" 
                alt="Cattle farm"
                class="w-full h-full object-cover mix-blend-overlay opacity-20"
            >
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-heading text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to Get Started?
            </h2>
            <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto">
                Join the growing community of Australian farmers and breeders on StudList.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}" class="bg-white text-[var(--color-primary)] font-semibold px-8 py-4 rounded-lg hover:bg-[var(--color-cream)] transition-colors shadow-lg">
                    Create Free Account
                </a>
                <a href="{{ route('search') }}" class="border-2 border-white text-white font-semibold px-8 py-4 rounded-lg hover:bg-white/10 transition-colors">
                    Browse Listings
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>

