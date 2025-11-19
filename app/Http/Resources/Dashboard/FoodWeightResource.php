<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodWeightResource extends JsonResource
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
            'organization_id' => $this->classification->organization_id,
            'created_at' => $this->created_at,
            'groupBy' => $this->classification->organization_id,
            // 'chartGroupBy' => $this->status_id,
            // 'chartGroupByDanger' => $this->reason_danger->danger_id,
            
        ];
        return $data;
    }
}
