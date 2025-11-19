<?php

namespace App\Http\Requests\External\Wafir;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ShowMonitorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function () {
            $monitor = $this->route('monitor');

            if (method_exists($monitor, 'hasRole') && !$monitor->hasRole('monitor')) {
                throw new HttpResponseException(response()->json([
                    'flag'                   => false,
                    'general_error_message'  => 'يرجى الاتصال بخدمة العملاء',
                    'message'                => 'The selected user is not a monitor',
                    'errors'                 => ['id' => ['The selected user is not a monitor.']],
                ], 422));
            }
        });
    }
}
