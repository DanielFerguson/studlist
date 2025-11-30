<x-layouts.public :title="$listing->name">
    <article class="py-12 bg-[var(--color-warm-white)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center gap-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-[var(--color-text-light)] hover:text-[var(--color-accent)]">Home</a></li>
                    <li><span class="text-[var(--color-text-light)]">/</span></li>
                    <li><a href="{{ route('search', ['category' => ['genetics']]) }}" class="text-[var(--color-text-light)] hover:text-[var(--color-accent)]">Genetics</a></li>
                    <li><span class="text-[var(--color-text-light)]">/</span></li>
                    <li><span class="text-[var(--color-text)] font-medium">{{ $listing->name }}</span></li>
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
                                    alt="{{ $listing->name }}"
                                    class="w-full h-full object-cover"
                                    id="main-image"
                                >
                                <span class="absolute top-4 left-4 bg-[var(--color-accent)] text-white text-sm font-semibold px-4 py-1.5 rounded-full">
                                    Genetics
                                </span>
                            </div>
                            @if(count($listing->photos) > 1)
                                <div class="p-4 flex gap-3 overflow-x-auto">
                                    @foreach($listing->photos as $index => $photo)
                                        <button 
                                            onclick="document.getElementById('main-image').src='{{ Storage::url($photo) }}'"
                                            class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden border-2 border-[var(--color-border)] hover:border-[var(--color-accent)] transition-colors"
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
                            {{ $listing->name }}
                        </h1>
                        
                        <div class="flex flex-wrap items-center gap-4 text-[var(--color-text-light)]">
                            @if($listing->type)
                                <span class="inline-flex items-center gap-1.5 bg-[var(--color-accent)]/10 text-[var(--color-accent)] px-3 py-1.5 rounded-full text-sm font-medium">
                                    {{ $listing->type }}
                                </span>
                            @endif
                            @if($listing->breed)
                                <span class="inline-flex items-center gap-1.5 bg-[var(--color-cream)] px-3 py-1.5 rounded-full text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    {{ $listing->breed }}
                                </span>
                            @endif
                            @if($listing->quantity)
                                <span class="inline-flex items-center gap-1.5 bg-[var(--color-cream)] px-3 py-1.5 rounded-full text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                    {{ $listing->quantity }} available
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="card-rustic p-8 mb-8">
                        <h2 class="font-heading text-xl font-semibold text-[var(--color-text)] mb-6">Details</h2>
                        
                        <div class="grid sm:grid-cols-2 gap-6">
                            @if($listing->sire)
                                <div>
                                    <dt class="text-sm text-[var(--color-text-light)] mb-1">Sire</dt>
                                    <dd class="font-medium text-[var(--color-text)]">{{ $listing->sire }}</dd>
                                </div>
                            @endif
                            @if($listing->dam)
                                <div>
                                    <dt class="text-sm text-[var(--color-text-light)] mb-1">Dam</dt>
                                    <dd class="font-medium text-[var(--color-text)]">{{ $listing->dam }}</dd>
                                </div>
                            @endif
                            @if($listing->registration_number)
                                <div>
                                    <dt class="text-sm text-[var(--color-text-light)] mb-1">Registration Number</dt>
                                    <dd class="font-medium text-[var(--color-text)]">{{ $listing->registration_number }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>

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
                        @if($listing->price)
                            <div class="text-center pb-6 mb-6 border-b border-[var(--color-border)]">
                                <span class="text-sm text-[var(--color-text-light)]">Price</span>
                                <div class="font-heading text-4xl font-bold text-[var(--color-accent)]">
                                    ${{ number_format($listing->price) }}
                                </div>
                                @if($listing->quantity && $listing->quantity > 1)
                                    <span class="text-sm text-[var(--color-text-light)]">per unit</span>
                                @endif
                            </div>
                        @endif

                        <h3 class="font-heading text-lg font-semibold text-[var(--color-text)] mb-4">Contact Seller</h3>

                        @if($listing->contact_name)
                            <div class="mb-4">
                                <span class="text-sm text-[var(--color-text-light)]">Contact</span>
                                <p class="font-medium text-[var(--color-text)]">{{ $listing->contact_name }}</p>
                            </div>
                        @endif

                        @if($listing->contact_phone)
                            <a href="tel:{{ $listing->contact_phone }}" class="flex items-center gap-3 p-4 rounded-lg bg-[var(--color-cream)] hover:bg-[var(--color-accent)]/10 transition-colors mb-3">
                                <div class="w-10 h-10 rounded-full bg-[var(--color-accent)] flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-xs text-[var(--color-text-light)]">Phone</span>
                                    <p class="font-medium text-[var(--color-text)]">{{ $listing->contact_phone }}</p>
                                </div>
                            </a>
                        @endif

                        @if($listing->contact_email)
                            <a href="mailto:{{ $listing->contact_email }}" class="flex items-center gap-3 p-4 rounded-lg bg-[var(--color-cream)] hover:bg-[var(--color-accent)]/10 transition-colors mb-6">
                                <div class="w-10 h-10 rounded-full bg-[var(--color-accent)] flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-xs text-[var(--color-text-light)]">Email</span>
                                    <p class="font-medium text-[var(--color-text)] break-all">{{ $listing->contact_email }}</p>
                                </div>
                            </a>
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
                <a href="{{ route('search', ['category' => ['genetics']]) }}" class="btn-outline inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Genetics
                </a>
            </div>
        </div>
    </article>
</x-layouts.public>

