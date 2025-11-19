<?php

namespace App\Http\Requests;

use App\Models\Facility;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use App\Http\Requests\MessageFormRequest;

class FacilityRequest extends MessageFormRequest
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
        request()->name = Str::of($this->name)->squish()->toString();
        // dd($this->name);
        return [
            'user_id' => 'sometimes',
            'registration_number'=>'required|numeric|digits:10|regex:/^[1-9][0-9]*$/',//|unique_soft_delete:facilities,registration_number/',
            'capacity'=> 'required|numeric|digitsBetween:1,5',
            'name'=>['required','max:191'],//,'unique_soft_delete:facilities,name'],
            'version_date'=>'required',
            'version_date_hj'=>'required',
            'end_date'=>'required',
            'end_date_hj'=>'required',
            'registration_source' => 'required|numeric|exists:saudi_cities,id',
            'license'=>'required|numeric|digitsBetween:9,12|unique_soft_delete:facilities,license|regex:/^[1-9][0-9]*$/',
            'license_expired'=>'required',
            'license_expired_hj'=>'required',
            // 'address'=>'required',
            'tax_certificate'=>'required|numeric|digits:15|regex:/^[1-9][0-9]*$/',//|unique_soft_delete:facilities,tax_certificate',
            'employee_number'=>'required|numeric|digitsBetween:1,5',
            'chefs_number'=>'required',
            'kitchen_space'=>'required',
            'postal_code'=>'required|digits:5|regex:/^[1-9][0-9]*$/',
            'sub_number'=>'required|digits:4|regex:/^[1-9][0-9]*$/',
            'building_number'=>'required|digits:4|regex:/^[1-9][0-9]*$/',
            'street_name'=>'required',
            'city_id' =>'required|numeric|exists:saudi_cities,id',
            'district_id' =>'required|numeric|exists:districts,id',
            'account_name' => 'required|string',
            'iban' => 'required|string|min:24|max:24|regex:/^[1-9a-zA-Z][0-9a-zA-Z]*$/',
            'bank_id' => 'required|numeric|exists:banks,id',
        ];
    }//used in store and update in facility controller

    //! need to replace all the additional spaces and check the uniqueness of the name for the facility

    // protected function getUniqueTrimmedNameRule() : bool
    // {
    //     // $trimmedName = Str::of($this->name)->squish()->toString();
    //     // $trimmedName = trim($trimmedName);
    //     // dd($trimmedName);
    //     // Rule::unique('facilities')->where(fn ($query) => $query->where('name', Str::of($this->name)->squish()->toString());
    //     // $validation = Rule::unique('facilities')->using(function ($q) {
    //     //     $q->where('name',  '=',  Str::of($this->name)->squish()->toString());
    //     // });
    //     //->where('name', $trimmedName);
    //     // ->ignore($this->route('facility'));
    //     return(Facility::where('name',  '=',  Str::of($this->name)->squish()->toString())? false :true);
    // }
    // protected function getUniqueRule()
    // {
    //     $rule = (new Unique('facilities'))->where(function ($query) {
    //         $query->where('name', Str::of(request('name'))->squish()->toString());
    //     });

    //     return($rule);
    // }


}
