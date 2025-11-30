@props(['listing', 'type'])

@php
    $routeName = match($type) {
        'steer' => 'steers.show',
        'stud' => 'studs.show',
        'genetics' => 'genetics.show',
        'equipment' => 'equipment.show',
        'service' => 'services.show',
        default => 'steers.show'
    };

    $typeLabel = match($type) {
        'steer' => 'Steer',
        'stud' => 'Stud',
        'genetics' => 'Genetics',
        'equipment' => 'Equipment',
        'service' => 'Service',
        default => 'Listing'
    };

    $typeColor = match($type) {
        'steer' => 'bg-[var(--color-primary)]',
        'stud' => 'bg-[var(--color-secondary)]',
        'genetics' => 'bg-[var(--color-accent)]',
        'equipment' => 'bg-amber-600',
        'service' => 'bg-teal-600',
        default => 'bg-gray-600'
    };

    $image = $listing->photos[0] ?? null;
    $title = $listing->name ?? $listing->business_name ?? 'Listing';
    $location = $listing->location ?? ($listing->locations_covered ? implode(', ', $listing->locations_covered) : null);
    $price = $listing->price ?? null;
@endphp

<a href="{{ route($routeName, $listing) }}" class="card-rustic group block">
    <!-- Image Container -->
    <div class="relative aspect-[4/3] overflow-hidden bg-[var(--color-cream)]">
        @if($image)
            <img 
                src="{{ Storage::url($image) }}" 
                alt="{{ $title }}"
                class="w-full h-full object-cover"
            >
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-16 h-16 text-[var(--color-border)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        @endif

        <!-- Type Badge -->
        <div class="absolute top-3 left-3">
            <span class="{{ $typeColor }} text-white text-xs font-semibold px-3 py-1 rounded-full shadow-md">
                {{ $typeLabel }}
            </span>
        </div>
    </div>

    <!-- Content -->
    <div class="p-5">
        <h3 class="font-heading text-lg font-semibold text-[var(--color-text)] group-hover:text-[var(--color-primary)] transition-colors line-clamp-1">
            {{ $title }}
        </h3>

        @if($listing->breed ?? false)
            <p class="text-sm text-[var(--color-text-light)] mt-1">
                {{ $listing->breed }}
            </p>
        @endif

        <div class="flex items-center justify-between mt-4">
            @if($location)
                <div class="flex items-center gap-1.5 text-sm text-[var(--color-text-light)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="truncate max-w-[120px]">{{ $location }}</span>
                </div>
            @endif

            @if($price)
                <span class="font-heading font-bold text-[var(--color-primary)]">
                    ${{ number_format($price) }}
                </span>
            @elseif($type === 'service')
                <span class="text-sm text-[var(--color-secondary)] font-medium">
                    Contact for pricing
                </span>
            @endif
        </div>
    </div>
</a>

