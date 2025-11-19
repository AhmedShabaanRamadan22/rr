<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'url' => $this->url,
            'alt' => $this->name,
            'created_at' => $this->created_at,
            'answerable_type' => str_replace('App\\Models\\','',$this->attachmentable->answerable_type ?? ""),
            // 'attachment_label_id' => $this->attachment_label_id,
        ];
        return $data;
    }
}
