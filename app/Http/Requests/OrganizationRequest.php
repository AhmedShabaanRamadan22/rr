<?php

namespace App\Http\Requests;

use App\Http\Requests\MessageFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends MessageFormRequest
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
        $id = $this->organization->id ?? 0;
        return [
            'name_ar'=>'sometimes|unique:organizations,name_ar,'.$id.',id',
            'name_en'=>'sometimes|unique:organizations,name_en,'.$id.',id',
            'domain'=>'sometimes|unique:organizations,domain,'.$id.',id',
            // 'phone_number'=>'regex:/^\d{10}$/',
            'whatsapp_instance_id' => 'sometimes|unique:organizations,whatsapp_instance_id,'.$id.',id',
            'whatsapp_token' => 'sometimes|unique:organizations,whatsapp_token,'.$id.',id'
        ];
    }
}
