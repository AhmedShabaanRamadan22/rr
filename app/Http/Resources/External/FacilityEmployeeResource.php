<?php

namespace App\Http\Resources\External;

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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'national_id' => $this->national_id,
            'position' => $this->position_name,
            'facility_name' => $this->facility_name,
            'attachmentUrl' => $this->whenLoaded('attachments', function () {
                return $this->attachment_url;
            }),

        ];
    }
}
