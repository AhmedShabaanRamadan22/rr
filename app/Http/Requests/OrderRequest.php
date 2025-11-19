<?php

namespace App\Http\Requests;

use App\Models\Facility;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MessageFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends MessageFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  Auth::user()->id == Facility::where('id',$this->facility_id)->first()->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // 'name' => 'required',
            'organization_service_id'=>'required|numeric|exists:organization_services,id',
            'facility_id'=>'required|numeric|exists:facilities,id',
        ];
    } //used in store in order controller
}
