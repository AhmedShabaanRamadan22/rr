<?php

namespace App\Http\Requests\External;

use App\Http\Requests\MessageFormRequest;
use App\Models\AttachmentLabel;
use App\Rules\AllowedAttachments;
use App\Rules\AttachmentAllowedExtensions;
use App\Rules\RequiredAttachments;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreUserRequest extends MessageFormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique_soft_delete:users,email'],
            'national_id' => ['required', 'numeric', 'digits:10', 'unique_soft_delete:users,national_id', 'regex:/^[1-9][0-9]*$/'],
            'phone' => ['required', 'numeric', 'digits:9', 'unique_soft_delete:users,phone', 'regex:/^5\d{8}$/'],
            'nationality' => ['required', 'numeric', 'exists:countries,id'],
            'phone_code' => ['required'],
            'national_source' => ['required', 'exists:saudi_cities,id', 'numeric'],
            'national_id_expired' => ['required', 'date', 'date_format:Y-m-d'],
            'birthday' => ['date', 'date_format:Y-m-d'],
            'attachments' => [
                'required',
                'array',
                'min:1',
                new RequiredAttachments('users'),
                new AllowedAttachments('users'),
            ],
            'attachments.*' => ['file', new AttachmentAllowedExtensions(),]
        ];
    }
}
