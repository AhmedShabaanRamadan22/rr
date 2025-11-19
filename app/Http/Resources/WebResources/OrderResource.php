<?php

namespace App\Http\Resources\WebResources;

use App\Http\Resources\NoteResource;
use App\Http\Resources\StatusResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'code' => $this->code,
            'status_id' => $this->status_id,
            'facility_id' => $this->facility_id,
            'organization_service_id' => $this->organization_service_id,
            'user_id' => $this->user_id,
            'country_ids' => $this->country_ids,
            'created_at' => $this->created_at,
            'service' => new ServiceResource($this->service),
            'country_organization' => $this->country_organization,
            'status' => new StatusResource($this->status),
            'facility' => new FacilityResource($this->facility),
            'notes' => NoteResource::collection($this->notes) ,
        ];

        return $data;
    }
}
