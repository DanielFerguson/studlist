<x-layouts.auth title="Reset password" heading="Create new password" description="Enter your new password below">
    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div>
            <label for="email" class="form-label">Email address</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email', $request->email) }}"
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
            <label for="password" class="form-label">New password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="form-input @error('password') border-red-500 @enderror" 
                placeholder="Enter new password"
                required
                autocomplete="new-password"
            >
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="form-label">Confirm new password</label>
            <input 
                type="password" 
                id="password_confirmation" 
                name="password_confirmation" 
                class="form-input" 
                placeholder="Confirm new password"
                required
                autocomplete="new-password"
            >
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary">
            Reset password
        </button>
    </form>
</x-layouts.auth>








