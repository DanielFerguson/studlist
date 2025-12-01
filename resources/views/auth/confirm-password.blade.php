<x-layouts.auth title="Confirm password" heading="Confirm your password" description="This is a secure area. Please confirm your password before continuing.">
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="form-label">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="form-input @error('password') border-red-500 @enderror" 
                placeholder="Enter your password"
                required
                autofocus
                autocomplete="current-password"
            >
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary">
            Confirm
        </button>
    </form>
</x-layouts.auth>






