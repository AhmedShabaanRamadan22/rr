<?php

namespace App\Http\Resources\Supervisor;

use App\Http\Resources\NoteResource;
use App\Http\Resources\ReasonDangerResource;
use App\Http\Resources\StatusResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupervisorTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $sector = $this->order_sector->sector;
        $data = [
            'id' => $this->id,
            'reason_danger_id' => $this->reason_danger_id,
            'user_id' => $this->user_id,
            'status_id' => $this->status_id,
            'order_sector_id' => $this->order_sector_id,
            'created_at' => $this->created_at,
            'code' => $this->code,
            'notes' => NoteResource::collection($this->notes),
            'attachment_url' => $this->attachment_url,
            'reason_danger' => new ReasonDangerResource($this->reason_danger),
            'status' => new StatusResource($this->status),

            'organization' => $sector->classification->organization->name ?? '-',
            'user_name' => $this->user->name,
            'facility' => $this->order_sector->order->facility->name,
            'sector_label' => $sector->label,
            'sector_id' => $sector->id,
        ];
        return $data;
    }
}
