<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmittedSectionResource extends JsonResource
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
            'section_id' => $this->section_id,
            'user_id' => $this->user_id,
            'submitted_form_id' => $this->submitted_form_id,
            'section_name' => $this->section->name,
            'form_name' => $this->submitted_form->form->name,
            'created_at' => $this->created_at,
        ];
    }
}
