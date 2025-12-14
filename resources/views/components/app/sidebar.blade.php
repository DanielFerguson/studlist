<!-- Mobile Sidebar -->
<aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-64 sidebar lg:hidden">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-[var(--color-border)]">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="/sl-logo.svg" alt="StudList" class="h-8 w-8">
                <span class="font-heading font-bold text-xl text-[var(--color-primary)]">StudList</span>
            </a>
            <button @click="sidebarOpen = false" class="p-2 rounded-lg hover:bg-[var(--color-cream)]">
                <svg class="w-5 h-5 text-[var(--color-text-light)]" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        @include('components.app.sidebar-nav')
    </div>
</aside>

<!-- Desktop Sidebar -->
<aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 sidebar">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center h-16 px-4 border-b border-[var(--color-border)]">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="/sl-logo.svg" alt="StudList" class="h-8 w-8">
                <span class="font-heading font-bold text-xl text-[var(--color-primary)]">StudList</span>
            </a>
        </div>

        @include('components.app.sidebar-nav')
    </div>
</aside>


