<x-layouts.app title="Dashboard">
    <div class="space-y-6" x-data="{ 
        deleteModal: { open: false, type: '', id: null, name: '' },
        openDeleteModal(type, id, name) {
            this.deleteModal = { open: true, type, id, name };
        },
        closeDeleteModal() {
            this.deleteModal = { open: false, type: '', id: null, name: '' };
        }
    }">
        <!-- Steers Section -->
        <div class="card-rustic">
            <div class="p-6 border-b border-[var(--color-border)]">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="font-heading text-xl font-semibold text-[var(--color-text)]">Steers</h2>
                        <p class="text-sm text-[var(--color-text-light)]">
                            Your steer listings. All listings are free to post!
                        </p>
                    </div>
                    <a href="{{ route('steers.create') }}" class="btn-primary btn-sm whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        List Steer
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-rustic">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($steerListings as $listing)
                        <tr>
                            <td class="font-medium">
                                <a href="{{ route('steers.edit', $listing) }}"
                                    class="text-[var(--color-primary)] hover:underline">
                                    {{ $listing->name }}
                                </a>
                            </td>
                            <td>
                                @if($listing->status === 'active')
                                <span class="badge badge-success">Active</span>
                                @elseif($listing->status === 'draft')
                                <span class="badge badge-secondary">Draft</span>
                                @else
                                <span class="badge badge-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                {{ $listing->price ? '$' . number_format($listing->price) : 'Not specified' }}
                            </td>
                            <td>
                                <div class="flex justify-end gap-2 flex-wrap">
                                    @if($listing->status === 'draft')
                                    <span class="text-sm text-[var(--color-text-light)]">Delete & recreate to activate</span>
                                    @elseif($listing->status === 'active')
                                    <form method="POST" action="{{ route('subscription.cancel', $listing) }}">
                                        @csrf
                                        <button type="submit" class="btn-outline btn-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Cancel
                                        </button>
                                    </form>
                                    @elseif($listing->status === 'cancelled')
                                    <span class="text-sm text-[var(--color-text-light)]">Delete & recreate to activate</span>
                                    @endif
                                    <a href="{{ route('steers.edit', $listing) }}" class="btn-outline btn-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </a>
                                    <button type="button"
                                        @click="openDeleteModal('steers', {{ $listing->id }}, '{{ $listing->name }}')"
                                        class="btn-danger btn-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-[var(--color-text-light)] py-8">
                                No steer listings found. <a href="{{ route('steers.create') }}"
                                    class="text-[var(--color-primary)] hover:underline">Create your first listing</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Studs Section -->
        <div class="card-rustic">
            <div class="p-6 border-b border-[var(--color-border)]">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="font-heading text-xl font-semibold text-[var(--color-text)]">Studs</h2>
                        <p class="text-sm text-[var(--color-text-light)]">
                            Your stud listings. All listings are free to post!
                        </p>
                    </div>
                    <a href="{{ route('studs.create') }}" class="btn-primary btn-sm whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        List Stud
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-rustic">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Tattoo</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studListings as $listing)
                        <tr>
                            <td class="font-medium">
                                <a href="{{ route('studs.edit', $listing) }}"
                                    class="text-[var(--color-primary)] hover:underline">
                                    {{ $listing->name }}
                                </a>
                            </td>
                            <td>
                                @if($listing->status === 'active')
                                <span class="badge badge-success">Active</span>
                                @elseif($listing->status === 'draft')
                                <span class="badge badge-secondary">Draft</span>
                                @else
                                <span class="badge badge-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>{{ $listing->tattoo_number ?? 'Not specified' }}</td>
                            <td>
                                <div class="flex justify-end gap-2 flex-wrap">
                                    @if($listing->status === 'draft')
                                    <span class="text-sm text-[var(--color-text-light)]">Delete & recreate to activate</span>
                                    @elseif($listing->status === 'active')
                                    <form method="POST" action="{{ route('subscription.cancel-stud', $listing) }}">
                                        @csrf
                                        <button type="submit" class="btn-outline btn-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Cancel
                                        </button>
                                    </form>
                                    @elseif($listing->status === 'cancelled')
                                    <span class="text-sm text-[var(--color-text-light)]">Delete & recreate to activate</span>
                                    @endif
                                    <a href="{{ route('studs.edit', $listing) }}" class="btn-outline btn-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </a>
                                    <button type="button"
                                        @click="openDeleteModal('studs', {{ $listing->id }}, '{{ $listing->name }}')"
                                        class="btn-danger btn-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-[var(--color-text-light)] py-8">
                                No stud listings found. <a href="{{ route('studs.create') }}"
                                    class="text-[var(--color-primary)] hover:underline">Create your first listing</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Genetics Section -->
        <div class="card-rustic">
            <div class="p-6 border-b border-[var(--color-border)]">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="font-heading text-xl font-semibold text-[var(--color-text)]">Genetics</h2>
                        <p class="text-sm text-[var(--color-text-light)]">
                            Your genetics listings. Genetics listings are free to post.
                        </p>
                    </div>
                    <a href="{{ route('genetics.create') }}" class="btn-primary btn-sm whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        List Genetics
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-rustic">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Location</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($geneticsListings as $listing)
                        <tr>
                            <td class="font-medium">
                                <a href="{{ route('genetics.edit', $listing) }}"
                                    class="text-[var(--color-primary)] hover:underline">
                                    {{ $listing->name }}
                                </a>
                            </td>
                            <td>{{ $listing->type }}</td>
                            <td>${{ number_format($listing->price) }}</td>
                            <td>{{ $listing->storage_location }}</td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('genetics.edit', $listing) }}" class="btn-outline btn-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </a>
                                    <button type="button"
                                        @click="openDeleteModal('genetics', {{ $listing->id }}, '{{ $listing->name }}')"
                                        class="btn-danger btn-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-[var(--color-text-light)] py-8">
                                No genetics listings found. <a href="{{ route('genetics.create') }}"
                                    class="text-[var(--color-primary)] hover:underline">Create your first listing</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Show Equipment Section -->
        <div class="card-rustic">
            <div class="p-6 border-b border-[var(--color-border)]">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="font-heading text-xl font-semibold text-[var(--color-text)]">Show Equipment</h2>
                        <p class="text-sm text-[var(--color-text-light)]">
                            Your show equipment listings. Equipment listings are free to post.
                        </p>
                    </div>
                    <a href="{{ route('show-equipment.create') }}" class="btn-primary btn-sm whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        List Equipment
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-rustic">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Condition</th>
                            <th>Location</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($showEquipmentListings as $listing)
                        <tr>
                            <td class="font-medium">
                                <a href="{{ route('show-equipment.edit', $listing) }}"
                                    class="text-[var(--color-primary)] hover:underline">
                                    {{ $listing->title }}
                                </a>
                            </td>
                            <td>{{ $listing->condition }}</td>
                            <td>{{ $listing->location }}</td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('show-equipment.edit', $listing) }}" class="btn-outline btn-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </a>
                                    <button type="button"
                                        @click="openDeleteModal('show-equipment', {{ $listing->id }}, '{{ $listing->title }}')"
                                        class="btn-danger btn-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-[var(--color-text-light)] py-8">
                                No equipment listings found. <a href="{{ route('show-equipment.create') }}"
                                    class="text-[var(--color-primary)] hover:underline">Create your first listing</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Services Section -->
        <div class="card-rustic">
            <div class="p-6 border-b border-[var(--color-border)]">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="font-heading text-xl font-semibold text-[var(--color-text)]">Services</h2>
                        <p class="text-sm text-[var(--color-text-light)]">
                            Your service listings. Service listings are free to post.
                        </p>
                    </div>
                    <a href="{{ route('services.create') }}" class="btn-primary btn-sm whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        List Service
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-rustic">
                    <thead>
                        <tr>
                            <th>Business Name</th>
                            <th>Type</th>
                            <th>Locations</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($serviceListings as $listing)
                        <tr>
                            <td class="font-medium">
                                <a href="{{ route('services.edit', $listing) }}"
                                    class="text-[var(--color-primary)] hover:underline">
                                    {{ $listing->business_name }}
                                </a>
                            </td>
                            <td>{{ $listing->type }}</td>
                            <td>{{ is_array($listing->locations_covered) ? implode(', ', $listing->locations_covered) :
                                $listing->locations_covered }}</td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('services.edit', $listing) }}" class="btn-outline btn-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </a>
                                    <button type="button"
                                        @click="openDeleteModal('services', {{ $listing->id }}, '{{ $listing->business_name }}')"
                                        class="btn-danger btn-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-[var(--color-text-light)] py-8">
                                No service listings found. <a href="{{ route('services.create') }}"
                                    class="text-[var(--color-primary)] hover:underline">Create your first listing</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="deleteModal.open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center modal-overlay"
            @click.self="closeDeleteModal()">
            <div x-show="deleteModal.open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
                <div class="p-6">
                    <h3 class="font-heading text-xl font-semibold text-[var(--color-text)] mb-2">
                        Are you absolutely sure?
                    </h3>
                    <p class="text-[var(--color-text-light)]">
                        This action cannot be undone. This will permanently delete
                        <strong x-text="deleteModal.name"></strong>
                        and remove it from our servers. Any associated subscriptions will also be cancelled.
                    </p>
                </div>
                <div
                    class="flex justify-end gap-3 px-6 py-4 bg-[var(--color-cream)] border-t border-[var(--color-border)]">
                    <button type="button" @click="closeDeleteModal()" class="btn-outline btn-sm">
                        Cancel
                    </button>
                    <form :action="'/' + deleteModal.type + '/' + deleteModal.id" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>




