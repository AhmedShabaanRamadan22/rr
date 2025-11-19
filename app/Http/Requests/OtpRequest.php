<?php

namespace App\Http\Requests;

use App\Http\Requests\MessageFormRequest;

class OtpRequest extends MessageFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'value' => 'sometimes',
            'phone_code' => 'required',
            'phone' => 'required|numeric|digits:9',
            'national_id' => 'sometimes|numeric|digits:10',
            // 'group_id' => 'required',
        ];
    }
}
