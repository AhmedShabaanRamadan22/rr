<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmittedFormResource extends JsonResource
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
            'order_sector_id' => $this->order_sector_id,
            'organization_id' => $this->order_sector->sector->classification->organization_id,
            'created_at' => $this->created_at,
            'groupBy' => $this->order_sector->sector->classification->organization_id,
            'chartGroupBy' => $this->form_id,
        ];
        return $data;
    }
}
