<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceListingRequest extends FormRequest
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
            'type' => 'required|string|in:Photographer,Fitter,Feeder,Other',
            'abn' => 'nullable|string|max:14',
            'business_name' => 'required|string|min:2|max:255',
            'contact_name' => 'required|string|min:2|max:255',
            'phone_contact' => 'nullable|string|max:20',
            'email_contact' => 'nullable|email|max:255',
            'locations_covered' => 'required|array|min:1',
            'locations_covered.*' => 'string|in:ACT,NSW,NT,QLD,SA,TAS,VIC,WA',
            'links' => 'nullable|array|max:5',
            'links.*' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
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
            'type.in' => 'Please select a valid service type.',
            'locations_covered.required' => 'Please select at least one location you cover.',
            'locations_covered.min' => 'Please select at least one location you cover.',
            'locations_covered.*.in' => 'Please select valid Australian states.',
            'links.max' => 'You can add a maximum of 5 links.',
            'links.*.url' => 'All links must be valid URLs.',
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

