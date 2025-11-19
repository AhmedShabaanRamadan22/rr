<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
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
            'value' => $this->value,
            'user_id' => $this->user_id,
            'question_id' => $this->question_id,
            'answerable_id' => $this->answerable_id,
            'answerable_type' => $this->answerable_type,
            'actual_value' => $this->actual_value,
            'created_at' => $this->created_at,
        ];
        return $data;
    }
}
