<x-layouts.auth title="Verify email" heading="Verify your email" description="Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed you.">
    @if(session('status') == 'verification-link-sent')
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="space-y-6">
        <p class="text-sm text-[var(--color-text-light)]">
            If you didn't receive the email, click the button below to request another.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary">
                Resend verification email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-center text-sm text-link">
                Log out
            </button>
        </form>
    </div>
</x-layouts.auth>









