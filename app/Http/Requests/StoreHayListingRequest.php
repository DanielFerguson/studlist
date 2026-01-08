<?php

namespace App\Http\Requests;

use App\Enums\BaleType;
use App\Enums\HayPriceType;
use App\Enums\HayQualityGrade;
use App\Enums\HayType;
use App\Enums\NitrateLevel;
use App\Enums\SeasonCut;
use App\Enums\StorageType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreHayListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Basic Information
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'hay_type' => ['required', 'string', new Enum(HayType::class)],
            'bale_type' => ['required', 'string', new Enum(BaleType::class)],
            'quantity' => ['required', 'integer', 'min:1', 'max:999999'],
            'weight_per_bale' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'season_cut' => ['nullable', 'string', new Enum(SeasonCut::class)],
            'cut_year' => ['nullable', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],

            // Quality & Testing
            'quality_grade' => ['nullable', 'string', new Enum(HayQualityGrade::class)],
            'test_results_available' => ['nullable', 'boolean'],
            'protein_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'moisture_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'energy_mj_kg' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'nitrate_level' => ['nullable', 'string', new Enum(NitrateLevel::class)],
            'weather_damaged' => ['nullable', 'boolean'],

            // Storage & Location
            'storage_type' => ['required', 'string', new Enum(StorageType::class)],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'delivery_available' => ['nullable', 'boolean'],
            'delivery_radius_km' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'minimum_order_quantity' => ['nullable', 'integer', 'min:1'],

            // Pricing
            'price_type' => ['required', 'string', new Enum(HayPriceType::class)],
            'price_per_bale' => ['nullable', 'numeric', 'min:0', 'max:99999.99'],
            'price_per_tonne' => ['nullable', 'numeric', 'min:0', 'max:99999.99'],

            // Contact
            'business_contact' => ['nullable', 'string', 'max:255'],
            'phone_contact' => ['nullable', 'string', 'max:20'],
            'email_contact' => ['nullable', 'email', 'max:255'],
            'pic_number' => ['nullable', 'string', 'max:20'],

            // Media & Description
            'photos' => ['nullable', 'array', 'max:10'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'photos.max' => 'You can upload a maximum of 10 photos.',
            'photos.*.image' => 'All uploaded files must be images.',
            'photos.*.max' => 'Each image must be smaller than 5MB.',
            'hay_type.Illuminate\Validation\Rules\Enum' => 'Please select a valid hay type.',
            'bale_type.Illuminate\Validation\Rules\Enum' => 'Please select a valid bale type.',
            'price_type.Illuminate\Validation\Rules\Enum' => 'Please select a valid price type.',
            'quality_grade.Illuminate\Validation\Rules\Enum' => 'Please select a valid quality grade.',
            'storage_type.Illuminate\Validation\Rules\Enum' => 'Please select a valid storage type.',
            'season_cut.Illuminate\Validation\Rules\Enum' => 'Please select a valid season cut.',
            'nitrate_level.Illuminate\Validation\Rules\Enum' => 'Please select a valid nitrate level.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Require either phone or email contact
            if (empty($this->input('phone_contact')) && empty($this->input('email_contact'))) {
                $validator->errors()->add('phone_contact', 'Either phone or email contact is required.');
                $validator->errors()->add('email_contact', 'Either phone or email contact is required.');
            }

            // Require price when price type is Per Bale
            if ($this->input('price_type') === 'Per Bale' && empty($this->input('price_per_bale'))) {
                $validator->errors()->add('price_per_bale', 'Price per bale is required when pricing type is Per Bale.');
            }

            // Require price when price type is Per Tonne
            if ($this->input('price_type') === 'Per Tonne' && empty($this->input('price_per_tonne'))) {
                $validator->errors()->add('price_per_tonne', 'Price per tonne is required when pricing type is Per Tonne.');
            }

            // Require delivery radius if delivery is available
            if ($this->boolean('delivery_available') && empty($this->input('delivery_radius_km'))) {
                $validator->errors()->add('delivery_radius_km', 'Delivery radius is required when delivery is available.');
            }
        });
    }
}
