<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'arrangement' => $this->arrangement,
            'content' => $this->content,
            'questionable_id' => $this->questionable_id,
            'question_bank_organization_id' => $this->question_bank_organization_id,
            'required' => $this->required,
            'visible' => $this->visible,
            'question_type_name' => $this->question_bank_organization->question_bank->question_type->question_type_mobile,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'options' => $this->options,
        ];
        if ($this->submitted_form_id) {
            $data['answers'] = new AnswerResource($this->answer($this->submitted_form_id)->first());
        }
        return $data;
    }
}

