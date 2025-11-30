<x-layouts.auth title="Create an account" heading="Get started" description="Create your free account to start listing">
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="form-label">Full name</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name') }}"
                class="form-input @error('name') border-red-500 @enderror" 
                placeholder="John Smith"
                required 
                autofocus
                autocomplete="name"
            >
            @error('name')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

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
                autocomplete="email"
            >
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="form-label">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="form-input @error('password') border-red-500 @enderror" 
                placeholder="Create a password"
                required
                autocomplete="new-password"
            >
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="form-label">Confirm password</label>
            <input 
                type="password" 
                id="password_confirmation" 
                name="password_confirmation" 
                class="form-input" 
                placeholder="Confirm your password"
                required
                autocomplete="new-password"
            >
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary">
            Create account
        </button>

        <!-- Login Link -->
        <p class="text-center text-sm text-[var(--color-text-light)]">
            Already have an account?
            <a href="{{ route('login') }}" class="text-link">Log in</a>
        </p>
    </form>
</x-layouts.auth>


