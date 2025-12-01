<x-layouts.app title="Password Settings">
    <div class="max-w-2xl mx-auto">
        <div class="card-rustic">
            <div class="p-6 border-b border-[var(--color-border)]">
                <h2 class="font-heading text-xl font-semibold text-[var(--color-text)]">Update Password</h2>
                <p class="text-sm text-[var(--color-text-light)] mt-1">Ensure your account is using a long, random password to stay secure.</p>
            </div>
            
            <form method="POST" action="{{ route('password.update') }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <x-form.input 
                    name="current_password" 
                    label="Current Password" 
                    type="password"
                    placeholder="Enter your current password"
                    required
                />

                <x-form.input 
                    name="password" 
                    label="New Password" 
                    type="password"
                    placeholder="Enter your new password"
                    required
                />

                <x-form.input 
                    name="password_confirmation" 
                    label="Confirm New Password" 
                    type="password"
                    placeholder="Confirm your new password"
                    required
                />

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">Update Password</button>
                </div>

                @if(session('status') === 'password-updated')
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-700">Password updated successfully.</p>
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-layouts.app>






