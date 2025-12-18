<x-layouts.app title="Contact Settings">
    <div class="max-w-2xl mx-auto">
        <div class="card-rustic">
            <div class="p-6 border-b border-[var(--color-border)]">
                <h2 class="font-heading text-xl font-semibold text-[var(--color-text)]">Contact Information</h2>
                <p class="text-sm text-[var(--color-text-light)] mt-1">Manage your default contact details. These will be auto-filled when you create new listings.</p>
            </div>
            
            <form method="POST" action="{{ route('contact.update') }}" class="p-6 space-y-6">
                @csrf
                @method('PATCH')

                <x-form.input 
                    name="contact_business_name" 
                    label="Business Name" 
                    placeholder="e.g., My Stud"
                    :value="$user->contact_business_name"
                    description="Your business or stud name for listings."
                />

                <x-form.input 
                    name="contact_phone" 
                    label="Phone Number" 
                    type="tel"
                    placeholder="e.g., 0412345678"
                    :value="$user->contact_phone"
                    description="Your contact phone number for listings."
                />

                <x-form.input 
                    name="contact_email" 
                    label="Contact Email" 
                    type="email"
                    placeholder="e.g., contact@example.com"
                    :value="$user->contact_email ?? $user->email"
                    description="Your contact email for listings. Defaults to your account email."
                />

                <x-form.input 
                    name="contact_pic_number" 
                    label="PIC Number" 
                    placeholder="e.g., N123456"
                    :value="$user->contact_pic_number"
                    description="Your Property Identification Code."
                />

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>











