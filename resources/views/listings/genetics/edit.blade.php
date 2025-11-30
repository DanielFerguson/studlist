<x-layouts.app title="Edit Genetics">
    <div class="max-w-4xl mx-auto">
        <div class="card-rustic">
            <div class="p-6 border-b border-[var(--color-border)]">
                <h1 class="font-heading text-2xl font-semibold text-[var(--color-text)]">Edit Genetics</h1>
                <p class="text-[var(--color-text-light)] mt-1">Update the details for {{ $genetics->name }}.</p>
            </div>

            <form method="POST" action="{{ route('genetics.update', $genetics) }}" enctype="multipart/form-data" class="p-6 space-y-8">
                @csrf
                @method('PUT')

                {{-- Basic Information --}}
                <x-form.section title="Basic Information" description="Essential details about your genetics listing">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            name="name" 
                            label="Listing Name" 
                            placeholder="e.g., Premium Angus Semen"
                            description="A descriptive name for this genetics listing."
                            :value="$genetics->name"
                            required
                        />

                        <x-form.input 
                            name="price" 
                            label="Price" 
                            type="number"
                            placeholder="e.g., 150"
                            description="The asking price per unit."
                            :value="$genetics->price"
                            required
                        />

                        <x-form.select 
                            name="type" 
                            label="Type"
                            :options="App\Enums\GeneticsType::toSelectOptions()"
                            :value="$genetics->type"
                            description="The type of genetics being offered."
                            required
                        />

                        <x-form.select 
                            name="breed" 
                            label="Breed"
                            :options="App\Enums\Breed::toSelectOptions()"
                            :value="$genetics->breed"
                            description="The breed of the genetics."
                            required
                        />

                        <div class="md:col-span-2">
                            <x-form.input 
                                name="storage_location" 
                                label="Storage Location" 
                                placeholder="e.g., ABC Genetics Centre, Sydney"
                                description="Where the genetics are currently stored."
                                :value="$genetics->storage_location"
                                required
                            />
                        </div>
                    </div>
                </x-form.section>

                {{-- Photos --}}
                <x-form.section title="Photos" description="Upload images related to this genetics listing">
                    <x-form.file-upload 
                        name="photos[]" 
                        label="Photos"
                        description="Upload additional photos (up to 10 images, max 5MB each). Existing photos will be kept."
                        :existingFiles="$genetics->photos ?? []"
                    />
                </x-form.section>

                {{-- Breeding Information --}}
                <x-form.section title="Breeding Information" description="Parentage and registration details">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            name="sire" 
                            label="Sire (Optional)" 
                            placeholder="e.g., XYZ Bull"
                            description="The name of the sire."
                            :value="$genetics->sire"
                        />

                        <x-form.input 
                            name="dam" 
                            label="Dam (Optional)" 
                            placeholder="e.g., ABC Cow"
                            description="The name of the dam (for embryos)."
                            :value="$genetics->dam"
                        />

                        <div class="md:col-span-2">
                            <x-form.input 
                                name="registration_link" 
                                label="Registration Link (Optional)" 
                                type="url"
                                placeholder="e.g., https://breedplan.une.edu.au/..."
                                description="Link to official registration or Breedplan data."
                                :value="$genetics->registration_link"
                            />
                        </div>
                    </div>
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
                            :value="$genetics->phone_contact"
                        />

                        <x-form.input 
                            name="email_contact" 
                            label="Email Contact" 
                            type="email"
                            placeholder="e.g., contact@example.com"
                            description="Your email address for contact. Required if no phone."
                            :value="$genetics->email_contact"
                        />
                    </div>
                </x-form.section>

                {{-- Additional Details --}}
                <x-form.section title="Additional Details" description="Extra information about this listing">
                    <x-form.textarea 
                        name="description" 
                        label="Description (Optional)"
                        placeholder="Provide details about the genetics, availability, shipping options..."
                        description="Provide a detailed description."
                        :value="$genetics->description"
                        :maxlength="500"
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


