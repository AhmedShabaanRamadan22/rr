<?php

namespace App\Http\Resources;

use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssistResource extends JsonResource
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
            'quantity' => $this->quantity,
            'assist_sector_id' => $this->assist_sector_id,
            'support_id' => $this->support_id,
            'assigner_id' => $this->assigner_id,
            'assistant_id' => $this->assistant_id,
            'status_id' => $this->status_id,
            'assist_sector_name' => $this->assist_from,
            'created_at' => $this->created_at,
            'status' => new StatusResource($this->status),
            'attachment_url' => $this->attachment_url,
            'assistant_info' => new UserResource($this->assistant_info),
            'assigner_info' => new UserResource($this->assigner_info),
        ];
        return $data;
    }
}
