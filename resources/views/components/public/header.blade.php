<header class="bg-white/95 backdrop-blur-sm border-b border-[var(--color-border)] sticky top-0 z-50">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <img src="/sl-logo.svg" alt="StudList" class="h-10 w-10">
                    <span class="font-heading text-2xl font-bold text-[var(--color-text)]">StudList</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('search') }}"
                    class="text-[var(--color-text-light)] hover:text-[var(--color-primary)] font-medium transition-colors">
                    Browse Listings
                </a>
                <a href="{{ route('search', ['category' => ['steers']]) }}"
                    class="text-[var(--color-text-light)] hover:text-[var(--color-primary)] font-medium transition-colors">
                    Steers
                </a>
                <a href="{{ route('search', ['category' => ['studs']]) }}"
                    class="text-[var(--color-text-light)] hover:text-[var(--color-primary)] font-medium transition-colors">
                    Studs
                </a>
                <a href="{{ route('search', ['category' => ['genetics']]) }}"
                    class="text-[var(--color-text-light)] hover:text-[var(--color-primary)] font-medium transition-colors">
                    Genetics
                </a>
                <a href="{{ route('hay.index') }}"
                    class="text-[var(--color-text-light)] hover:text-[var(--color-primary)] font-medium transition-colors">
                    Hay
                </a>
                <a href="{{ route('search', ['category' => ['equipment']]) }}"
                    class="text-[var(--color-text-light)] hover:text-[var(--color-primary)] font-medium transition-colors">
                    Equipment
                </a>
                <a href="{{ route('search', ['category' => ['services']]) }}"
                    class="text-[var(--color-text-light)] hover:text-[var(--color-primary)] font-medium transition-colors">
                    Services
                </a>
                <a href="{{ route('about') }}"
                    class="text-[var(--color-text-light)] hover:text-[var(--color-primary)] font-medium transition-colors">
                    About
                </a>
            </div>

            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center gap-4">
                @auth
                <a href="{{ route('dashboard') }}" class="btn-outline text-sm">
                    Dashboard
                </a>
                @else
                <a href="{{ route('login') }}"
                    class="text-[var(--color-text-light)] hover:text-[var(--color-primary)] font-medium transition-colors">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="btn-primary text-sm">
                    Start Listing
                </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button type="button" onclick="toggleMobileMenu()"
                    class="text-[var(--color-text)] hover:text-[var(--color-primary)] p-2">
                    <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <div class="flex flex-col gap-2 pt-4 border-t border-[var(--color-border)]">
                <a href="{{ route('search') }}"
                    class="px-4 py-2 text-[var(--color-text-light)] hover:bg-[var(--color-cream)] rounded-lg transition-colors">
                    Browse Listings
                </a>
                <a href="{{ route('search', ['category' => ['steers']]) }}"
                    class="px-4 py-2 text-[var(--color-text-light)] hover:bg-[var(--color-cream)] rounded-lg transition-colors">
                    Steers
                </a>
                <a href="{{ route('search', ['category' => ['studs']]) }}"
                    class="px-4 py-2 text-[var(--color-text-light)] hover:bg-[var(--color-cream)] rounded-lg transition-colors">
                    Studs
                </a>
                <a href="{{ route('search', ['category' => ['genetics']]) }}"
                    class="px-4 py-2 text-[var(--color-text-light)] hover:bg-[var(--color-cream)] rounded-lg transition-colors">
                    Genetics
                </a>
                <a href="{{ route('hay.index') }}"
                    class="px-4 py-2 text-[var(--color-text-light)] hover:bg-[var(--color-cream)] rounded-lg transition-colors">
                    Hay
                </a>
                <a href="{{ route('search', ['category' => ['equipment']]) }}"
                    class="px-4 py-2 text-[var(--color-text-light)] hover:bg-[var(--color-cream)] rounded-lg transition-colors">
                    Equipment
                </a>
                <a href="{{ route('search', ['category' => ['services']]) }}"
                    class="px-4 py-2 text-[var(--color-text-light)] hover:bg-[var(--color-cream)] rounded-lg transition-colors">
                    Services
                </a>
                <a href="{{ route('about') }}"
                    class="px-4 py-2 text-[var(--color-text-light)] hover:bg-[var(--color-cream)] rounded-lg transition-colors">
                    About
                </a>
                <hr class="border-[var(--color-border)] my-2">
                @auth
                <a href="{{ route('dashboard') }}"
                    class="px-4 py-2 text-[var(--color-primary)] font-medium hover:bg-[var(--color-cream)] rounded-lg transition-colors">
                    Dashboard
                </a>
                @else
                <a href="{{ route('login') }}"
                    class="px-4 py-2 text-[var(--color-text-light)] hover:bg-[var(--color-cream)] rounded-lg transition-colors">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="mx-4 btn-primary text-center text-sm">
                    Start Listing
                </a>
                @endauth
            </div>
        </div>
    </nav>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');
            
            menu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }
    </script>
</header>