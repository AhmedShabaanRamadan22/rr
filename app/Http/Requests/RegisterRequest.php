<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends MessageFormRequest
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
        return[ 
            
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique_soft_delete:users,email',
            'password' => 'sometimes|string|min:8',
            'phone' => 'required|unique_soft_delete:users,phone|numeric|digits:9',
            'phone_code' => 'required',
            'nationality' => 'required|exists:countries,id|numeric',
            'national_id' => 'required|unique_soft_delete:users,national_id|numeric|digits:10|regex:/^[1-9][0-9]*$/',
            'national_source' => 'required|exists:saudi_cities,id|numeric',
            'national_id_expired' => 'required',
            'birthday' => 'required',
            'birthday_hj' => 'required',
            'role' => 'sometimes|string|exists:roles,name',
            
        ];
    }
}
