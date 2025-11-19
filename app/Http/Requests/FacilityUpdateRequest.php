<?php

namespace App\Http\Requests;

use App\Models\Facility;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MessageFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class FacilityUpdateRequest extends MessageFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return Auth::user()->id == Facility::where('id', request()->route('facility')->id)->firstOrFail()->user_id;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $facility = Facility::where('id', request()->route('facility')->id)->first();
        return [
            'facility' => 'sometimes|array',
            'user_id' => 'sometimes',
            'registration_number' => 'sometimes|numeric|digits:10|regex:/^[1-9][0-9]*$/',//|unique_soft_delete:facilities,registration_number,'.$facility->id.'',     //Rule::unique('facilities', 'registration_number')->ignore($facility->id)->whereNotNull('deleted_at')],
            'capacity' => 'sometimes|numeric|digitsBetween:1,5',
            'name' => 'sometimes|max:191',//|unique_soft_delete:facilities,name,'.$facility->id,     //,Rule::unique('facilities', 'name')->ignore($facility->id)],
            'version_date' => 'sometimes',
            'version_date_hj' => 'sometimes',
            'end_date' => 'sometimes',
            'end_date_hj' => 'sometimes',
            'registration_source' => 'sometimes|numeric|exists:saudi_cities,id',
            'license' => 'sometimes|numeric|digitsBetween:9,12|unique_soft_delete:facilities,license,'.$facility->id.'|regex:/^[1-9][0-9]*$/',// Rule::unique('facilities', 'license')->ignore($facility->id)],
            'license_expired' => 'sometimes',
            'license_expired_hj' => 'sometimes',
            'address' => 'sometimes',
            'tax_certificate' => 'sometimes|numeric|digits:15',//|unique_soft_delete:facilities,tax_certificate,'.$facility->id,
            'employee_number' => 'sometimes|numeric:digitsBetween:1,5',
            'chefs_number' => 'sometimes',
            'kitchen_space' => 'sometimes',
            'building_number' => 'sometimes|digits:4|regex:/^[1-9][0-9]*$/',
            'street_name' => 'sometimes',
            'city_id' => 'sometimes|numeric|exists:saudi_cities,id',
            'district_id' => 'sometimes|numeric|exists:districts,id',
            'postal_code' => 'sometimes|digits:5|regex:/^[1-9][0-9]*$/',
            'sub_number' => 'sometimes|digits:4|regex:/^[1-9][0-9]*$/',
            'account_name' => 'sometimes|string',
            'iban' => 'sometimes|string|min:24|max:24',
            'bank_id' => 'sometimes|numeric|exists:banks,id',
        ];
    } //used in store and update in facility controller
}
