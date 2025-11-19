<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'reason_danger_id' => $this->reason_danger_id,
            'user_id' => $this->user_id,
            'organization_id' => $this->reason_danger->organization_id,
            'danger_id' => $this->reason_danger->danger_id,
            'created_at' => $this->created_at,
            'groupBy' => $this->reason_danger->organization_id,
            'chartGroupBy' => $this->status_id,
            'chartGroupByDanger' => $this->reason_danger->danger_id,
            
        ];
        return $data;
    }
}
