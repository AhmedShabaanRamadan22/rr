<?php

namespace App\Http\Requests\External;

use App\Rules\AllowedAttachments;
use App\Rules\AttachmentAllowedExtensions;
use App\Rules\RequiredAttachments;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFacilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191', 'unique:facilities,name'],
            'registration_number' => ['required', 'integer', 'max_digits:20', 'unique:facilities,registration_number'],
            'version_date' => ['required', 'date', 'date_format:Y-m-d'],
            'version_date_hj' => ['required', 'string', 'max:191'],
            'end_date' => ['required', 'date', 'date_format:Y-m-d'],
            'end_date_hj' => ['required', 'string', 'max:191'],
            'registration_source' => ['required', 'integer', 'exists:saudi_cities,id'],
            'license' => ['required', 'integer', 'unique:facilities,license'],
            'license_expired' => ['required', 'date', 'date_format:Y-m-d'],
            'license_expired_hj' => ['required', 'string', 'max:191'],
            'capacity' => ['required', 'integer', 'min:1'],
            'tax_certificate' => ['required', 'integer', 'unique:facilities,tax_certificate'],
            'employee_number' => ['required', 'integer', 'min:0'],
            'chefs_number' => ['nullable', 'integer', 'min:0'],
            'kitchen_space' => ['nullable', 'integer', 'min:0'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'street_name' => ['required', 'string', 'max:191'],
            'district_id' => ['required', 'integer', 'exists:districts,id'],
            'city_id' => ['required', 'integer', 'exists:saudi_cities,id'],
            'building_number' => ['required', 'integer', 'min:0'],
            'postal_code' => ['required', 'integer'],
            'sub_number' => ['nullable', 'integer', 'min:0'],

            'iban' => ['sometimes', 'string', 'max:191', 'regex:/^SA[0-9]{22}$/'],
            'account_name' => ['string', 'max:191', 'required_with:iban'],
            'bank_id' => ['integer', 'exists:banks,id', 'required_with:iban'],

            'attachments' => [
                'required',
                'array',
                'min:1',
                new RequiredAttachments('facilities'),
                new AllowedAttachments('facilities'),
            ],
            'attachments.*' => ['file', new AttachmentAllowedExtensions(),]
        ];
    }
}
