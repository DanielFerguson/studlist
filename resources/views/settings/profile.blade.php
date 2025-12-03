<x-layouts.app title="Profile Settings">
    <div class="max-w-2xl mx-auto space-y-6">
        {{-- Profile Information --}}
        <div class="card-rustic">
            <div class="p-6 border-b border-[var(--color-border)]">
                <h2 class="font-heading text-xl font-semibold text-[var(--color-text)]">Profile Information</h2>
                <p class="text-sm text-[var(--color-text-light)] mt-1">Update your account's profile information and email address.</p>
            </div>
            
            <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-6">
                @csrf
                @method('PATCH')

                <x-form.input 
                    name="name" 
                    label="Name" 
                    :value="auth()->user()->name"
                    required
                    autofocus
                />

                <x-form.input 
                    name="email" 
                    label="Email" 
                    type="email"
                    :value="auth()->user()->email"
                    required
                />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            Your email address is unverified.
                            <form method="POST" action="{{ route('verification.send') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-[var(--color-primary)] hover:underline">
                                    Click here to re-send the verification email.
                                </button>
                            </form>
                        </p>
                    </div>
                @endif

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>

        {{-- Delete Account --}}
        <div class="card-rustic">
            <div class="p-6 border-b border-[var(--color-border)]">
                <h2 class="font-heading text-xl font-semibold text-red-600">Delete Account</h2>
                <p class="text-sm text-[var(--color-text-light)] mt-1">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            </div>
            
            <div class="p-6" x-data="{ showDeleteModal: false }">
                <button 
                    type="button" 
                    @click="showDeleteModal = true"
                    class="btn-danger"
                >
                    Delete Account
                </button>

                {{-- Delete Modal --}}
                <div 
                    x-show="showDeleteModal" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-50 flex items-center justify-center modal-overlay"
                    @click.self="showDeleteModal = false"
                >
                    <div 
                        x-show="showDeleteModal"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden"
                    >
                        <form method="POST" action="{{ route('profile.destroy') }}">
                            @csrf
                            @method('DELETE')

                            <div class="p-6">
                                <h3 class="font-heading text-xl font-semibold text-[var(--color-text)] mb-2">
                                    Are you sure you want to delete your account?
                                </h3>
                                <p class="text-[var(--color-text-light)] mb-6">
                                    Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.
                                </p>

                                <x-form.input 
                                    name="password" 
                                    label="Password" 
                                    type="password"
                                    placeholder="Enter your password"
                                    required
                                />
                            </div>
                            <div class="flex justify-end gap-3 px-6 py-4 bg-[var(--color-cream)] border-t border-[var(--color-border)]">
                                <button 
                                    type="button" 
                                    @click="showDeleteModal = false" 
                                    class="btn-outline btn-sm"
                                >
                                    Cancel
                                </button>
                                <button type="submit" class="btn-danger btn-sm">
                                    Delete Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>








