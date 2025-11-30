<x-layouts.app title="Edit Service">
    <div class="max-w-4xl mx-auto">
        <div class="card-rustic">
            <div class="p-6 border-b border-[var(--color-border)]">
                <h1 class="font-heading text-2xl font-semibold text-[var(--color-text)]">Edit Service</h1>
                <p class="text-[var(--color-text-light)] mt-1">Update the details for {{ $service->business_name }}.</p>
            </div>

            <form 
                method="POST" 
                action="{{ route('services.update', $service) }}" 
                class="p-6 space-y-8"
                x-data="{ 
                    locations: @js($service->locations_covered ?? ['']),
                    links: @js($service->links ?? [''])
                }"
            >
                @csrf
                @method('PUT')

                {{-- Business Information --}}
                <x-form.section title="Business Information" description="Details about your business">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.select 
                            name="type" 
                            label="Service Type"
                            :options="[
                                'Clipping' => 'Clipping',
                                'Fitting' => 'Fitting',
                                'Photography' => 'Photography',
                                'Transport' => 'Transport',
                                'Veterinary' => 'Veterinary',
                                'Feed Supplier' => 'Feed Supplier',
                                'Show Preparation' => 'Show Preparation',
                                'Other' => 'Other',
                            ]"
                            :value="$service->type"
                            description="The type of service you provide."
                            required
                        />

                        <x-form.input 
                            name="abn" 
                            label="ABN (Optional)" 
                            placeholder="e.g., 12 345 678 901"
                            description="Your Australian Business Number."
                            :value="$service->abn"
                        />

                        <x-form.input 
                            name="business_name" 
                            label="Business Name" 
                            placeholder="e.g., Smith Cattle Services"
                            description="Your business or trading name."
                            :value="$service->business_name"
                            required
                        />

                        <x-form.input 
                            name="contact_name" 
                            label="Contact Name" 
                            placeholder="e.g., John Smith"
                            description="The primary contact person."
                            :value="$service->contact_name"
                            required
                        />
                    </div>
                </x-form.section>

                {{-- Locations Covered --}}
                <x-form.section title="Locations Covered" description="Where you provide your services">
                    <div class="space-y-3">
                        <template x-for="(location, index) in locations" :key="index">
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    :name="'locations_covered[' + index + ']'"
                                    x-model="locations[index]"
                                    placeholder="e.g., NSW, QLD, VIC"
                                    class="form-input flex-1"
                                >
                                <button 
                                    type="button" 
                                    @click="locations.splice(index, 1)"
                                    x-show="locations.length > 1"
                                    class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <button 
                            type="button" 
                            @click="locations.push('')" 
                            class="text-sm text-[var(--color-primary)] hover:underline flex items-center gap-1"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add another location
                        </button>
                        <p class="form-description">Enter the areas/states where you provide services.</p>
                        @error('locations_covered')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </x-form.section>

                {{-- Contact Information --}}
                <x-form.section title="Contact Information" description="How customers can reach you">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            name="phone_contact" 
                            label="Phone Contact" 
                            type="tel"
                            placeholder="e.g., 0412345678"
                            description="Your phone number for contact. Required if no email."
                            :value="$service->phone_contact"
                        />

                        <x-form.input 
                            name="email_contact" 
                            label="Email Contact" 
                            type="email"
                            placeholder="e.g., contact@example.com"
                            description="Your email address for contact. Required if no phone."
                            :value="$service->email_contact"
                        />
                    </div>
                </x-form.section>

                {{-- Links --}}
                <x-form.section title="Links (Optional)" description="Website and social media links">
                    <div class="space-y-3">
                        <template x-for="(link, index) in links" :key="index">
                            <div class="flex gap-2">
                                <input 
                                    type="url" 
                                    :name="'links[' + index + ']'"
                                    x-model="links[index]"
                                    placeholder="e.g., https://www.facebook.com/yourbusiness"
                                    class="form-input flex-1"
                                >
                                <button 
                                    type="button" 
                                    @click="links.splice(index, 1)"
                                    x-show="links.length > 1"
                                    class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <button 
                            type="button" 
                            @click="links.push('')" 
                            class="text-sm text-[var(--color-primary)] hover:underline flex items-center gap-1"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add another link
                        </button>
                        <p class="form-description">Add links to your website, Facebook, Instagram, etc.</p>
                    </div>
                </x-form.section>

                {{-- Additional Details --}}
                <x-form.section title="Additional Details" description="Extra information about your service">
                    <x-form.textarea 
                        name="description" 
                        label="Description (Optional)"
                        placeholder="Describe your services, experience, specialties..."
                        description="Provide a detailed description of your services."
                        :value="$service->description"
                        :maxlength="1000"
                    />
                </x-form.section>

                {{-- Submit --}}
                <div class="flex justify-end pt-6 border-t border-[var(--color-border)]">
                    <a href="{{ route('dashboard') }}" class="btn-outline mr-4">Cancel</a>
                    <button type="submit" class="btn-primary">
                        Update Listing
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>


