<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReasonDangerResource extends JsonResource
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
            'danger_id' => $this->danger_id,
            'reason_id' => $this->reason_id,
            'organization_id' => $this->organization_id,
            'operation_type_id' => $this->operation_type_id,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'name' => $this->name,
            'danger' => new DangerResource($this->danger),
        ];
        return $data;
    }
}
