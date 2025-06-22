<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGeneticsListingRequest extends FormRequest
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
            'price' => 'required|numeric|min:0|max:999999.99',
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max per image
            'breed' => 'required|string|in:Angus,Hereford,Shorthorn,Charolais,Limousin,Wagyu,Other',
            'type' => 'required|string|in:Semen Straws,Embryos',
            'sire' => 'nullable|string|max:255',
            'dam' => 'nullable|string|max:255',
            'registration_link' => 'nullable|url|max:255',
            'storage_location' => 'required|string|in:ACT,NSW,NT,QLD,SA,TAS,VIC,WA',
            'phone_contact' => 'nullable|string|max:20',
            'email_contact' => 'nullable|email|max:255',
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
            'photos.max' => 'You can upload a maximum of 10 photos.',
            'photos.*.image' => 'All uploaded files must be images.',
            'photos.*.max' => 'Each image must be smaller than 5MB.',
            'breed.in' => 'Please select a valid breed.',
            'type.in' => 'Please select a valid genetics type.',
            'storage_location.in' => 'Please select a valid Australian state.',
            'registration_link.url' => 'The registration link must be a valid URL.',
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
            if (empty($this->input('phone_contact')) && empty($this->input('email_contact'))) {
                $validator->errors()->add('phone_contact', 'Either phone or email contact is required.');
                $validator->errors()->add('email_contact', 'Either phone or email contact is required.');
            }
        });
    }
}