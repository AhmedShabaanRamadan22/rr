<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealOrganizationStageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        // return parent::toArray($request);
        $data = [
            'id' => $this->id,
            'name' => $this->organization_stage->stage_bank->name,
            'is_open' => $this->isOpen(),
            'is_done' => !is_null($this->done_at),
            'done_at' => $this->done_at,
            'description' => $this->organization_stage->stage_bank->description,
            'created_at' => $this->created_at,
            'duration' => $this->duration,
            'has_questions' => $this->organization_stage->visible_questions->isNotEmpty() ? true : false,
            // 'questions' => $this->organization_stage->visible_questions,
        ];

        return $data;

    }
}
