<x-layouts.app title="List a Steer">
    <div class="max-w-4xl mx-auto">
        <div class="card-rustic">
            <div class="p-6 border-b border-[var(--color-border)]">
                <h1 class="font-heading text-2xl font-semibold text-[var(--color-text)]">List a Steer</h1>
                <p class="text-[var(--color-text-light)] mt-1">Create a new listing for your steer. You'll be redirected
                    to checkout after creating.</p>
            </div>

            <form method="POST" action="{{ route('steers.store') }}" enctype="multipart/form-data"
                class="p-6 space-y-8">
                @csrf

                {{-- Basic Information --}}
                <x-form.section title="Basic Information" description="Essential details about your steer">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input name="name" label="Steer Name" placeholder="e.g., Buster"
                            description="The name of the steer you are listing." required />

                        <x-form.date-picker name="dob" label="Date of Birth"
                            description="The date when the steer was born." :maxDate="date('Y-m-d')" required />

                        <x-form.select name="breed" label="Breed" :options="App\Enums\Breed::toSelectOptions()"
                            description="The breed of the steer." required />

                        <x-form.select name="colour" label="Colour" :options="App\Enums\Colour::toSelectOptions()"
                            description="The colour of the steer." required />

                        <x-form.input name="location" label="Location (Nearest Town)" placeholder="e.g., Armidale, NSW"
                            description="The nearest town or locality to the steer." required />

                        <x-form.input name="price" label="Price (Optional)" type="number" placeholder="e.g., 2500"
                            description="The asking price for the steer." />
                    </div>
                </x-form.section>

                {{-- Photos --}}
                <x-form.section title="Photos" description="Upload images of your steer">
                    <x-form.file-upload name="photos[]" label="Photos"
                        description="Upload photos of the steer (up to 10 images, max 5MB each)." />
                </x-form.section>

                {{-- Breeding Information --}}
                <x-form.section title="Breeding Information" description="Parentage and breeding details">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input name="sire" label="Sire (Optional)" placeholder="e.g., XYZ Bull"
                            description="The name of the sire (father) of the steer." />

                        <x-form.input name="dam" label="Dam (Optional)" placeholder="e.g., ABC Cow"
                            description="The name of the dam (mother) of the steer." />
                    </div>
                </x-form.section>

                {{-- Contact Information --}}
                <x-form.section title="Contact Information" description="How buyers can reach you">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-form.input name="business_contact" label="Business Contact (Optional)"
                                placeholder="e.g., My Stud" description="Your business name for contact." />
                        </div>

                        <x-form.input name="phone_contact" label="Phone Contact" type="tel"
                            placeholder="e.g., 0412345678"
                            description="Your phone number for contact. Required if no email." />

                        <x-form.input name="email_contact" label="Email Contact" type="email"
                            placeholder="e.g., contact@example.com"
                            description="Your email address for contact. Required if no phone." />

                        <div class="md:col-span-2">
                            <x-form.input name="pic_number" label="PIC Number (Optional)" placeholder="e.g., N123456"
                                description="Your Property Identification Code." />
                        </div>
                    </div>
                </x-form.section>

                {{-- Additional Details --}}
                <x-form.section title="Additional Details" description="Extra information about your steer">
                    <x-form.textarea name="description" label="Description (Optional)"
                        placeholder="Tell us a little about this steer..."
                        description="Provide a detailed description of the steer." :maxlength="500" />

                    <div class="mt-6">
                        <x-form.checkbox name="started_on_feed" label="Started on feed?"
                            description="Check if the steer has started on feed." />
                    </div>
                </x-form.section>

                {{-- Submit --}}
                <div class="flex justify-end pt-6 border-t border-[var(--color-border)]">
                    <a href="{{ route('dashboard') }}" class="btn-outline mr-4">Cancel</a>
                    <button type="submit" class="btn-primary">
                        Create Listing & Subscribe
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>