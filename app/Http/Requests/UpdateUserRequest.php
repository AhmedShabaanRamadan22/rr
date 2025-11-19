<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends MessageFormRequest
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
        $id = Auth::user()->id;
        return [

            'name' => 'sometimes|string|max:191',
            'email' => 'sometimes|string|email|max:191|unique_soft_delete:users,email,'.$id,// Rule::unique('users', 'email')->ignore(Auth::user()->id)],
            'phone' => 'sometimes|numeric|digits:9|unique_soft_delete:users,phone,'.$id,// Rule::unique('users', 'phone')->ignore(Auth::user()->id)],
            'phone_code' => 'sometimes',
            'nationality' => 'sometimes|exists:countries,id|numeric',
            'national_id' => 'sometimes|numeric|digits:10|unique_soft_delete:users,national_id,'.$id.'|regex:/^[1-9][0-9]*$/',// Rule::unique('users', 'national_id')->ignore(Auth::user()->id)],
            'national_source' => 'sometimes|exists:saudi_cities,id|numeric',
            'national_id_expired' => 'sometimes',
            'birthday' => 'sometimes',
            'birthday_hj' => 'sometimes',
            // 'role' => 'sometimes|string|exists:roles,name',
            // 'account_name' => 'sometimes|string',
            // 'iban' => 'sometimes|digits:24',
            // 'bank_id' => 'sometimes|numeric|exists:banks,id',

        ];
    }
}
