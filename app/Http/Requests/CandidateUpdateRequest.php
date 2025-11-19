<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateUpdateRequest extends MessageFormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'national_id' => 'required|numeric|digits:10|regex:/^[1-9][0-9]*$/',
            'nationality' => 'required|exists:countries,id|numeric',
            'birthdate' => 'required',
            'birthdate_hj' => 'required',
            'address'=>'required',
            'scrub_size'=>'required|in:s,m,l,xl,2xl,3xl,4xl,5xl',
            'account_name' => 'required|string',
            'owner_national_id' => 'required|numeric|digits:10|regex:/^[1-9][0-9]*$/',
            'iban' => 'required|string|min:24|max:24',
            'bank_id' => 'required|numeric|exists:banks,id',

        ];
    }
}
