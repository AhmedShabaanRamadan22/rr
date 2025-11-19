<?php

namespace App\Http\Resources;

use App\Models\AttachmentLabel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'email' => $this->email,
            'phone' => $this->phone,
            'phone_code' => $this->phone_code,
            'national_id' => $this->national_id,
            'nationality' => $this->nationality,
            'nationality_name' => $this->nationality_name,
            'birthdate' => $this->birthdate,
            'birthdate_hj' => $this->birthdate_hj,
            'scrub_size' => $this->scrub_size,
            'scrub_size_name' => $this->scrub_size_name,
            'previously_work_at_rakaya' => $this->previously_work_at_rakaya,
            'has_relative' => $this->has_relative,
            'address' => $this->address,
            'qualification' => $this->qualification,
            'qualification_name' => $this->qualification_name,
            'department_id' => $this->department_id,
            'department_name' => $this->department_name,
            'status_id' => $this->status_id,
            'status_name' => $this->status->name,
            'self_description' => $this->self_description,
            'gender' => $this->gender,
            'gender_name' => $this->gender_name,
            'resident_status' => $this->resident_status,
            'resident_status_name' => $this->resident_status_name,
            'job_category' => $this->job_category,
            'job_category_name' => $this->job_category_name,
            'marital_status' => $this->marital_status,
            'marital_status_name' => $this->marital_status_name,
            'salary_expectation' => $this->salary_expectation,
            'availability_to_start' => $this->availability_to_start,
            'availability_to_start_name' => $this->availability_to_start_name,
            'years_of_experience' => $this->years_of_experience,
            'years_of_experience_name' => $this->years_of_experience_name,
            'created_at' => $this->created_at,
            'attachment_url' => $this->attachment_url,
            'bank_information' => $this->bank_information,
            'attachment_candidate_profile_personal' => $this->attachment_url->where('attachment_label_id',AttachmentLabel::CANDIDATE_PROFILE_PERSONAL_LABEL)->last(),
            'attachment_candidate_national_id' => $this->attachment_url->where('attachment_label_id',AttachmentLabel::CANDIDATE_NATIONAL_ID)->last(),
            'attachment_candidate_iban' => $this->attachment_url->where('attachment_label_id',AttachmentLabel::CANDIDATE_IBAN)->last(),
            'attachment_candidate_education_certification' => $this->attachment_url->where('attachment_label_id', 48)->last(),
            'attachment_candidate_course_certification' => $this->attachment_url->where('attachment_label_id', 49)->last(),
            'attachment_candidate_experience_certification' => $this->attachment_url->where('attachment_label_id', 50)->last(),
            'attachment_candidate_passport' => $this->attachment_url->where('attachment_label_id', 52)->last(),
            'attachment_candidate_driving_license' => $this->attachment_url->where('attachment_label_id', 53)->last(),
            'attachment_candidate_national_address' => $this->attachment_url->where('attachment_label_id', 54)->last(),

        ];
        return $data;
    }
}
