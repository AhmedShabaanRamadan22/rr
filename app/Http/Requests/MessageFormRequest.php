<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MessageFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    protected $stopOnFirstFailure = true;
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function messages()
    {
        $environment = App::environment();
        if ($environment === 'production') {
            return [
                'required' => trans('translation.The :attribute field is required.'),
                'unique' => trans('translation.The :attribute already exists.'),
                'numeric' => trans('translation.The :attribute must be numeric.'),
                'digits' => trans('translation.The :attribute must be of :digits digits.'),
                'digits_between' => trans('translation.The :attribute must be between :min - :max digits.'),
                'exists' => trans('translation.Problem accured with :attribute, please conatct customer service.'),
                'min' => trans('translation.The length of the :attribute should be at least :min characters.'),
                'max' => trans('translation.The length of the :attribute should be up to :max characters.'),
                'regex' => trans("translation.The :attribute shouldn't start with 0."),
                'unique_soft_delete' => trans('translation.The :attribute already exists.'),
                'in' => trans('translation.Problem accured with :attribute, please conatct customer service.'),

            ];
        }else{
            return [
                'required' => trans('translation.The :attribute field is required.'),
                'unique' => trans('translation.The :attribute already exists.'),
                'numeric' => trans('translation.The :attribute must be numeric.'),
                'digits' => trans('translation.The :attribute must be of :digits digits.'),
                'digits_between' => trans('translation.The :attribute must be between :min - :max digits.'),
                'exists' => trans('translation.The :attribute must be an id that exist.'),
                'min' => trans('translation.The length of the :attribute should be at least :min characters.'),
                'max' => trans('translation.The length of the :attribute should be up to :max characters.'),
                'regex' => trans("translation.The :attribute shouldn't start with 0."),
                'unique_soft_delete' => trans('translation.The :attribute already exists.'),
                'in' => trans('translation.The :attribute must be a value in :values.'),
            ];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()->first(),
        ], 422));
    }
}
