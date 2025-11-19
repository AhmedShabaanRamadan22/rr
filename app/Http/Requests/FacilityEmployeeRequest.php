<?php

namespace App\Http\Requests;

use App\Models\Facility;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MessageFormRequest;

class FacilityEmployeeRequest extends MessageFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->id == Facility::find($this->facility_id)?->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $current_facility = $this;
        // dd($current_facility);
        return [
            'national_id' => [
                'required', 'numeric','digits:10','regex:/^[1-9][0-9]*$/', Rule::unique('facility_employees', 'national_id')
                    ->where(function ($query) use ($current_facility) {
                        $query->where('facility_id', $current_facility->facility_id)
                        ->whereNull('deleted_at');
                    }),
            ],
            'name' => 'required',
            'facility_employee_position_id' => 'required|numeric|exists:facility_employee_positions,id',
            'facility_id' => 'required|numeric',
        ];
    }
}
