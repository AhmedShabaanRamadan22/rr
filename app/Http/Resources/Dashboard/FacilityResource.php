<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
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
            'organization_id' => $this->order_sector->sector->classification->organization_id??null,
            'groupBy' => $this->order_sector->sector->classification->organization_id??null,
        ];
        return $data;
    }
}
