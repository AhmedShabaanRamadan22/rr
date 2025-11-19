<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class ContactUsRequest extends MessageFormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'             => 'required|string|min:1|max:255',
            'email'            => 'required|email:rfc|string',
            'phone'            => 'required|string',
            'phone_code'       => 'required|string',
            'message'          => 'required|string',
            'subject_id' => 'required|string|exists:subjects,id',
        ];
    }
}
