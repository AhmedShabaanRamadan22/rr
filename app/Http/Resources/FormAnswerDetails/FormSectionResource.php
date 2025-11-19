<?php

namespace App\Http\Resources\FormAnswerDetails;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormSectionResource extends JsonResource
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
            'name' => $this->name,
            'questions' => FormQuestionResource::collection($this->visible_questions),
        ];

        return $data;
    }
}
