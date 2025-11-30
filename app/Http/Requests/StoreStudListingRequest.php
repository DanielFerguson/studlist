<?php

namespace App\Http\Requests;

use App\Enums\Breed;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreStudListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by auth middleware on the route
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max per image
            'dob' => 'required|date|before:today|after:1900-01-01',
            'breed' => ['required', 'string', new Enum(Breed::class)],
            'colour' => 'required|string|in:Black,Red,White,Brown,Grey,Dun,Other',
            'tattoo_number' => 'nullable|string|max:50',
            'location' => 'required|string|min:2|max:255',
            'sire' => 'nullable|string|max:255',
            'dam' => 'nullable|string|max:255',
            'registration_link' => 'nullable|url|max:500',
            'business_contact' => 'nullable|string|max:255',
            'phone_contact' => 'nullable|string|max:20',
            'email_contact' => 'nullable|email|max:255',
            'pic_number' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'dob.before' => 'Date of birth must be in the past.',
            'dob.after' => 'Date of birth must be after 1900.',
            'photos.max' => 'You can upload a maximum of 10 photos.',
            'photos.*.image' => 'All uploaded files must be images.',
            'photos.*.max' => 'Each image must be smaller than 5MB.',
            'breed.Illuminate\Validation\Rules\Enum' => 'Please select a valid breed.',
            'colour.in' => 'Please select a valid colour.',
            'registration_link.url' => 'Registration link must be a valid URL.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Custom validation: require either phone or email
            if (empty($this->phone_contact) && empty($this->email_contact)) {
                $validator->errors()->add('phone_contact', 'Either phone or email contact is required.');
                $validator->errors()->add('email_contact', 'Either phone or email contact is required.');
            }
        });
    }
}
