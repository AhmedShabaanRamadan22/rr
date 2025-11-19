<?php

namespace App\Http\Resources\Supervisor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupervisorSectorResource extends JsonResource
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
            'sector_label' => $this->label,
            'organization_id' => $this->classification->organization_id,
        ];
        return $data;
    }
}
