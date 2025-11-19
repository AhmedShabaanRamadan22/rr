<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmittedFormResource extends JsonResource
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
            'order_sector_id' => $this->order_sector_id,
            'user_id' => $this->user_id,
            'form_id' => $this->form_id,
            'submitted_sections' => $this->submitted_sections,
            'is_completed' => $this->is_completed,
            'created_at' => $this->created_at,
            'form' => new FormResource($this->form, null, $this->id),
            'user' => UserInfoResource::make($this->whenLoaded('user')),
        ];
        return $data;
    }
}
