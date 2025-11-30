<x-layouts.auth title="Forgot password" heading="Reset your password" description="Enter your email address and we'll send you a link to reset your password">
    @if(session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="form-label">Email address</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email') }}"
                class="form-input @error('email') border-red-500 @enderror" 
                placeholder="email@example.com"
                required 
                autofocus
                autocomplete="email"
            >
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary">
            Send reset link
        </button>

        <!-- Back to Login -->
        <p class="text-center text-sm text-[var(--color-text-light)]">
            Remember your password?
            <a href="{{ route('login') }}" class="text-link">Log in</a>
        </p>
    </form>
</x-layouts.auth>


