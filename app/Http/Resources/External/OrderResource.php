<?php

namespace App\Http\Resources\External;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'user_name' => $this->user->name,
            'organization' => $this->organization->name,
            'service' => $this->service->name,
            'status' => $this->status->name,
            'facility' => $this->facility->name,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,

            'notes' => $this->whenLoaded('notes', function () {
                return NoteResource::collection($this->notes()->get());
            }),
        ];
    }
}
