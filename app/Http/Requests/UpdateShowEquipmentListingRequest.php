<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShowEquipmentListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by the controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:2|max:255',
            'description' => 'nullable|string|max:1000',
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max per image
            'condition' => 'required|string|in:New,Like New,Good,Fair,Poor',
            'location' => 'required|string|min:2|max:255',
            'phone_contact' => 'nullable|string|max:20',
            'email_contact' => 'nullable|email|max:255',
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
            'condition.in' => 'Please select a valid condition.',
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
