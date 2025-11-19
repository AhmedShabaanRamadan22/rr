<?php

namespace App\Http\Resources\WebResources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentLabelResource extends JsonResource
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
            'label' => $this->label,
            'arrangement' => $this->arrangement,
            'placeholder_ar' => $this->placeholder_ar,
            'placeholder_en' => $this->placeholder_en,
            'type' => $this->type,
            'extensions' => $this->extensions,
            'is_required' => $this->is_required,
        ];

        return $data;
    }
}
