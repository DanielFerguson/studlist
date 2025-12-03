<x-layouts.auth title="Log in to your account" heading="Welcome back" description="Enter your email and password to access your account">
    @if(session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
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

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="form-label">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-link">
                        Forgot password?
                    </a>
                @endif
            </div>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="form-input @error('password') border-red-500 @enderror" 
                placeholder="Password"
                required
                autocomplete="current-password"
            >
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-3">
            <input 
                type="checkbox" 
                id="remember" 
                name="remember" 
                class="form-checkbox"
                {{ old('remember') ? 'checked' : '' }}
            >
            <label for="remember" class="text-sm text-[var(--color-text)]">Remember me</label>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary">
            Log in
        </button>

        <!-- Register Link -->
        <p class="text-center text-sm text-[var(--color-text-light)]">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-link">Sign up</a>
        </p>
    </form>
</x-layouts.auth>








