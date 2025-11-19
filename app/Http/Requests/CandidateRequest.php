<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class CandidateRequest extends MessageFormRequest
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
            'name'                  => 'required|string|min:1|max:255',
            'email'                 => 'required|email:rfc|string',
            'previously_work_at_rakaya'=> 'sometimes|string',
            'has_relative'          => 'sometimes|string',
            'qualification'         => 'required|string|in:high_school,diploma,bachelor,master,phd',
            'department_id'         => 'required|integer|exists:departments,id',
            'self_description'      => 'required|string|min:1',
            'gender'                => 'required|string|in:male,female',
            'resident_status'       => 'required|string|in:citizen,resident,visitor,other',
            'salary_expectation'    => 'required|integer',
            'availability_to_start' => 'required|string|in:now,two_to_four_weeks,four_to_eight_weeks,more_than_eight_weeks',
            'job_category'          => 'required|string|in:full_time,part_time,remotely,hybrid,training,seasonal',
            'years_of_experience'   => 'required|string|in:0,1,2,3,4,5,+6,+10',
            'marital_status'        => 'required|string|in:single,married,divorced,widowed,other', // أعزب، متزوج، مطلق، أرمل، أو أخرى
        ] + $this->phone_rules();
    }

    public function phone_rules(){
        if(request()->resident_status == 'other'){
            return [];
        }
        
        return [
            'phone'                 => 'required|string',
            'phone_code'            => 'required|string',
        ];
    }
}
