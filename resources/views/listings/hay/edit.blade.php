<x-layouts.app title="Edit Hay Listing">
    <div class="max-w-4xl mx-auto">
        <div class="card-rustic">
            <div class="p-6 border-b border-[var(--color-border)]">
                <h1 class="font-heading text-2xl font-semibold text-[var(--color-text)]">Edit Hay Listing</h1>
                <p class="text-[var(--color-text-light)] mt-1">Update your hay listing details.</p>
            </div>

            <form method="POST" action="{{ route('hay.update', $hay) }}" enctype="multipart/form-data" class="p-6 space-y-8">
                @csrf
                @method('PUT')

                {{-- Basic Information --}}
                <x-form.section title="Basic Information" description="Essential details about your hay">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-form.input
                                name="title"
                                label="Title"
                                placeholder="e.g., Premium Lucerne Hay - 2024 First Cut"
                                description="A descriptive title for your hay listing."
                                :value="$hay->title"
                                required
                            />
                        </div>

                        <x-form.select
                            name="hay_type"
                            label="Hay Type"
                            :options="App\Enums\HayType::toSelectOptions()"
                            description="The type of hay being offered."
                            :value="$hay->hay_type"
                            required
                        />

                        <x-form.select
                            name="bale_type"
                            label="Bale Type"
                            :options="App\Enums\BaleType::toSelectOptions()"
                            description="The format of the bales."
                            :value="$hay->bale_type"
                            required
                        />

                        <x-form.input
                            name="quantity"
                            label="Quantity Available"
                            type="number"
                            placeholder="e.g., 500"
                            description="Number of bales available."
                            :value="$hay->quantity"
                            required
                        />

                        <x-form.input
                            name="weight_per_bale"
                            label="Weight per Bale (kg)"
                            type="number"
                            step="0.01"
                            placeholder="e.g., 450"
                            description="Average weight per bale in kilograms."
                            :value="$hay->weight_per_bale"
                        />

                        <x-form.select
                            name="season_cut"
                            label="Season/Cut"
                            :options="App\Enums\SeasonCut::toSelectOptions()"
                            description="Which cut of the season."
                            :value="$hay->season_cut"
                        />

                        <x-form.input
                            name="cut_year"
                            label="Cut Year"
                            type="number"
                            placeholder="e.g., {{ date('Y') }}"
                            description="Year the hay was cut."
                            :value="$hay->cut_year"
                        />
                    </div>
                </x-form.section>

                {{-- Photos --}}
                <x-form.section title="Photos" description="Upload images of your hay">
                    <x-form.file-upload
                        name="photos[]"
                        label="Photos"
                        description="Upload photos (up to 10 images, max 5MB each)."
                        :existing="$hay->photos"
                    />
                </x-form.section>

                {{-- Quality & Testing --}}
                <x-form.section title="Quality & Testing" description="Quality grade and test results">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.select
                            name="quality_grade"
                            label="Quality Grade"
                            :options="App\Enums\HayQualityGrade::toSelectOptions()"
                            description="Overall quality grade of the hay."
                            :value="$hay->quality_grade"
                        />

                        <x-form.checkbox
                            name="test_results_available"
                            label="Test Results Available"
                            description="Check if you have feed test results available."
                            :checked="$hay->test_results_available"
                        />

                        <x-form.input
                            name="protein_percentage"
                            label="Protein %"
                            type="number"
                            step="0.1"
                            placeholder="e.g., 18.5"
                            description="Crude protein percentage from feed test."
                            :value="$hay->protein_percentage"
                        />

                        <x-form.input
                            name="moisture_percentage"
                            label="Moisture %"
                            type="number"
                            step="0.1"
                            placeholder="e.g., 12.0"
                            description="Moisture content percentage."
                            :value="$hay->moisture_percentage"
                        />

                        <x-form.input
                            name="energy_mj_kg"
                            label="Energy (MJ/kg DM)"
                            type="number"
                            step="0.1"
                            placeholder="e.g., 10.5"
                            description="Metabolisable energy content."
                            :value="$hay->energy_mj_kg"
                        />

                        <x-form.select
                            name="nitrate_level"
                            label="Nitrate Level"
                            :options="App\Enums\NitrateLevel::toSelectOptions()"
                            description="Nitrate level from testing."
                            :value="$hay->nitrate_level"
                        />

                        <x-form.checkbox
                            name="weather_damaged"
                            label="Weather Damaged"
                            description="Check if the hay has any weather damage."
                            :checked="$hay->weather_damaged"
                        />
                    </div>
                </x-form.section>

                {{-- Storage & Location --}}
                <x-form.section title="Storage & Location" description="Where the hay is stored and delivery options">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.select
                            name="storage_type"
                            label="Storage Type"
                            :options="App\Enums\StorageType::toSelectOptions()"
                            description="How the hay is currently stored."
                            :value="$hay->storage_type"
                            required
                        />

                        <x-form.input
                            name="location"
                            label="Location"
                            placeholder="e.g., Tamworth, NSW"
                            description="Town and state where hay is located."
                            :value="$hay->location"
                            required
                        />

                        <x-form.checkbox
                            name="delivery_available"
                            label="Delivery Available"
                            description="Check if you can deliver the hay."
                            :checked="$hay->delivery_available"
                        />

                        <x-form.input
                            name="delivery_radius_km"
                            label="Delivery Radius (km)"
                            type="number"
                            placeholder="e.g., 100"
                            description="Maximum delivery distance in kilometers."
                            :value="$hay->delivery_radius_km"
                        />

                        <x-form.input
                            name="minimum_order_quantity"
                            label="Minimum Order Quantity"
                            type="number"
                            placeholder="e.g., 10"
                            description="Minimum number of bales per order."
                            :value="$hay->minimum_order_quantity"
                        />
                    </div>
                </x-form.section>

                {{-- Pricing --}}
                <x-form.section title="Pricing" description="Set your pricing">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <x-form.select
                            name="price_type"
                            label="Price Type"
                            :options="App\Enums\HayPriceType::toSelectOptions()"
                            description="How you want to price the hay."
                            :value="$hay->price_type"
                            required
                        />

                        <x-form.input
                            name="price_per_bale"
                            label="Price per Bale ($)"
                            type="number"
                            step="0.01"
                            placeholder="e.g., 150.00"
                            description="Price per individual bale."
                            :value="$hay->price_per_bale"
                        />

                        <x-form.input
                            name="price_per_tonne"
                            label="Price per Tonne ($)"
                            type="number"
                            step="0.01"
                            placeholder="e.g., 350.00"
                            description="Price per tonne."
                            :value="$hay->price_per_tonne"
                        />
                    </div>
                </x-form.section>

                {{-- Contact Information --}}
                <x-form.section title="Contact Information" description="How buyers can reach you">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input
                            name="business_contact"
                            label="Business Name"
                            placeholder="e.g., Smith Hay Supplies"
                            description="Your business name (optional)."
                            :value="$hay->business_contact"
                        />

                        <x-form.input
                            name="phone_contact"
                            label="Phone Contact"
                            type="tel"
                            placeholder="e.g., 0412345678"
                            description="Your phone number for contact. Required if no email."
                            :value="$hay->phone_contact"
                        />

                        <x-form.input
                            name="email_contact"
                            label="Email Contact"
                            type="email"
                            placeholder="e.g., contact@example.com"
                            description="Your email address for contact. Required if no phone."
                            :value="$hay->email_contact"
                        />

                        <x-form.input
                            name="pic_number"
                            label="PIC Number"
                            placeholder="e.g., NABC1234"
                            description="Property Identification Code (optional)."
                            :value="$hay->pic_number"
                        />
                    </div>
                </x-form.section>

                {{-- Additional Details --}}
                <x-form.section title="Additional Details" description="Extra information about your hay">
                    <x-form.textarea
                        name="description"
                        label="Description (Optional)"
                        placeholder="Provide details about the hay quality, handling, storage conditions, availability..."
                        description="Provide a detailed description."
                        :maxlength="2000"
                        :value="$hay->description"
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
