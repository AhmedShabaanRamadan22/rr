<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request = null): array
    {
        $data = [
            'id' => $this->id,
            'status_id' => $this->status_id,
            'facility_id' => $this->facility_id,
            'user_id' => $this->user_id,
            'organization_id' => $this->organization_service->organization_id,
            'created_at' => $this->created_at,
            'groupBy' => $this->organization_service->organization_id,
            'chartGroupBy' => $this->status_id,
        ];
        return $data;
    }
}
