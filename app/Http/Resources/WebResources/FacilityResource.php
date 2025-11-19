<?php

namespace App\Http\Resources\WebResources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\WebResources\FacilityEmployeeResource;

class FacilityResource extends JsonResource
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
            'registration_number' => $this->registration_number,
            'version_date' => $this->version_date,
            'version_date_hj' => $this->version_date_hj,
            'end_date' => $this->end_date,
            'end_date_hj' => $this->end_date_hj,
            'registration_source' => $this->registration_source,
            'license' => $this->license,
            'license_expired' => $this->license_expired,
            'license_expired_hj' => $this->license_expired_hj,
            'capacity' => $this->capacity,
            'tax_certificate' => $this->tax_certificate,
            'employee_number' => $this->employee_number,
            'chefs_number' => $this->chefs_number,
            'kitchen_space' => $this->kitchen_space,
            'user_id' => $this->user_id,
            'street_name' => $this->street_name,
            'district_id' => $this->district_id,
            'city_id' => $this->city_id,
            'building_number' => $this->building_number,
            'postal_code' => $this->postal_code,
            'sub_number' => $this->sub_number,
            'city' => $this->city,
            'registration_source_name' => $this->registration_source_name,
            'district' => $this->district,
            'attachmentUrl' => $this->attachment_url,
            'bank_information' => new BankInformationResource($this->iban),
            'all_facility_employees' => FacilityEmployeeResource::collection($this->facility_employees),
        ];

        return $data;
    }
}
