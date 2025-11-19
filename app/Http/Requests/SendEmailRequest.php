<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'emails' => ['required', 'array'],
            'emails.*' => ['required', 'string', 'email']
        ];
    }
}
