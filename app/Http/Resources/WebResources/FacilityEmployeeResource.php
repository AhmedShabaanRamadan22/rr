<?php

namespace App\Http\Resources\WebResources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilityEmployeeResource extends JsonResource
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
            'national_id' => $this->national_id,
            'name' => $this->name,
            'facility_id' => $this->facility_id,
            'facility_employee_position_id' => $this->facility_employee_position_id,
            'facility_name' => $this->facility_name,
            'attachmentUrl' => $this->attachment_url,
            'position_name' => $this->position_name,
        ];

        return $data;
    }
}
