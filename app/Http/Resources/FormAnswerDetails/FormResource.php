<?php

namespace App\Http\Resources\FormAnswerDetails;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request = null): array
    {
        $data = [
            'id' => $this->id,
            'full_name' => $this->form_full_name ?? trans('translation.no-data'),
            'sections' => FormSectionResource::collection($this->sections_has_question),
        ];

        return $data;
    }
}
