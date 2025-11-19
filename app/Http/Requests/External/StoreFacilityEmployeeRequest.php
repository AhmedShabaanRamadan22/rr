<?php

namespace App\Http\Requests\External;

use App\Http\Requests\MessageFormRequest;
use App\Models\AttachmentLabel;
use App\Rules\AllowedAttachments;
use App\Rules\AttachmentAllowedExtensions;
use App\Rules\RequiredAttachments;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreFacilityEmployeeRequest extends MessageFormRequest
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
        $current_facility = $this->facility_id;

        return [
            'name' => ['required', 'string', 'max:191', 'unique:facilities,name'],
            'national_id' => [
                'required', 'numeric','digits:10','regex:/^[1-9][0-9]*$/', Rule::unique('facility_employees', 'national_id')
                    ->where(function ($query) use ($current_facility) {
                        $query->where('facility_id', $current_facility)
                            ->whereNull('deleted_at');
                    }),
            ],
            'facility_employee_position_id' => ['required', 'numeric', 'exists:facility_employee_positions,id'],
            'facility_id' => ['required', 'numeric', 'exists:facilities,id'],
            'user_id' => ['required', 'exists:users,id'],

            'attachments' => [
                'required',
                'array',
                'min:1',
                new RequiredAttachments('facility_employees'),
                new AllowedAttachments('facility_employees'),
            ],
            'attachments.*' => ['file', new AttachmentAllowedExtensions(),]
        ];
    }
}
