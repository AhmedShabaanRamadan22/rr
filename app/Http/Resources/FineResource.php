<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FineResource extends JsonResource
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
            'fine_organization_id' => $this->fine_organization_id,
            'user_id' => $this->user_id,
            'order_sector_id' => $this->order_sector_id,
            'created_at' => $this->created_at,
            'code' => $this->code,
            'notes' => NoteResource::collection($this->notes),
            'attachment_url' => $this->attachment_url,
        ];
        return $data;
    }
}
