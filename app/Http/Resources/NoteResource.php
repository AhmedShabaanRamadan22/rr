<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
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
            'content' => $this->content,
            'notable_id' => $this->notable_id,
            'notable_type' => $this->notable_type,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            // 'deleted_at' => $this->deleted_at,
            'since' => $this->since,
            'user_name' => $this->user_name,
        ];
        return $data;
    }
}
