<header class="sticky top-0 z-30 bg-white border-b border-[var(--color-border)]">
    <div class="flex items-center justify-between h-16 px-4 lg:px-6">
        <!-- Mobile Menu Button -->
        <button 
            @click="sidebarOpen = true" 
            class="p-2 rounded-lg hover:bg-[var(--color-cream)] lg:hidden"
        >
            <svg class="w-6 h-6 text-[var(--color-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Page Title / Breadcrumb -->
        <div class="hidden lg:block">
            @if(isset($header))
                {{ $header }}
            @else
                <h1 class="font-heading text-xl font-semibold text-[var(--color-text)]">
                    {{ $title ?? 'Dashboard' }}
                </h1>
            @endif
        </div>

        <!-- Right Side -->
        <div class="flex items-center gap-4">
            <!-- Browse Listings Link -->
            <a href="{{ route('search') }}" class="hidden sm:flex items-center gap-2 text-sm text-[var(--color-text-light)] hover:text-[var(--color-primary)]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Browse Listings
            </a>

            <!-- User Menu (Desktop) -->
            <div class="hidden lg:block relative" x-data="{ open: false }">
                <button 
                    @click="open = !open" 
                    class="flex items-center gap-2 p-2 rounded-lg hover:bg-[var(--color-cream)]"
                >
                    <div class="w-8 h-8 rounded-full bg-[var(--color-primary)] flex items-center justify-center text-white text-sm font-semibold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <svg class="w-4 h-4 text-[var(--color-text-light)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown -->
                <div 
                    x-show="open" 
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-[var(--color-border)] py-1"
                >
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-[var(--color-text)] hover:bg-[var(--color-cream)]">
                        Profile
                    </a>
                    <a href="{{ route('password.edit') }}" class="block px-4 py-2 text-sm text-[var(--color-text)] hover:bg-[var(--color-cream)]">
                        Password
                    </a>
                    <a href="{{ route('subscription.billing-portal') }}" class="block px-4 py-2 text-sm text-[var(--color-text)] hover:bg-[var(--color-cream)]">
                        Billing
                    </a>
                    <hr class="my-1 border-[var(--color-border)]">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 5000)"
            class="mx-4 mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between"
        >
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="text-green-700 hover:text-green-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div 
            x-data="{ show: true }" 
            x-show="show"
            class="mx-4 mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center justify-between"
        >
            <span>{{ session('error') }}</span>
            <button @click="show = false" class="text-red-700 hover:text-red-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif
</header>













