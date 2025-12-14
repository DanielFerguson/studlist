@props([
    'title',
    'description' => null,
])

<div class="space-y-6">
    <div class="pb-4 border-b border-[var(--color-border)]">
        <h3 class="text-lg font-semibold text-[var(--color-text)]">{{ $title }}</h3>
        @if($description)
            <p class="text-sm text-[var(--color-text-light)]">{{ $description }}</p>
        @endif
    </div>
    {{ $slot }}
</div>









