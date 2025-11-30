<x-layouts.app title="List Show Equipment">
    <div class="max-w-4xl mx-auto">
        <div class="card-rustic">
            <div class="p-6 border-b border-[var(--color-border)]">
                <h1 class="font-heading text-2xl font-semibold text-[var(--color-text)]">List Show Equipment</h1>
                <p class="text-[var(--color-text-light)] mt-1">Create a new equipment listing. Equipment listings are free to post.</p>
            </div>

            <form method="POST" action="{{ route('show-equipment.store') }}" enctype="multipart/form-data" class="p-6 space-y-8">
                @csrf

                {{-- Basic Information --}}
                <x-form.section title="Basic Information" description="Essential details about your equipment">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-form.input 
                                name="title" 
                                label="Title" 
                                placeholder="e.g., Show Halter Set - Leather"
                                description="A descriptive title for your equipment."
                                required
                            />
                        </div>

                        <x-form.select 
                            name="condition" 
                            label="Condition"
                            :options="[
                                'New' => 'New',
                                'Like New' => 'Like New',
                                'Good' => 'Good',
                                'Fair' => 'Fair',
                                'Poor' => 'Poor',
                            ]"
                            description="The condition of the equipment."
                            required
                        />

                        <x-form.input 
                            name="location" 
                            label="Location" 
                            placeholder="e.g., Brisbane, QLD"
                            description="Where the equipment is located."
                            required
                        />
                    </div>
                </x-form.section>

                {{-- Photos --}}
                <x-form.section title="Photos" description="Upload images of your equipment">
                    <x-form.file-upload 
                        name="photos[]" 
                        label="Photos"
                        description="Upload photos of the equipment (up to 10 images, max 5MB each)."
                    />
                </x-form.section>

                {{-- Contact Information --}}
                <x-form.section title="Contact Information" description="How buyers can reach you">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            name="phone_contact" 
                            label="Phone Contact" 
                            type="tel"
                            placeholder="e.g., 0412345678"
                            description="Your phone number for contact. Required if no email."
                        />

                        <x-form.input 
                            name="email_contact" 
                            label="Email Contact" 
                            type="email"
                            placeholder="e.g., contact@example.com"
                            description="Your email address for contact. Required if no phone."
                        />
                    </div>
                </x-form.section>

                {{-- Additional Details --}}
                <x-form.section title="Additional Details" description="Extra information about your equipment">
                    <x-form.textarea 
                        name="description" 
                        label="Description (Optional)"
                        placeholder="Describe the equipment, including size, brand, any wear, etc..."
                        description="Provide a detailed description."
                        :maxlength="500"
                    />
                </x-form.section>

                {{-- Submit --}}
                <div class="flex justify-end pt-6 border-t border-[var(--color-border)]">
                    <a href="{{ route('dashboard') }}" class="btn-outline mr-4">Cancel</a>
                    <button type="submit" class="btn-primary">
                        Create Listing
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>

