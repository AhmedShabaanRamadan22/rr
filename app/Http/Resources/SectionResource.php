<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        $questions = $this->visible_questions;
        if ($this->submitted_form_id) {
            // dd($this->answered_questions($this->submitted_form_id)->get());
            $questions = $this->answered_questions($this->submitted_form_id)
            ->with('question_bank_organization.question_bank.question_type')
            ->get();
            foreach ($questions as $question) {
                $question->submitted_form_id = $this->submitted_form_id;
            }
        }
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'display_flag' => $this->display_flag,
            'form_id' => $this->form_id,
            'arrangement' => $this->arrangement,
            'is_visible' => $this->is_visible,
            'created_at' => $this->created_at,
            'visible_questions' => QuestionResource::collection($questions),
        ];
        return $data;
    }
}