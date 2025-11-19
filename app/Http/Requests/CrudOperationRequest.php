<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CrudOperationRequest extends FormRequest
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
        // dd(str_replace('-','_',$this->route()->uri).',name');
        // dd($this->route()->uri);
        $route_uri =  $this->route()->uri;
        $pass_unique = !in_array($route_uri,['food', 'sectors']);
        $name_unique = '';
        $label_unique = '';
        if($pass_unique){
            $name_unique = $route_uri == "roles" ? "|unique" : "|unique_soft_delete";
            $label_unique = "|unique_soft_delete";
        }

        return [
            "name" => "sometimes".$name_unique.":" . str_replace('-', '_', $this->route()->uri) . ',name',
            "name_ar" => "sometimes|unique_soft_delete:" . str_replace('-', '_', $this->route()->uri) . ',name_ar',
            "name_en" => "sometimes|unique_soft_delete:" . str_replace('-', '_', $this->route()->uri) . ',name_en',
            "label" => "sometimes" . $label_unique . ":" . str_replace('-', '_', $this->route()->uri) . ',label',
            "code" => "sometimes|unique_soft_delete:" . str_replace('-', '_', $this->route()->uri) . ',code',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        // Extract the first validation error message and attribute
        $firstError = $errors->first();
        $failedAttribute = key($errors->messages());

        // Build the error message
        $errorMessage = "Validation failed for $failedAttribute: $firstError";

        // Flash the error message to the session
        $this->session()->flash('message', $validator->errors()->first());
        // $this->session()->flash('message', $errorMessage);
        $this->session()->flash('alert-type', 'error');

        // Redirect back with input and error message
        throw new HttpResponseException(redirect()->back()->withInput());
    }

    public function messages()
    {
        return [
            // 'required' => trans('translation.The :attribute field is required.'),
            // 'unique' => trans('translation.The :attribute already exists.'),
            // 'numeric' => trans('translation.The :attribute must be numeric.'),
            // 'digits' => trans('translation.The :attribute must be of :digits digits.'),
            // 'digits_between' => trans('translation.The :attribute must be between :min - :max digits.'),
            // 'exists' => trans('translation.The :attribute must be an id that exist.'),
            // 'min' => trans('translation.The length of the :attribute should be at least :min characters.'),
            // 'max' => trans('translation.The length of the :attribute should be up to :max characters.'),
            // 'regex' => trans("translation.The :attribute shouldn't start with 0."),
            'unique_soft_delete' => trans('translation.The :attribute already exists.'),
            // 'in' => trans('translation.The :attribute must be a value in :values.'),
        ];
    }
}