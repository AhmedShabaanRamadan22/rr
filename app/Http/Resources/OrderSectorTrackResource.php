<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderSectorTrackResource extends JsonResource
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
            'order_id' => $this->order_id,
            'sector_id' => $this->sector_id,
            'label' => $this->sector->label,
            'organization_id' => $this->sector->classification->organization_id,
            'organization_name' => $this->sector->classification->organization->name,
            // 'parent_id' => $this->parent_id,
            'facility_name' => $this->name,
            // 'is_active' => $this->is_active,
            'child_names' => $this->child_names,
            'order_sector_name' => $this->order_sector_name,
        ];
        return $data;
    }
}
